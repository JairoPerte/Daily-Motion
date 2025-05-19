<?php

namespace App\Category\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

class CategoryNameTooLongException extends DailyMotionException
{
    public function __construct()
    {
        $this->httpCode = 400;
        parent::__construct("Category name is too long");
    }
}
