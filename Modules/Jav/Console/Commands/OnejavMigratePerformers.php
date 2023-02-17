<?php

namespace Modules\Jav\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Modules\Jav\Repositories\OnejavRepository;
use Modules\Jav\Services\OnejavService;

class OnejavMigratePerformers extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'jav:onejav-migrate-performers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate performers from Onejav to Performers.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(
        OnejavRepository $onejavRepository,
    ) {
        $service = app(OnejavService::class);
        $this->output->progressStart($onejavRepository->count());
        foreach ($onejavRepository->getAll() as $onejav) {
            $this->output->progressAdvance();
            $service->syncPerformers($onejav);
        }

        $this->output->progressFinish();
    }
}
