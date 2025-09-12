<?php

// app/Models/GtinMapping.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GtinMapping extends Model
{
    protected $fillable = [
        'product_id','order_no','product_no','season','color_code','size_code',
        'gtin14','gtin16','qr_path','barcode_path','quantity'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
