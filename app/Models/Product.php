<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = []; // or define $fillable

    protected $casts = [
        'identity_json'      => 'array',
        'manufacturing_json' => 'array',
        'materials_json'     => 'array',
        'custody_json'       => 'array',
        'usage_json'         => 'array',
        'certs_json'         => 'array',
        'sustain_json'       => 'array',
        'impact_json'        => 'array',
        'manufactured_on'    => 'date',
        'packaged_on'        => 'date',
    ];

    public function gtinMappings()
    {
        return $this->hasMany(GtinMapping::class);
    }
}
