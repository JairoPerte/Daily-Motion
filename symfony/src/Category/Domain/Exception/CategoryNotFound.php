<?php

namespace App\Category\Domain\Exception;

use App\Shared\Domain\Exception\NotFoundException;

class CategoryNotFound extends NotFoundException
{
    public function __construct()
    {
        parent::__construct("La categoría no existe");
    }
}
