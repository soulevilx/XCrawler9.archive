<?php

namespace Modules\Jav\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'dvd_id' => 'string',
        'size' => 'float',
        'date' => 'date:Y-m-d',
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

    public function exPerformers(): BelongsToMany
    {
        return $this->belongsToMany(
            Performers::class,
            'performer_onejav',
            'onejav_id',
            'performer_id'
        )->withTimestamps();
    }

    public function exGenres(): BelongsToMany
    {
        return $this->belongsToMany(
            Genre::class,
            'genre_onejav',
            'onejav_id',
            'genre_id'
        )->withTimestamps();
    }
}
