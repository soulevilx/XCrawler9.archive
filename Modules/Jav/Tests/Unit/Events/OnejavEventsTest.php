<?php

namespace Modules\Jav\Tests\Unit\Events;

use JOOservices\XClient\Response\Response;
use JOOservices\XClient\XClient;
use Mockery;
use Mockery\MockInterface;
use Modules\Jav\Crawlers\OnejavCrawler;
use Tests\TestCase;

class OnejavEventsTest extends TestCase
{
    public function testOnejavItemParsedEvent()
    {
        $this->instance(
            XClient::class,
            Mockery::mock(XClient::class, function (MockInterface $mock) {
                $response = new Response();
                $response->reset(
                    200,
                    [],
                    file_get_contents(__DIR__.'/../../Fixtures/Crawlers/Onejav/2022_12_31.html'),
                );

                $mock->shouldReceive('init');
                $mock->shouldReceive('setHeaders');
                $mock->shouldReceive('get')->andReturn($response);
            })
        );

        $crawler = app(OnejavCrawler::class);

        $items = $crawler->getItems($this->faker->url);

        $this->assertEquals('/torrent/ymdd304', $items->first()->url);
        $this->assertEquals('https://pics.dmm.co.jp/mono/movie/adult/ymdd304/ymdd304pl.jpg', $items->first()->cover);
        $this->assertEquals('YMDD-304', $items->first()->dvd_id);
        $this->assertEquals(4.5, $items->first()->size);
        $this->assertEquals('2022-12-31', $items->first()->date->format('Y-m-d'));
        $this->assertIsArray($items->first()->genres);
        $this->assertEquals('Blow', $items->first()->genres[0]);
        $this->assertEquals('Creampie', $items->first()->genres[1]);
        $this->assertEquals('Planning', $items->first()->genres[2]);
        $this->assertEquals('Reversed Role', $items->first()->genres[3]);
        $this->assertEquals('Slut', $items->first()->genres[4]);
        $this->assertEquals(
            "The Bimbo Wagon Goes! ! Happening A Go Go! ! Melody, Hina, Marks, June, Lovejoy, And Liz's Strange Journey The Beautiful Girl Born In Northern Europe, Who Is One In 27 Million People, Makes Another Attack! A Sticky Thick DeepKiss And Fierce SEX With A Japanese Boy",
            $items->first()->description
        );
        $this->assertIsArray($items->first()->performers);
        $this->assertEquals('Lovejoy June', $items->first()->performers[0]);
        $this->assertEquals('Marks Hiina Melody', $items->first()->performers[1]);
        $this->assertEquals('/torrent/ymdd304/download/17076960/onejav.com_ymdd304.torrent', $items->first()->torrent);

        $this->assertDatabaseCount('onejav', $items->count());
    }
}
