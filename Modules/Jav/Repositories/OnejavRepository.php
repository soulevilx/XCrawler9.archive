<?php

namespace Modules\Jav\Repositories;

use Modules\Core\Repositories\AbstractBaseRepository;
use Modules\Jav\Models\Onejav;

class OnejavRepository extends AbstractBaseRepository
{
    public function __construct(public Onejav $model)
    {
    }

    public function uniqueColumns(): array
    {
        return ['url'];
    }
}
