<?php

namespace Modules\Jav\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Traits\HasUuid;

class Genre extends Model
{
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'uuid',
        'name',
    ];

    protected static function newFactory()
    {
        return \Modules\Jav\Database\factories\GenerFactory::new();
    }
}
