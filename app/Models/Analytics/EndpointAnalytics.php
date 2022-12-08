<?php

namespace App\Models\Analytics;


use App\Models\Model;

/**
 * @property string $path
 * @property string $method
 */
class EndpointAnalytics extends Model
{
    public const TABLE = 'endpoint_analytics';

    const PATH = 'path';
    const METHOD = 'method';
    const NAME = 'name';
    const USER_UUID = 'user_uuid';

    protected $fillable = [
        self::PATH,
        self::METHOD,
        self::NAME,
        self::USER_UUID,
    ];
}
