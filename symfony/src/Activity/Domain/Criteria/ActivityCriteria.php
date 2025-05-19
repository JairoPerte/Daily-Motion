<?php

namespace App\Activity\Domain\Criteria;

class ActivityCriteria
{
    public function __construct(
        public readonly ?string $categoryId,
        public readonly ?string $name
    ) {}
}
