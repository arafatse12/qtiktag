<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\GtinMapping;

class GtinMappingSeeder extends Seeder
{
    public function run(): void
    {
        $product = Product::where('product_no', 'HM0004588C')->firstOrFail();
        $appUrl      = rtrim(config('app.url'), '/');
        GtinMapping::updateOrCreate(
            ['gtin16' => '07300235375212HM000006'],
            [
                'product_id' => $product->id,
                'product_no' => $product->product_no,
                'order_no'   => 'ORD000123',
                'season'     => 'SS25',
                'color_code' => 'C001',
                'size_code'  => 'S12',
                'gtin14'     => '07300235375212',
                'quantity'   => 1,
                'qr_path'    => "{$appUrl}/qr/07300235375212HM000006.png",
                'barcode_path'=> '/barcodes/07300235375212.png',
            ]
        );
    }
}
