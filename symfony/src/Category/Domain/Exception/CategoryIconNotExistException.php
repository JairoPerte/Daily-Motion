<?php

namespace App\Category\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

class CategoryIconNotExistException extends DailyMotionException
{
    public function __construct()
    {
        parent::__construct("Category icon does not exists");
        $this->httpCode = 422;
    }
}
