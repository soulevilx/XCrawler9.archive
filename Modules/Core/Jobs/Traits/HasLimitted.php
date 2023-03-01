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
            new LimitMiddleware(
                [
                    self::class,
                    config('app.env'),
                ],
                $this->block ?? 1,
                $this->allow ?? 1,
                $this->every ?? 1,
            )
        ];
    }

    public function retryUntil()
    {
        return now()->addMinutes(60);
    }
}
