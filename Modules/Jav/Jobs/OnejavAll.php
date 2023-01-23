<?php

namespace Modules\Jav\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Core\Jobs\Traits\HasLimitted;
use Modules\Jav\Services\OnejavService;

class OnejavAll implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use HasLimitted;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OnejavService $service)
    {
        $service->all();
    }
}
