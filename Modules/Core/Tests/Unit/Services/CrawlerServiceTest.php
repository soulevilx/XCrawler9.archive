<?php

namespace Modules\Core\Tests\Unit\Services;

use Illuminate\Support\Facades\Event;
use JOOservices\XClient\Response\Dom;
use JOOservices\XClient\Response\Response;
use JOOservices\XClient\XClient;
use Mockery;
use Mockery\MockInterface;
use Modules\Core\Events\CrawlingFailed;
use Modules\Core\Events\CrawlingSuccess;
use Modules\Core\Events\RequestCached;
use Modules\Core\Services\CrawlerService;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;

class CrawlerServiceTest extends TestCase
{
    public function testCrawlGetSuccess()
    {
        Event::fake(CrawlingSuccess::class);
        $this->instance(
            XClient::class,
            Mockery::mock(XClient::class, function (MockInterface $mock) {
                $response = new Response();
                $response->reset(
                    200,
                    [],
                    $this->faker->randomHtml,
                    '1.1',
                    'OK'
                );
                $mock->shouldReceive('init');
                $mock->shouldReceive('get')->andReturn($response);
            })
        );
        $service = app(CrawlerService::class);
        $url = $this->faker->url;
        $this->assertInstanceOf(Crawler::class, $service->crawl($url)->format(Dom::class)->getData());
        $this->assertDatabaseHas('request_logs', [
            'url' => $url,
            'code' => 200,
        ], 'mongodb');

        Event::assertDispatched(CrawlingSuccess::class);
    }

    public function testCrawlGetFailed()
    {
        Event::fake(CrawlingFailed::class);
        $this->instance(
            XClient::class,
            Mockery::mock(XClient::class, function (MockInterface $mock) {
                $response = new Response();
                $response->reset(
                    400,
                    [],
                    '',
                    '1.1',
                );
                $response->isSucceed = false;

                $mock->shouldReceive('init');
                $mock->shouldReceive('get')->andReturn($response);
            })
        );
        $service = app(CrawlerService::class);
        $url = $this->faker->url;
        $this->assertInstanceOf(Crawler::class, $service->crawl($url)->format(Dom::class)->getData());
        $this->assertDatabaseHas('request_logs', [
            'url' => $url,
            'code' => 400,
        ], 'mongodb');
        Event::assertDispatched(CrawlingFailed::class);
    }

    public function testCrawlGetSuccessWithCached()
    {
        Event::fake([CrawlingSuccess::class, RequestCached::class]);
        $this->instance(
            XClient::class,
            Mockery::mock(XClient::class, function (MockInterface $mock) {
                $response = new Response();
                $response->reset(
                    200,
                    [],
                    $this->faker->randomHtml,
                    '1.1',
                    'OK'
                );
                $mock->shouldReceive('init')->once();
                $mock->shouldReceive('get')->andReturn($response)->once();
            })
        );
        $service = app(CrawlerService::class);
        $url = $this->faker->url;
        $this->assertInstanceOf(Crawler::class, $service->crawl($url)->format(Dom::class)->getData());
        $this->assertDatabaseHas('request_logs', [
            'url' => $url,
            'code' => 200,
        ], 'mongodb');

        Event::assertDispatched(CrawlingSuccess::class);
        Event::assertNotDispatched(RequestCached::class);

        $service->crawl($url);

        Event::assertDispatched(RequestCached::class);
    }
}
