<?php

namespace App\Repositories\Eloquent;

use App\Models\GtinMapping;
use App\Repositories\Contracts\GtinMappingRepository;

final class EloquentGtinMappingRepository implements GtinMappingRepository
{
    public function create(array $data): GtinMapping
    {
        return GtinMapping::create($data);
    }
}
