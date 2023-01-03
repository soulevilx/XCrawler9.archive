<?php

namespace Modules\Core\Tests\Unit\Services;

use Modules\Core\Services\SettingService;
use Tests\TestCase;

class SettingServiceTest extends TestCase
{
    public function testRemember()
    {
        $service = app(SettingService::class);

        $now = time();

        $this->assertEquals($now, $service->remember('test', 'time', fn () => $now));
        $this->assertDatabaseHas('settings', [
            'group' => 'test',
            'key' => 'time',
            'value' => $now,
        ], 'mongodb');
    }

    public function testForget()
    {
        $service = app(SettingService::class);

        $now = time();

        $service->remember('test', 'time', fn () => $now);
        $service->forget('test', 'time');

        $this->assertDatabaseMissing('settings', [
            'group' => 'test',
            'key' => 'time',
            'value' => $now,
        ], 'mongodb');
    }
}
