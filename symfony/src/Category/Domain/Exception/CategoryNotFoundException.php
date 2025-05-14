<?php

namespace App\Category\Domain\Exception;

use App\Shared\Domain\Exception\NotFoundException;

class CategoryNotFoundException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct("Category not found");
    }
}
