<?php

namespace App\Repositories\Contracts;

use App\Models\Product;

interface ProductRepository
{
    public function findByProductNo(string $productNo): ?Product;
}
