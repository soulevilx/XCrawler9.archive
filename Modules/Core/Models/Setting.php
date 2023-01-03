<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends \Jenssegers\Mongodb\Eloquent\Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $fillable = [
        'group',
        'key',
        'value',
    ];

    public function scopeGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    public function scopeKey($query, $key)
    {
        return $query->where('key', $key);
    }
}
