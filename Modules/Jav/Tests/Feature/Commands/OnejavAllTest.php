<?php

namespace Modules\Jav\Tests\Feature\Commands;

use Illuminate\Support\Facades\Queue;
use Modules\Jav\Jobs\OnejavAll;
use Tests\TestCase;

class OnejavAllTest extends TestCase
{
    public function testHandle()
    {
        Queue::fake(OnejavAll::class);
        $this->artisan('jav:onejav-all')->assertExitCode(0);
        Queue::assertPushed(OnejavAll::class);
    }
}
