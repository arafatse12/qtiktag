<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\GtinMapping;

class DynamicSampleSeeder extends Seeder
{
    public function run(): void
    {
        // Create 5 demo products
        Product::factory()
            ->count(5)
            ->create()
            ->each(function (Product $product) {
                // Give each product between 1 and 3 GTIN mappings
                GtinMapping::factory()
                    ->count(rand(1, 3))
                    ->create([
                        'product_id' => $product->id,
                        'product_no' => $product->product_no,
                        'season'     => $product->season,
                    ]);
            });
    }
}
