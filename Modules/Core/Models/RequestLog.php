<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequestLog extends \Jenssegers\Mongodb\Eloquent\Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $fillable = [
        'url',
        'payload',
        'code',
        'response'
    ];

    protected $casts = [
        'url' => 'string',
        'payload' => 'array',
        'code' => 'integer',
        'response' => 'string'
    ];
}
