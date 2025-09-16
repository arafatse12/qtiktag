<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\GtinMapping;
use App\Models\Product;

/**
 * @extends Factory<GtinMapping>
 */
class GtinMappingFactory extends Factory
{
    protected $model = GtinMapping::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'product_no' => $this->faker->bothify('HM########'),
            'order_no' => $this->faker->bothify('ORD#####'),
            'season' => $this->faker->randomElement(['SS25', 'AW25']),
            'color_code' => $this->faker->safeColorName(),
            'size_code' => $this->faker->randomElement(['S', 'M', 'L']),
            'gtin14' => $this->faker->numerify('##############'),
            'gtin16' => $this->faker->unique()->numerify('################'),
            'quantity' => $this->faker->numberBetween(1, 100),
            'qr_path' => '/qrs/' . $this->faker->uuid . '.png',
            'barcode_path' => '/barcodes/' . $this->faker->uuid . '.png',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
