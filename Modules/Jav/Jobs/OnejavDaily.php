<?php

namespace Modules\Jav\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Core\Jobs\Traits\HasLimitted;
use Modules\Jav\Services\OnejavService;

class OnejavDaily implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use HasLimitted;

    protected int $block = 1;
    protected int $allow = 2;
    protected int $every = 1;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OnejavService $service)
    {
        $service->daily();
    }
}
