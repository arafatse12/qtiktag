<?php

namespace App\Repositories\Contracts;

use App\Models\GtinMapping;

interface GtinMappingRepository
{
    /**
     * @param  array{
     *   product_id:int, order_no:string, product_no:string, season:string,
     *   color_code:string, size_code:string|int, gtin14:string, gtin16:string,
     *   quantity:int, qr_path:string
     * } $data
     */
    public function create(array $data): GtinMapping;
}
