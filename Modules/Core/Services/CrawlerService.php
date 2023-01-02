<?php

namespace Modules\Core\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use JOOservices\XClient\Response\Response;
use JOOservices\XClient\XClient;
use Modules\Core\Events\CrawlingFailed;
use Modules\Core\Events\CrawlingSuccess;
use Modules\Core\Events\RequestCached;
use Modules\Core\Models\RequestLog;

class CrawlerService
{
    public function __construct(private XClient $client)
    {
        $this->client->init();
    }

    public function crawl(string $url, array|null $payload = null)
    {
        $payload ??= [];
        if (config('core.crawling.cache.enable')) {
            if (Cache::has($url)) {
                Event::dispatch(new RequestCached($url, $payload));
            }
            return Cache::remember($url, config('core.crawling.cache.interval'), function () use ($url, $payload) {
                return $this->_crawling($url, $payload);
            });
        }

        return $this->_crawling($url, $payload);
    }

    private function _crawling(string $url, array|null $payload = null): Response
    {
        $payload ??= [];
        $requestLog = RequestLog::create(compact('url', 'payload'));
        $response = $this->client->get($url, $payload);
        if ($response->isSuccessful()) {
            Event::dispatch(new CrawlingSuccess($url, $payload));
        } else {
            Event::dispatch(new CrawlingFailed($url, $payload));
        }

        $requestLog->update([
            'code' => $response->getStatusCode(),
            'response' => $response->getBody()->getContents()
        ]);

        return $response;
    }
}
