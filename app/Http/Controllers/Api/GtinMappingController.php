<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GtinMapping;
use Illuminate\Http\Request;

class GtinMappingController extends Controller
{
    public function show(string $gtin)
    {
        $digits = preg_replace('/\D+/', '', (string) $gtin);
        if (!$digits) {
            return response()->json(['message' => 'No GTIN provided'], 404);
        }
        $gtin14 = strlen($digits) >= 14 ? substr($digits, 0, 14) : null;

 

        $row = GtinMapping::with('product')
        ->where('gtin16',$gtin)
            // ->where(function ($q) use ($digits, $gtin14) {
            //     $q->where('gtin16', $digits);
            //     if ($gtin14) $q->orWhere('gtin14', $gtin14);
            // })
            ->first();

        if (!$row || !$row->product) {
            return response()->json(['message' => "GTIN {$digits} not found"], 404);
        }
       
        $p = $row->product;

        $payload = [
            'gtin_16' => $row->gtin16,
            'product' => [
                'name'        => $p->name,
                'brand'       => $p->brand,
                'model'       => $p->model,
                'description' => $p->description,
                'barcode'     => $p->barcode,
                'sku_code'    => $p->sku_code,
                'gtin14'      => $p->gtin14,
                'image'      => $p->image,
            ],
            'manufacturing' => [
                'factory'                     => $p->factory,
                'address'                     => $p->address,
                'origin'                      => $p->origin,
                'batch'                       => $p->batch,
                'manufactured_on'             => optional($p->manufactured_on)->format('Y-m-d'),
                'packaged_on'                 => optional($p->packaged_on)->format('Y-m-d'),
                'production_process'          => $p->production_process,
                'environmental_considerations'=> $p->environmental_considerations,
                'worker_wellbeing'            => $p->worker_wellbeing,
                'gear_image_url'              => $p->gear_image_url,
            ],
            'materials' => $p->materials_json,
            'custody'   => $p->custody_json,
            'usage'     => $p->usage_json,
            'certs'     => $p->certs_json ?? [],
            'sustain'   => $p->sustain_json,
            'impact'    => $p->impact_json,
            'image'    => $row->image,
            'qr_image'    => $row->qr_path,
            'info_accuracy' => [
                'updated_at' => $p->info_updated_at_pretty ?: optional($p->updated_at)->format('d/m/Y - H:i:s'),
                'publisher'  => $p->publisher ?: $p->brand,
            ],
            'images' => [
                'logo'   => $p->image_logo,
                'hero'   => $p->image_hero,
                'footer' => $p->image_footer,
            ],
            'shop_url' => $p->shop_url,
        ];

        return response()->json($payload)->header('Cache-Control', 'public, max-age=60');
    
    }
}
