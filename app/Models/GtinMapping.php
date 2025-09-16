<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GtinMapping extends Model
{
    use HasFactory;

    protected $guarded = []; // simplify seeding

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Normalize GTINs to digits only on set
    public function setGtin14Attribute($value)
    {
        $this->attributes['gtin14'] = preg_replace('/\D+/', '', (string) $value);
    }

    public function setGtin16Attribute($value)
    {
        $this->attributes['gtin16'] = preg_replace('/\D+/', '', (string) $value);
    }
}
