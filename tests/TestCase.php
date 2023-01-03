<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use WithFaker;
    use DatabaseMigrations;

    use RefreshDatabase {
        RefreshDatabase::refreshDatabase as refreshSchema;
    }

    public function refreshDatabase()
    {
        $this->artisan('db:wipe');
        $this->artisan('migrate:fresh');
    }
}
