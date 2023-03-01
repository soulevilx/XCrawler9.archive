<?php

namespace Modules\Core\Jobs\Middlewares;

use Illuminate\Support\Facades\Redis;

class LimitMiddleware
{
    public function __construct(
        private array $keys,
        private int $block = 1,
        private int $allow = 1,
        private int $every = 1,
    ) {
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
            ->block($this->block)
            ->allow($this->allow)
            ->every($this->every)
            ->then(function () use ($job, $next) {
                $next($job);
            }, function () use ($job) {
                // Could not obtain lock...

                $job->release(10);
            });
    }
}
