<?php

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\NotFoundException;

class UserNotFoundException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct(message: "Usuario no encontrado");
    }
}
