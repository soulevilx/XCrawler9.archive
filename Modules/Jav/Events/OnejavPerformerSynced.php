<?php

namespace Modules\Jav\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Jav\Models\Onejav;

class OnejavPerformerSynced
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public Onejav $model)
    {
        //
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
