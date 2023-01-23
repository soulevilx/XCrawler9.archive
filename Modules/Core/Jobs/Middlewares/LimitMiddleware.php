<?php

namespace Modules\Core\Jobs\Middlewares;

use Illuminate\Support\Facades\Redis;

class LimitMiddleware
{
    public function __construct(private array $keys)
    {
    }

    /**
     * Process the queued job.
     *
     * @param  mixed  $job
     * @param  callable  $next
     * @return mixed
     */
    public function handle($job, $next)
    {
        Redis::throttle(
            md5(
                serialize(
                    [
                        config('app.key'),
                        $this->keys,
                        config('app.server_ip'),
                    ]
                )
            )
        )
            ->block(1)
            ->allow(1)
            ->every(1)
            ->then(function () use ($job, $next) {
                $next($job);
            }, function () use ($job) {
                // Could not obtain lock...

                $job->release(10);
            });
    }
}
