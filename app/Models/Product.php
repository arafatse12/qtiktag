<?php

// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_no','name','description','season',
        'customer_group','construction_type',
        'gtin14','qr_base_url','qr_url',
        'supplier_code','supplier_name',
    ];

    public function mappings()
    {
        return $this->hasMany(GtinMapping::class);
    }

    protected static function booted()
    {
        static::saving(function ($m) {
            if ($m->gtin14 && !$m->qr_url) {
                $base = $m->qr_base_url ?: 'https://qr.hmgroup.com/01';
                $m->qr_url = rtrim($base,'/').'/'.$m->gtin14;
            }
        });
    }
}

