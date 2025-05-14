<?php

namespace App\Category\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

class CategoryNotOwnedByUserException extends DailyMotionException
{
    public function __construct()
    {
        parent::__construct("User does not own this category");
        $this->httpCode = 403;
    }
}
