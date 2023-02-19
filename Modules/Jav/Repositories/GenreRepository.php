<?php

namespace Modules\Jav\Repositories;

use Modules\Core\Repositories\AbstractBaseRepository;
use Modules\Jav\Models\Genre;

class GenreRepository extends AbstractBaseRepository
{
    public function __construct(public Genre $model)
    {
    }

    public function uniqueColumns(): array
    {
        return ['name'];
    }
}
