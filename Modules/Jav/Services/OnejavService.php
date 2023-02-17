<?php

namespace Modules\Jav\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Modules\Core\Services\SettingService;
use Modules\Jav\Crawlers\OnejavCrawler;
use Modules\Jav\Events\OnejavItemCreated;
use Modules\Jav\Events\OnejavPerformerSynced;
use Modules\Jav\Repositories\OnejavRepository;
use Modules\Jav\Repositories\PerformerRepository;

class OnejavService
{
    public function __construct(
        private OnejavCrawler $crawler,
        private SettingService $service,
        private OnejavRepository $repository
    ) {
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

    public function create(array $attributes): Model
    {
        $model = $this->repository->create($attributes);

        $this->syncPerformers($model);

        Event::dispatch(new OnejavItemCreated($model));

        return $model;
    }

    public function syncPerformers(Model $model): void
    {
        $performerRepository = app(PerformerRepository::class);
        foreach ($model->performers as $performer) {
            $performerModel = $performerRepository->create(['name' => $performer,]);
            $model->exPerformers()->syncWithoutDetaching($performerModel->id);
        }

        Event::dispatch(new OnejavPerformerSynced($model));
    }
}
