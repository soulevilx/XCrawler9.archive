<?php

namespace Modules\Jav\Crawlers;

use ArrayObject;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use JOOservices\XClient\Response\Dom;
use Modules\Core\Services\CrawlerService;
use Modules\Jav\Events\OnejavItemParsed;
use Modules\Jav\Models\Onejav;
use Symfony\Component\DomCrawler\Crawler;

class OnejavCrawler extends CrawlerService
{
    public const BASE_URL = 'https://onejav.com';
    public const DEFAULT_DATE_FORMAT = 'Y/m/d';

    public function getItems(string $url, array $payload = ['page' => 1]): Collection
    {
        $response = $this->crawl($url, $payload);

        if (!$response->isSuccessful()) {
            return collect();
        }

        return collect(
            $response->format(Dom::class)->getData()->filter('.container .columns')
                ->each(function ($el) {
                    return $this->parse($el);
                })
        );
    }

    public function daily(): Collection
    {
        $models = collect();
        $this->getItemsRecursive($models, Carbon::now()->format(self::DEFAULT_DATE_FORMAT));

        return $models;
    }

    public function search(string $keyword, string $by = 'search')
    {
        $models = collect();
        $this->getItemsRecursive($models, $by.'/'.urlencode($keyword));

        return $models;
    }

    public function getItemsWithPage(Collection &$models, string $url, array $payload = []): int
    {
        $currentPage = !empty($payload['page']) ? $payload['page'] : 1;
        if (empty($payload['page'])) {
            $payload['page'] = $currentPage;
        }

        $response = $this->crawl(self::BASE_URL.'/'.$url, $payload);

        if (!$response->isSuccessful()) {
            return 1;
        }

        $dom = $response->format(Dom::class)->getData();
        $pageNode = $dom->filter('a.pagination-link')->last();

        $lastPage = 0 === $pageNode->count() ? 1 : (int) $pageNode->text();

        $models = $models->merge(
            collect($dom->filter('.container .columns')->each(function ($el) {
                return $this->parse($el);
            }))
        );

        return $lastPage;
    }

    public function getItemsRecursive(Collection &$models, string $url, array $payload = []): int
    {
        $currentPage = !empty($payload['page']) ? $payload['page'] : 1;
        if (empty($payload['page'])) {
            $payload['page'] = $currentPage;
        }

        $response = $this->crawl(self::BASE_URL.'/'.$url, $payload);

        if (!$response->isSuccessful()) {
            return 1;
        }

        $dom = $response->format(Dom::class)->getData();
        $pageNode = $dom->filter('a.pagination-link')->last();

        $lastPage = 0 === $pageNode->count() ? 1 : (int) $pageNode->text();

        $models = $models->merge(
            collect($dom->filter('.container .columns')->each(function ($el) {
                return $this->parse($el);
            }))
        );

        if (empty($payload) || $payload['page'] < $lastPage) {
            sleep(1);
            $lastPage = $this->getItemsRecursive($models, $url, ['page' => $currentPage + 1]);
        }

        return $lastPage;
    }

    private function parse(Crawler $crawler): Model
    {
        $model = app(Onejav::class);

        if ($crawler->filter('h5.title a')->count()) {
            $model->url = trim($crawler->filter('h5.title a')->attr('href'));
        }

        if ($crawler->filter('.columns img.image')->count()) {
            $model->cover = trim($crawler->filter('.columns img.image')->attr('src'));
        }

        if ($crawler->filter('h5 a')->count()) {
            $model->dvd_id = (trim($crawler->filter('h5 a')->text(null, false)));
            $model->dvd_id = implode(
                '-',
                preg_split('/(,?\\s+)|((?<=[a-z])(?=\\d))|((?<=\\d)(?=[a-z]))/i', $model->dvd_id)
            );
        }

        if ($crawler->filter('h5 span')->count()) {
            $model->size = trim($crawler->filter('h5 span')->text(null, false));

            if (str_contains($model->size, 'MB')) {
                $model->size = (float) trim(str_replace('MB', '', $model->size));
                $model->size /= 1024;
            } elseif (str_contains($model->size, 'GB')) {
                $model->size = (float) trim(str_replace('GB', '', $model->size));
            }
        }

        // Always use href because it'll never change but text will be
        $model->date = $this->convertStringToDateTime(trim($crawler->filter('.subtitle.is-6 a')->attr('href')));
        $model->genres = collect($crawler->filter('.tags .tag')->each(
            function ($genres) {
                return trim($genres->text(null, false));
            }
        ))->reject(function ($value) {
            return empty($value);
        })->unique()->toArray();

        // Description
        $description = $crawler->filter('.level.has-text-grey-dark');
        $model->description = $description->count() ? trim($description->text(null, false)) : null;
        $model->description = preg_replace("/\r|\n/", '', $model->description);

        $model->performers = collect($crawler->filter('.panel .panel-block')->each(
            function ($performers) {
                return trim($performers->text(null, false));
            }
        ))->reject(function ($value) {
            return empty($value);
        })->unique()->toArray();

        $model->torrent = trim($crawler->filter('.control.is-expanded a')->attr('href'));

        // Gallery. Only for FC
        $gallery = $crawler->filter('.columns .column a img');
        if ($gallery->count()) {
            $model->gallery = collect($gallery->each(
                function ($image) {
                    return trim($image->attr('src'));
                }
            ))->reject(function ($value) {
                return empty($value);
            })->unique()->toArray();
        }

        Event::dispatch(new OnejavItemParsed($model));

        return $model;
    }

    private function convertStringToDateTime(string $date): ?Carbon
    {
        if (!$dateTime = Carbon::createFromFormat(self::DEFAULT_DATE_FORMAT, trim($date, '/'))) {
            return null;
        }

        return $dateTime;
    }
}
