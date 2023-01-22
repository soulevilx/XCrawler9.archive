<?php

namespace Modules\Jav\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Jav\Models\Onejav;

class OnejavItemParsed
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public Onejav $model)
    {
    }
}
