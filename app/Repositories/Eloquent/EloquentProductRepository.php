<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepository;

final class EloquentProductRepository implements ProductRepository
{
    public function findByProductNo(string $productNo): ?Product
    {
        return Product::where('product_no', $productNo)->first();
    }
}
