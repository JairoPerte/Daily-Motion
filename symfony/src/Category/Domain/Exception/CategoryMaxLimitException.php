<?php

namespace App\Category\Domain\Exception;

use App\Shared\Domain\Exception\MaxLimitException;

class CategoryMaxLimitException extends MaxLimitException
{
    public function __construct()
    {
        parent::__construct("Max limit category result reached");
    }
}
