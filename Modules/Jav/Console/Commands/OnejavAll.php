<?php

namespace Modules\Jav\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;

class OnejavAll extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'jav:onejav-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all Onejav until end of pages.';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Modules\Jav\Jobs\OnejavAll::dispatch()->onQueue('jav');
    }
}
