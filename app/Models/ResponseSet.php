<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResponseSet extends Model
{
    use HasFactory;

    const TABLE = 'response_sets';

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }
}
