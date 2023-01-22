<?php

namespace Modules\Jav\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Modules\Jav\Models\Onejav;

class OnejavRepository
{
    public function __construct(public Onejav $model)
    {
    }

    public function create(array $attributes): Model
    {
        return $this->model->updateOrCreate(
            [
                'url' => $attributes['url'],
            ],
            Arr::only($attributes, $this->getColumns())
        );
    }

    private function getColumns()
    {
        return Schema::getColumnListing($this->model->getTable());
    }
}
