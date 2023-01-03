<?php

namespace Modules\Jav\Listeners;

use Modules\Jav\Events\OnejavItemParsed;
use Modules\Jav\Repositories\OnejavRepository;

class OnejavCrawlingSubscriber
{

    public function onOnejavItemParsed(OnejavItemParsed $event)
    {
        $repository = app(OnejavRepository::class);
        $repository->create($event->item->getArrayCopy());
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe($events)
    {
        return [
            OnejavItemParsed::class => 'onOnejavItemParsed',
        ];
    }
}
