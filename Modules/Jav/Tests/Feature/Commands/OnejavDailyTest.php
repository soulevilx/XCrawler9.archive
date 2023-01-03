<?php

namespace Modules\Jav\Tests\Feature\Commands;

use Illuminate\Support\Facades\Queue;
use Modules\Jav\Jobs\OnejavDaily;
use Tests\TestCase;

class OnejavDailyTest extends TestCase
{
    public function testHandle()
    {
        Queue::fake(OnejavDaily::class);
        $this->artisan('jav:onejav-daily')->assertExitCode(0);
        Queue::assertPushed(OnejavDaily::class);
    }
}
