<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'product_no' => $this->faker->unique()->bothify('HM########'),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(10),
            'season' => $this->faker->randomElement(['SS25', 'AW25']),
            'customer_group' => $this->faker->randomElement(['Baby', 'Kids', 'Men']),
            'construction_type' => $this->faker->randomElement(['Flat Woven', 'Knit']),
            'gtin14' => $this->faker->numerify('##############'),

            // QR fields
            'qr_base_url' => 'https://qr.hmgroup.com/01',
            'qr_url' => null,

            // Supplier info
            'supplier_code' => $this->faker->bothify('SUP###'),
            'supplier_name' => $this->faker->company(),

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
