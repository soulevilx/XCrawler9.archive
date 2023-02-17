<?php

namespace Modules\Core\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\LazyCollection;

abstract class AbstractBaseRepository
{
    protected function getColumns()
    {
        return Schema::getColumnListing($this->model->getTable());
    }

    abstract public function uniqueColumns(): array;

    public function create(array $attributes): Model
    {
        return $this->model->updateOrCreate(
            array_intersect_key($attributes, array_flip($this->uniqueColumns())),
            Arr::only($attributes, $this->getColumns())
        );
    }

    public function getAll():  LazyCollection
    {
        return $this->model->cursor();
    }

    public function count(): int
    {
        return $this->model->count();
    }
}
