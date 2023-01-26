<?php

namespace Modules\Jav\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;

class OnejavDaily extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'jav:onejav-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Onejav daily.';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Modules\Jav\Jobs\OnejavDaily::dispatch()->onQueue('high');
    }
}
