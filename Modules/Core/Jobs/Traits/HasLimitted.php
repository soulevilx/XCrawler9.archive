<?php

namespace Modules\Core\Jobs\Traits;

use Modules\Core\Jobs\Middlewares\LimitMiddleware;

trait HasLimitted
{
    public function middleware()
    {
        if (config('app.env') === 'testing') {
            return [];
        }

        return [
            new LimitMiddleware([
                self::class,
                config('app.env'),
            ])
        ];
    }

    public function retryUntil()
    {
        return now()->addMinutes(60);
    }
}
