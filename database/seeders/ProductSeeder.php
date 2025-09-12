<?php

// database/seeders/ProductSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder {
    public function run(): void {
        DB::table('products')->insert([
            [
                'product_no' => '1315741',
                'name'       => 'Diego Checked SSL',
                'description'=> 'Shirt - Garment upper body',
                'season'     => '3-2026',
                'customer_group' => 'Men',
                'construction_type' => 'Woven',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_no' => '4709965',
                'name'       => 'Basic Tee',
                'description'=> 'Knitted cotton tee',
                'season'     => '2-2026',
                'customer_group' => 'Men',
                'construction_type' => 'Knitted',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_no' => '8880011',
                'name'       => 'Slim Denim',
                'description'=> 'Jeans',
                'season'     => '1-2026',
                'customer_group' => 'Men',
                'construction_type' => 'Woven',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
