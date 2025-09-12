<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\BarcodeService;

class BarcodeController extends Controller
{
    public function __construct(private BarcodeService $service) {}

    public function index()  { return view('barcode.index'); }
    public function create() { return view('barcode.create'); }

    public function store(Request $request)
    {
        return response()->json($request->all());
    }

    public function batch(Request $req)
    {
        $validated = $req->validate([
            'order_no'   => ['required','string','max:64'],
            'product_no' => ['required','string','max:32', Rule::exists('products','product_no')],
            'season'     => ['required','string','max:32'],
            'quantity'   => ['required','integer','min:1'],
            'color_code' => ['required','string','max:32'],
            'size_code'  => ['required','string','max:16'],
        ], [
            'product_no.exists' => 'Product number does not exist. Please create the product first.',
        ]);

        try {
            $result = $this->service->generateBatch($validated);
            return response()->json($result);
        } catch (\Throwable $e) {
            return response()->json([
                'ok'      => false,
                'message' => $e->getMessage(),
            ], str_contains($e->getMessage(), 'Product number') ? 422 : 500);
        }
    }

    public function extract(Request $request)
    {
        // Accept any of: 'image', 'file', 'photo', or 'image_base64'
        $fileField = collect(['image','file','photo'])->first(fn ($f) => $request->hasFile($f));

        $request->validate([
            'image'        => 'required_without_all:file,photo,image_base64|file|max:20480',
            'file'         => 'required_without_all:image,photo,image_base64|file|max:20480',
            'photo'        => 'required_without_all:image,file,image_base64|file|max:20480',
            'image_base64' => 'required_without_all:image,file,photo|string',
        ], [
            'image.required_without_all'        => 'Provide an image in "image", "file", or "photo", or base64 in "image_base64".',
            'file.required_without_all'         => 'Provide an image in "image", "file", or "photo", or base64 in "image_base64".',
            'photo.required_without_all'        => 'Provide an image in "image", "file", or "photo", or base64 in "image_base64".',
            'image_base64.required_without_all' => 'Provide an image in "image", "file", or "photo", or base64 in "image_base64".',
        ]);

        try {
            $input = $fileField
                ? ['file' => $request->file($fileField)]
                : ['base64' => (string) $request->input('image_base64','')];

            $out = $this->service->extractFromImage($input);

            // enforce product existence (as in your original)
            if (!Product::where('product_no', $out['product_no'])->exists()) {
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
