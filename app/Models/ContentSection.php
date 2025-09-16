<?php

// app/Models/ContentSection.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentSection extends Model
{
    protected $fillable = ['name','slug','content','published'];

    protected $casts = [
        'content' => 'array',
        'published' => 'boolean',
    ];
}

