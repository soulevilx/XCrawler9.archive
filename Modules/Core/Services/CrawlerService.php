<?php

namespace Modules\Core\Services;

use Campo\UserAgent;
use Illuminate\Support\Facades\Event;
use JOOservices\XClient\Response\Response;
use JOOservices\XClient\XClient;
use Modules\Core\Events\CrawlingFailed;
use Modules\Core\Events\CrawlingSuccess;
use Modules\Core\Models\RequestLog;

class CrawlerService
{
    /**
     * @throws \Exception
     */
    public function __construct(private XClient $client)
    {
        $this->client->init([], [
            'stream' => false,
        ]);

        $this->client->setHeaders(['User-Agent', UserAgent::random(['device_type' => 'Desktop'])]);
    }

    public function crawl(string $url, array $payload = []): Response
    {
        $requestLog = RequestLog::create(compact('url', 'payload'));
        $response = $this->client->get($url, $payload);

        if ($response->isSuccessful()) {
            Event::dispatch(new CrawlingSuccess($url, $payload));
        } else {
            Event::dispatch(new CrawlingFailed($url, $payload));
        }

        $stream = $response->getBody();
        $content = $stream->getContents();

        $requestLog->update([
            'code' => $response->getStatusCode(),
            'response' => (string) $content,
        ]);

        $stream->rewind();

        return $response;
    }
}
