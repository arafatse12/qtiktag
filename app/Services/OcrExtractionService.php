<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

/**
 * Extracts fields from an image using Gemini.
 */
final class OcrExtractionService
{
    /**
     * @return array{product_no:string, order_no:string, season:string, color_code:string, size_code:int, quantity:int}
     */
    public function extract(string $base64Image, string $mime): array
    {
        $apiKey  = (string) config('services.gemini.key');
        $model   = (string) (config('services.gemini.model') ?? 'gemini-1.5-flash');
        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        $responseSchema = [
            'type'       => 'object',
            'properties' => [
                'product_no' => ['type' => 'string'],
                'order_no'   => ['type' => 'string'],
                'season'     => ['type' => 'string'],
                'color_code' => ['type' => 'string'],
                'size_code'  => ['type' => 'integer'],
                'quantity'   => ['type' => 'integer'],
            ],
            'required' => ['product_no','order_no','season','color_code','size_code','quantity'],
        ];

        $prompt = <<<TXT
Read the H&M order sheet and extract:
- product_no, order_no, season, color_code, size_code (S count), quantity (TOT PCS).
Output ONLY JSON. Missing → "" or 0.
TXT;

        $payload = [
            'contents' => [[
                'role'  => 'user',
                'parts' => [
                    ['text' => $prompt],
                    ['inline_data' => ['mime_type' => $mime, 'data' => $base64Image]],
                ],
            ]],
            'generationConfig' => [
                'temperature'         => 0.1,
                'topP'                => 0.8,
                'topK'                => 40,
                'maxOutputTokens'     => 768,
                'response_mime_type'  => 'application/json',
                'response_schema'     => $responseSchema,
            ],
        ];

        $resp = Http::timeout(60)->withHeaders(['Content-Type' => 'application/json'])->post($endpoint, $payload);
        if (!$resp->ok()) {
            abort(response()->json(['error' => 'Gemini API error', 'details' => $resp->json()], $resp->status()));
        }

        $jsonText = data_get($resp->json(), 'candidates.0.content.parts.0.text', '');
        $data = json_decode((string) $jsonText, true) ?: [];

        return [
            'product_no' => (string)($data['product_no'] ?? ''),
            'order_no'   => (string)($data['order_no'] ?? ''),
            'season'     => (string)($data['season'] ?? ''),
            'color_code' => (string)($data['color_code'] ?? ''),
            'size_code'  => (int)($data['size_code'] ?? 0),
            'quantity'   => (int)($data['quantity'] ?? 0),
        ];
    }
}
