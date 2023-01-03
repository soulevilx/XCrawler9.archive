<?php

namespace Modules\Jav\Services;

use Illuminate\Support\Collection;
use Modules\Core\Services\SettingService;
use Modules\Jav\Crawlers\OnejavCrawler;

class OnejavService
{
    public function __construct(private OnejavCrawler $crawler, private SettingService $service)
    {
    }

    public function all(): Collection
    {
        $items = collect();
        $currentPage = $this->service->remember('onejav', 'page', fn() => 1);

        $nextPage = $this->crawler->getItemsWithPage($items, 'new', ['page' => $currentPage]);

        if ($nextPage === $currentPage) {
            $nextPage = 1;
        }

        $this->service->forget('onejav', 'page');
        $this->service->remember('onejav', 'page', fn() => $nextPage);

        return $items;
    }

    public function daily(): Collection
    {
        return $this->crawler->daily();
    }
}
