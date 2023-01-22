<?php

namespace Modules\Jav\Tests\Unit\Services;

use JOOservices\XClient\Response\Response;
use JOOservices\XClient\XClient;
use Mockery;
use Mockery\MockInterface;
use Modules\Core\Models\Setting;
use Modules\Core\Services\SettingService;
use Modules\Jav\Crawlers\OnejavCrawler;
use Modules\Jav\Services\OnejavService;
use Tests\TestCase;

class OnejavServiceTest extends TestCase
{
    public function testAll()
    {
        $this->instance(
            XClient::class,
            Mockery::mock(XClient::class, function (MockInterface $mock) {
                $mock->shouldReceive('init');
                $response = new Response();
                $response->reset(
                    200,
                    [],
                    file_get_contents(__DIR__.'/../../Fixtures/Crawlers/Onejav/new_10703.html'),
                );
                $mock->shouldReceive('setHeaders');
                $mock->shouldReceive('get')->andReturn($response);
            })
        );

        $service = app(OnejavService::class);
        $setting = app(SettingService::class);
        Setting::where('group', 'onejav')->delete();
        $setting->remember('onejav', 'pages', fn() => 10703);
        $setting->remember('onejav', 'current_page', fn() => 10703);

        $items = $service->all();

        $this->assertEquals(1, $setting->get('onejav', 'current_page'));
        $this->assertEquals(10703, $setting->get('onejav', 'pages'));

        $service->all();
        $this->assertEquals(2, $setting->get('onejav', 'current_page'));
        $this->assertDatabaseCount('onejav', $items->count());
    }

    public function testDaily()
    {
        $this->instance(
            XClient::class,
            Mockery::mock(XClient::class, function (MockInterface $mock) {
                $mock->shouldReceive('init');
                for ($index = 1; $index <= 3; $index++) {
                    $response = new Response();
                    $response->reset(
                        200,
                        [],
                        file_get_contents(__DIR__.'/../../Fixtures/Crawlers/Onejav/2023_01_02_0'.$index.'.html'),
                    );
                    $mock->shouldReceive('setHeaders');
                    $mock->shouldReceive('get')
                        ->with(
                            OnejavCrawler::BASE_URL.'/'.now()->format(OnejavCrawler::DEFAULT_DATE_FORMAT),
                            ['page' => $index]
                        )->andReturn($response);
                }
            })
        );

        $items = app(OnejavService::class)->daily();
        $this->assertCount(29, $items);
        $this->assertDatabaseCount('onejav', $items->count());
    }
}
