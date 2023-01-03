<?php

namespace Modules\Jav\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Onejav extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'cover',
        'dvd_id',
        'size',
        'date',
        'genres',
        'description',
        'performers',
        'torrent',
    ];

    protected $casts = [
        'url' => 'string',
        'cover' => 'string',
        'dvd_id' => 'float',
        'date' => 'date',
        'genres' => 'array',
        'performers' => 'array',
        'description' => 'string',
        'torrent' => 'string',
    ];

    protected $table = 'onejav';

    protected static function newFactory()
    {
        return \Modules\Jav\Database\factories\OnejavFactory::new();
    }
}
