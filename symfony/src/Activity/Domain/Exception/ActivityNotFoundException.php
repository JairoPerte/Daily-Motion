<?php

namespace App\Activity\Domain\Exception;

use App\Shared\Domain\Exception\NotFoundException;

class ActivityNotFoundException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct("Activity not found");
    }
}
