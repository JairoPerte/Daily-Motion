<?php

namespace App\Category\Application\Service;

use App\Category\Domain\Exception\CategoryMaxLimitException;

class ThrowExceptionIfMaxCategoryLimit
{
    public function __invoke(int $limit): void
    {
        if ($limit > 20 || $limit <= 0) {
            throw new CategoryMaxLimitException();
        }
    }
}
