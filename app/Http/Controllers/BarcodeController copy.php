<?php
namespace App\Http\Controllers;

use App\Models\GtinMapping;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorPNG;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BarcodeController extends Controller
{
    public function index()  { return view('barcode.index'); }
    public function create() { return view('barcode.create'); }

    public function store(Request $request)
    {
        return response()->json($request->all());
    }

public function batch(Request $req)
{
    $req->validate([
        'order_no'   => ['required','string','max:64'],
        'product_no' => ['required','string','max:32', Rule::exists('products','product_no')],
        'season'     => ['required','string','max:32'],
        'quantity'   => ['required','integer','min:1'],
        'color_code' => ['required','string','max:32'],
        'size_code'  => ['required','string','max:16'],
    ], [
        'product_no.exists' => 'Product number does not exist. Please create the product first.',
    ]);

    $product = Product::where('product_no', $req->product_no)->firstOrFail();

    if (!$product) {
        return response()->json([
            'ok'      => false,
            'message' => "Product number was not found. Please create the product first.",
            'data'    => ['product_no' => $req->product_no],
        ], 422, ['Content-Type' => 'application/json; charset=utf-8']);
    }
    $qty     = max(1, (int) $req->quantity);

    $digits13 = preg_replace('/\D/', '', $req->product_no.$req->color_code.$req->size_code.$req->season);
    $digits13 = str_pad(substr($digits13, 0, 13), 13, '0');
    $gtin14   = $digits13 . $this->checkDigit($digits13);

    Storage::disk('public')->makeDirectory('qrcodes');
    // Storage::disk('public')->makeDirectory('barcodes');
    $barcodeGen = new BarcodeGeneratorPNG();
    $suffixWidth = ($qty >= 100) ? 0 : 2;

    DB::beginTransaction();
    try {
        $items = [];

        for ($i = 1; $i <= $qty; $i++) {
            $suffix = ($suffixWidth > 0)
                ? str_pad((string)$i, $suffixWidth, '0', STR_PAD_LEFT)
                : (string)$i;

            $gtin16 = $gtin14 . $suffix;

            $payload = env('APP_URL'). "/{$gtin16}";

            $qrRel = "qrcodes/qr_{$gtin16}.png";
            $qrPng = QrCode::format('png')->size(256)->margin(1)->generate($payload);
            Storage::disk('public')->put($qrRel, $qrPng);

            // $bcRel = "barcodes/barcode_{$gtin16}.png";
            // Storage::disk('public')->put($bcRel, $barcodeGen->getBarcode($gtin16, $barcodeGen::TYPE_CODE_128));

            GtinMapping::create([
                'product_id'   => $product->id,
                'order_no'     => $req->order_no,
                'product_no'   => $req->product_no,
                'season'       => $req->season,
                'color_code'   => $req->color_code,
                'size_code'    => $req->size_code,
                'gtin14'       => $gtin14,
                'gtin16'       => $gtin16,
                'quantity'     => $qty,
                'qr_path'      => "storage/{$qrRel}",
                // 'barcode_path' => "storage/{$bcRel}",
            ]);

            $items[] = [
                'index'   => $i,
                'gtin14'  => $gtin14,
                'gtin16'  => $gtin16,
                'payload' => $payload,
                'qr'      => asset("storage/{$qrRel}"),
                // 'barcode' => asset("storage/{$bcRel}"),
            ];
        }

        DB::commit();
        return response()->json([
            'ok'     => true,
            'count'  => $qty,
            'gtin14' => $gtin14,
            'items'  => $items,
        ]);
    } catch (\Throwable $e) {
        DB::rollBack();
        return response()->json(['ok' => false, 'message' => $e->getMessage()], 500);
    }
}

private function checkDigit(string $digits13): int
{
    $sum = 0;
    for ($i=0; $i<13; $i++) {
        $n = (int) $digits13[12 - $i];
        $sum += ($i % 2 === 0) ? $n * 3 : $n;
    }
    return (10 - ($sum % 10)) % 10;
}



public function extract(Request $request)
{
    $fileField = null;
    foreach (['image', 'file', 'photo'] as $name) {
        if ($request->hasFile($name)) { $fileField = $name; break; }
    }

    $request->validate([
        'image'        => 'required_without_all:file,photo,image_base64|file|max:20480',
        'file'         => 'required_without_all:image,photo,image_base64|file|max:20480',
        'photo'        => 'required_without_all:image,file,image_base64|file|max:20480',
        'image_base64' => 'required_without_all:image,file,photo|string',
    ], [
        'image.required_without_all'        => 'Provide an image file in "image", "file", or "photo", or base64 in "image_base64".',
        'file.required_without_all'         => 'Provide an image file in "image", "file", or "photo", or base64 in "image_base64".',
        'photo.required_without_all'        => 'Provide an image file in "image", "file", or "photo", or base64 in "image_base64".',
        'image_base64.required_without_all' => 'Provide an image file in "image", "file", or "photo", or base64 in "image_base64".',
    ]);

    $apiKey = env('GEMINI_API_KEY');
    if (!$apiKey) return response()->json(['error' => 'GEMINI_API_KEY not configured'], 500);

    $model = env('GEMINI_MODEL', 'gemini-2.5-flash-lite');

    $mime = null;
    $data64 = null;

    if ($fileField) {
        $file  = $request->file($fileField);
        $mime  = $file->getClientMimeType() ?: 'application/octet-stream';
        $bytes = file_get_contents($file->getRealPath());
        if ($mime === 'application/octet-stream') {
            $sig = substr($bytes, 0, 12);
            if (strncmp($sig, "\xFF\xD8\xFF", 3) === 0) $mime = 'image/jpeg';
            elseif (strncmp($sig, "\x89PNG", 4) === 0)    $mime = 'image/png';
            elseif (strncmp($sig, "RIFF", 4) === 0 && substr($sig, 8, 4) === "WEBP") $mime = 'image/webp';
            else $mime = 'image/jpeg';
        }
        $data64 = base64_encode($bytes);
    } else {
        $raw = (string) $request->input('image_base64', '');
        if (preg_match('#^data:(image/[\w+\-\.]+);base64,#i', $raw, $m)) {
            $mime   = strtolower($m[1]);
            $data64 = substr($raw, strpos($raw, ',') + 1);
        } else {
            $mime   = 'image/jpeg';
            $data64 = $raw;
        }
        $data64  = preg_replace('/\s+/', '', $data64);
        $decoded = base64_decode($data64, true);
        if ($decoded === false || strlen($decoded) < 64) {
            return response()->json(['error' => 'Invalid base64 image data in "image_base64".'], 422);
        }
        if (!in_array($mime, ['image/jpeg','image/jpg','image/png','image/webp'])) {
            $sig = substr($decoded, 0, 12);
            if (strncmp($sig, "\xFF\xD8\xFF", 3) === 0) $mime = 'image/jpeg';
            elseif (strncmp($sig, "\x89PNG", 4) === 0)    $mime = 'image/png';
            elseif (strncmp($sig, "RIFF", 4) === 0 && substr($sig, 8, 4) === "WEBP") $mime = 'image/webp';
            else $mime = 'image/jpeg';
        }
        $data64 = base64_encode($decoded);
    }

    $responseSchema = [
        'type'       => 'object',
        'properties' => [
            'product_no' => ['type' => 'string',  'description' => 'Product No'],
            'order_no'   => ['type' => 'string',  'description' => 'Order No'],
            'season'     => ['type' => 'string',  'description' => 'Season such as 3-2026'],
            'color_code' => ['type' => 'string',  'description' => 'ARTICLE NO value'],
            'size_code'  => ['type' => 'integer', 'description' => 'Assortment S size count (e.g., S=1)'],
            'quantity'   => ['type' => 'integer', 'description' => 'TOT PCS as integer'],
        ],
        'required' => ['product_no','order_no','season','color_code','size_code','quantity'],
    ];

    $prompt = <<<TXT
Read the H&M order sheet and extract:

- product_no  → value near "Product No:"
- order_no    → value near "Order No:"
- season      → value near "Season:" (e.g., "3-2026")
- color_code  → ARTICLE NO (the "Article No:" value)
- size_code   → **the numeric S size count from the Assortment row**.
                Example: if the table shows S=1, M=1, L=1, XL=0 then size_code must be 1.
                If the S cell is highlighted/selected/blue, still return the number in the S cell (usually 1).
                Return only the integer for S (do not return M/L/XL values).
                If the S value is missing or unreadable, return 0.
- quantity    → TOT PCS (total pieces) as an integer.

Formatting:
- Output ONLY valid JSON (no markdown, no extra text).
- Keys must be exactly: product_no, order_no, season, color_code, size_code, quantity.
- Strings trimmed; size_code and quantity must be integers.
- If any field is missing or unreadable, use "" for strings and 0 for integers.
TXT;

    $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";
    $payload = [
        'contents' => [[
            'role'  => 'user',
            'parts' => [
                ['text' => $prompt],
                ['inline_data' => ['mime_type' => $mime, 'data' => $data64]],
            ],
        ]],
        'generationConfig' => [
            'temperature'        => 0.1,
            'topP'               => 0.9,
            'topK'               => 40,
            'maxOutputTokens'    => 768,
            'response_mime_type' => 'application/json',
            'response_schema'    => $responseSchema,
        ],
    ];

    try {
        $resp = \Illuminate\Support\Facades\Http::timeout(60)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($endpoint, $payload);

        if (!$resp->ok()) {
            return response()->json(['error' => 'Gemini API error', 'details' => $resp->json()], $resp->status());
        }

        $jsonText = data_get($resp->json(), 'candidates.0.content.parts.0.text');
        if (!is_string($jsonText) || trim($jsonText) === '') {
            return response()->json(['error' => 'No structured JSON returned by model.'], 502);
        }

        $data = json_decode($jsonText, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON returned by model.', 'raw' => $jsonText], 502);
        }

        $out = [
            'product_no' => (string)($data['product_no'] ?? ''),
            'order_no'   => (string)($data['order_no']   ?? ''),
            'season'     => (string)($data['season']     ?? ''),
            'color_code' => (string)($data['color_code'] ?? ''),
            'size_code'  => (int)   ($data['size_code']  ?? 0), // S size count
            'quantity'   => (int)   ($data['quantity']   ?? 0), // Tot Pcs
        ];

        $product = Product::where('product_no',(string)($data['product_no']))->first();

        if (!$product) {
            return response()->json([
                'ok'      => false,
                'message' => "Product number '{$out['product_no']}' was not found. Please create the product first.",
                'data'    => $out,
            ], 422, ['Content-Type' => 'application/json; charset=utf-8']);
        }

        return response()->json($out, 200, ['Content-Type' => 'application/json; charset=utf-8']);
    } catch (\Throwable $e) {
        return response()->json(['error' => 'Failed to extract fields.', 'message' => $e->getMessage()], 500);
    }
}

}
