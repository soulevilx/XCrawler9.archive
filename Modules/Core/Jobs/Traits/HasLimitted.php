<?php

namespace Modules\Core\Jobs\Traits;

use Modules\Core\Jobs\Middlewares\LimitMiddleware;

trait HasLimitted
{
    public function middleware()
    {
        if ('testing' === config('app.env')) {
            return [];
        }

        return [new LimitMiddleware(self::class)];
    }

    public function retryUntil()
    {
        return now()->addMinutes(60);
    }
}
