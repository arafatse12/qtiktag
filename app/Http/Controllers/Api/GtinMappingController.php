<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GtinMapping;
use Illuminate\Http\Request;

class GtinMappingController extends Controller
{
    public function show(string $gtin)
    {
        $row = GtinMapping::where('gtin16', $gtin)->first();

        if (!$row) {
            return response()->json(['message' => 'Not found'], 404);
        }
//         $row = $row = [
//     "gtin_16" => "1315741002132401",
//     "product" => [
//         "name" => "CONNERY Baby Woven Trousers",
//         "brand" => "H&M Group",
//         "model" => "HM0004588CX000006",
//         "description" => "100% Organic Cotton Flat Woven Trousers for Babies Inspired by Work Wear",
//         "barcode" => "88768333888282",
//         "sku_code" => "0004588C-CONNERY"
//     ],
//     "manufacturing" => [
//         "factory" => "Manufactured for H & M Hennes & Mauritz GBC AB by Russell Garments SIM Fabrics Ltd.",
//         "address" => "315, Road - 4,\nBaridhara DOHS\nDhaka\nBangladesh -\n1206",
//         "origin" => "Made in Bangladesh",
//         "batch" => "4588-3-2025",
//         "manufactured_on" => "2025-04-08",
//         "packaged_on" => "2025-04-09"
//     ],
//     "info_accuracy" => [
//         "updated_at" => "08/04/2025 - 04:07:45",
//         "publisher" => "H&M Group"
//     ],
//     "images" => [
//         "logo" => "/hm-logo.svg",
//         "hero" => "/images/trouser-hero.jpg",
//         "footer" => "/images/brand-footer.jpg"
//     ],
//     "shop_url" => "https://hm.com/product/88768333888282"
// ];

        return response()->json($row);
    }
}
