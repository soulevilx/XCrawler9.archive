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
        $currentPage = $this->service->remember('onejav', 'current_page', fn() => 0);
        $lastPages = $this->crawler->getItemsWithPage($items, 'new', ['page' => $currentPage + 1]);

        if ($currentPage === $lastPages) {
            $currentPage = 0;
        }

        $this->service->forget('onejav', 'pages');
        $this->service->remember('onejav', 'pages', fn() => $lastPages);

        $this->service->forget('onejav', 'current_page');
        $this->service->remember('onejav', 'current_page', fn() => $currentPage + 1);

        return $items;
    }

    public function daily(): Collection
    {
        return $this->crawler->daily();
    }
}
