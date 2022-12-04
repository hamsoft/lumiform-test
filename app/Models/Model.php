<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * @property string $uuid
 * @method $this|static create(mixed $validated)
 * @method null|static find(mixed $uuid)
 */
abstract class Model extends EloquentModel
{
    use HasUuids;

    public const UUID = 'uuid';
    protected $primaryKey = 'uuid';

}
