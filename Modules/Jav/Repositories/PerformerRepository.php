<?php

namespace Modules\Jav\Repositories;

use Modules\Core\Repositories\AbstractBaseRepository;
use Modules\Jav\Models\Performers;

class PerformerRepository extends AbstractBaseRepository
{
    public function __construct(public Performers $model)
    {
    }

    public function uniqueColumns(): array
    {
        return ['name'];
    }
}
