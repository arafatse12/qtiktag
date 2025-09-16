<?php

namespace App\Services;

use App\Models\GtinMapping;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Picqer\Barcode\BarcodeGeneratorPNG;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BarcodeService
{
    /**
     * Generate a batch of QR codes + GTIN mappings.
     * Expects: order_no, product_no, season, quantity, color_code, size_code
     */
    public function generateBatch(array $payload): array
    {
        $product = Product::where('product_no', $payload['product_no'])->first();
        if (!$product) {
            throw new \RuntimeException("Product number was not found. Please create the product first.");
        }

        $qty = max(1, (int) $payload['quantity']);

        // Build GTIN-14 from parts
        $digits13 = preg_replace(
            '/\D/',
            '',
            $payload['product_no'].$payload['color_code'].$payload['size_code'].$payload['season']
        );
        $digits13 = str_pad(substr($digits13, 0, 13), 13, '0');
        $gtin14   = $digits13 . $this->checkDigit($digits13);

        Storage::disk('public')->makeDirectory('qrcodes');
        // Storage::disk('public')->makeDirectory('barcodes');
        $barcodeGen  = new BarcodeGeneratorPNG(); // (kept if you later want barcodes)
        $suffixWidth = ($qty >= 100) ? 0 : 2;
        $appUrl      = rtrim(config('app.url'), '/');

        return DB::transaction(function () use ($qty, $suffixWidth, $gtin14, $payload, $product, $barcodeGen, $appUrl) {
            $items = [];

            for ($i = 1; $i <= $qty; $i++) {
                $suffix = ($suffixWidth > 0)
                    ? str_pad((string)$i, $suffixWidth, '0', STR_PAD_LEFT)
                    : (string)$i;

                $gtin16  = $gtin14 . $suffix;
                $qrRel   = "qrcodes/qr_{$gtin16}.png";
                $target  = "{$appUrl}/{$gtin16}";

                $qrPng = QrCode::format('png')->size(256)->margin(1)->generate($target);
                Storage::disk('public')->put($qrRel, $qrPng);

                // $bcRel = "barcodes/barcode_{$gtin16}.png";
                // Storage::disk('public')->put($bcRel, $barcodeGen->getBarcode($gtin16, $barcodeGen::TYPE_CODE_128));

                GtinMapping::create([
                    'product_id'   => $product->id,
                    'order_no'     => $payload['order_no'],
                    'product_no'   => $payload['product_no'],
                    'season'       => $payload['season'],
                    'color_code'   => $payload['color_code'],
                    'size_code'    => $payload['size_code'],
                    'gtin14'       => $gtin14,
                    'gtin16'       => $gtin16,
                    'quantity'     => $qty,
                    'qr_path'      => "{$appUrl}/storage/{$qrRel}",
                    // 'barcode_path' => "storage/{$bcRel}",
                ]);

                $items[] = [
                    'index'   => $i,
                    'gtin14'  => $gtin14,
                    'gtin16'  => $gtin16,
                    'payload' => $target,
                    'qr'      => asset("storage/{$qrRel}"),
                    // 'barcode' => asset("storage/{$bcRel}"),
                ];
            }

            return [
                'ok'     => true,
                'count'  => $qty,
                'gtin14' => $gtin14,
                'items'  => $items,
            ];
        });
    }

public function extractFromImage(array $input): array
{
    $apiKey = config('services.gemini.key') ?: env('GEMINI_API_KEY');
    if (!$apiKey) {
        throw new \RuntimeException('GEMINI_API_KEY not configured');
    }

    $model = config('services.gemini.model', env('GEMINI_MODEL', 'gemini-2.5-flash-lite'));

    // Build binary
    [$mime, $data64] = $this->normalizeBinary($input);

    // Response schema (guidance for model)
    $responseSchema = [
        'type'       => 'object',
        'properties' => [
            'product_no' => ['type' => 'string',  'description' => 'Product No'],
            'order_no'   => ['type' => 'string',  'description' => 'Order No'],
            'season'     => ['type' => 'string',  'description' => 'Season such as 3-2026'],
            'color_code' => ['type' => 'string',  'description' => 'ARTICLE NO value'],
            'size_code'  => ['type' => 'integer', 'description' => 'S size count'],
            'quantity'   => ['type' => 'integer', 'description' => 'TOT PCS'],
        ],
        'required' => ['product_no','order_no','season','color_code','size_code','quantity'],
    ];

    $prompt = <<<TXT
Read the H&M order sheet and extract:
- product_no  → near "Product No:"
- order_no    → near "Order No:"
- season      → near "Season:" (e.g., "3-2026")
- color_code  → Article No
- size_code   → the numeric S size count from the Assortment row (integer only; if missing return 0)
- quantity    → TOT PCS (integer)

Output ONLY JSON with keys: product_no, order_no, season, color_code, size_code, quantity.
Missing strings = "", missing integers = 0.
TXT;

    $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

    $body = [
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

    $resp = Http::timeout(60)
        ->withHeaders(['Content-Type' => 'application/json'])
        ->post($endpoint, $body);

    if (!$resp->ok()) {
        throw new \RuntimeException('Gemini API error: '.json_encode($resp->json()));
    }

    // Try to find the first non-empty text in any candidate/part
    $jsonText = $this->pickFirstJsonText($resp->json());
    if (!is_string($jsonText) || trim($jsonText) === '') {
        throw new \RuntimeException('No structured JSON returned by model.');
    }

    // Decode with sanitizer (handles code fences, BOM, stray commas/quotes, etc.)
    $data = $this->decodeModelJson($jsonText);

    return [
        'product_no' => (string)($data['product_no'] ?? ''),
        'order_no'   => (string)($data['order_no']   ?? ''),
        'season'     => (string)($data['season']     ?? ''),
        'color_code' => (string)($data['color_code'] ?? ''),
        'size_code'  => (int)   ($data['size_code']  ?? 0),
        'quantity'   => (int)   ($data['quantity']   ?? 0),
    ];
}

/** ----------------- helpers ----------------- */

/**
 * Find the first non-empty text field from the Gemini response.
 */
private function pickFirstJsonText(array $response): ?string
{
    $candidates = $response['candidates'] ?? [];
    foreach ($candidates as $cand) {
        // Newer APIs: candidates[n].content.parts[*].text
        if (isset($cand['content']['parts']) && is_array($cand['content']['parts'])) {
            foreach ($cand['content']['parts'] as $part) {
                if (isset($part['text']) && is_string($part['text']) && trim($part['text']) !== '') {
                    return $part['text'];
                }
            }
        }
        // Fallbacks (just in case)
        if (isset($cand['content']['text']) && is_string($cand['content']['text']) && trim($cand['content']['text']) !== '') {
            return $cand['content']['text'];
        }
    }
    // Legacy shortcut you used:
    $text = data_get($response, 'candidates.0.content.parts.0.text');
    return (is_string($text) && trim($text) !== '') ? $text : null;
}

/**
 * Sanitize and decode possibly-fenced/dirty JSON from the model.
 */
private function decodeModelJson(string $jsonText): array
{
    // 1) Trim & strip UTF-8 BOM and common whitespace
    $s = ltrim($jsonText, "\xEF\xBB\xBF \t\n\r\0\x0B");

    // 2) If it's inside ```json ... ``` or ``` ... ```, extract fenced block
    if (preg_match('/```(?:json)?\s*(.*?)\s*```/is', $s, $m)) {
        $s = $m[1];
    }

    // 3) If there’s surrounding prose, grab the largest {...} slice
    $start = strpos($s, '{');
    $end   = strrpos($s, '}');
    if ($start !== false && $end !== false && $end > $start) {
        $s = substr($s, $start, $end - $start + 1);
    }

    // 4) Normalize curly quotes to straight quotes
    $s = strtr($s, [
        "\xE2\x80\x9C" => '"', // “
        "\xE2\x80\x9D" => '"', // ”
        "\xE2\x80\x98" => "'", // ‘
        "\xE2\x80\x99" => "'", // ’
    ]);

    // 5) Remove trailing commas before } or ]
    $s = preg_replace('/,\s*([}\]])/m', '$1', $s);

    // 6) Remove non-printable control chars (keep common whitespace)
    $s = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $s);

    // 7) Try decoding
    $data = json_decode($s, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
        return $data;
    }

    // 8) If still not valid, convert single-quoted strings to double-quoted
    $sAlt = preg_replace_callback(
        '/\'(?:\\\\.|[^\'\\\\])*\'/s',
        function ($m) {
            $inner = substr($m[0], 1, -1);
            $inner = str_replace(['\\\'', '"'], ["'", '\"'], $inner);
            return '"' . $inner . '"';
        },
        $s
    );
    $data = json_decode($sAlt, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
        return $data;
    }

    throw new \RuntimeException('Invalid JSON returned by model: ' . json_last_error_msg());
}

    /** ---------- helpers ---------- */

    private function checkDigit(string $digits13): int
    {
        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $n = (int) $digits13[12 - $i];
            $sum += ($i % 2 === 0) ? $n * 3 : $n;
        }
        return (10 - ($sum % 10)) % 10;
    }

    /** @return array{0:string,1:string} [$mime,$base64] */
    private function normalizeBinary(array $input): array
    {
        if (isset($input['file']) && $input['file']) {
            $file  = $input['file'];
            $mime  = $file->getClientMimeType() ?: 'application/octet-stream';
            $bytes = file_get_contents($file->getRealPath());

            // try to detect common image types if octet-stream
            if ($mime === 'application/octet-stream') {
                $sig = substr($bytes, 0, 12);
                if (strncmp($sig, "\xFF\xD8\xFF", 3) === 0) $mime = 'image/jpeg';
                elseif (strncmp($sig, "\x89PNG", 4) === 0)    $mime = 'image/png';
                elseif (strncmp($sig, "RIFF", 4) === 0 && substr($sig, 8, 4) === "WEBP") $mime = 'image/webp';
            }

            // allow pdfs to pass through (Gemini can read some pdfs); otherwise default to jpeg
            if (!in_array($mime, ['image/jpeg','image/jpg','image/png','image/webp','application/pdf'])) {
                $mime = 'image/jpeg';
            }

            return [$mime, base64_encode($bytes)];
        }

        $raw = (string)($input['base64'] ?? $input['raw'] ?? '');
        if ($raw === '') throw new \InvalidArgumentException('No image provided.');

        if (preg_match('#^data:(image/[\w+\-\.]+|application/pdf);base64,#i', $raw, $m)) {
            $mime   = strtolower($m[1]);
            $data64 = substr($raw, strpos($raw, ',') + 1);
        } else {
            $mime   = 'image/jpeg';
            $data64 = $raw;
        }

        $data64  = preg_replace('/\s+/', '', $data64);
        $decoded = base64_decode($data64, true);
        if ($decoded === false || strlen($decoded) < 64) {
            throw new \InvalidArgumentException('Invalid base64 image data.');
        }

        // try to detect image if mime is odd
        if (!in_array($mime, ['image/jpeg','image/jpg','image/png','image/webp','application/pdf'])) {
            $sig = substr($decoded, 0, 12);
            if (strncmp($sig, "\xFF\xD8\xFF", 3) === 0) $mime = 'image/jpeg';
            elseif (strncmp($sig, "\x89PNG", 4) === 0)    $mime = 'image/png';
            elseif (strncmp($sig, "RIFF", 4) === 0 && substr($sig, 8, 4) === "WEBP") $mime = 'image/webp';
            else $mime = 'image/jpeg';
        }

        return [$mime, base64_encode($decoded)];
    }
}
