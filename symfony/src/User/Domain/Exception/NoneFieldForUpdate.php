<?php

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

class NoneFieldForUpdate extends DailyMotionException
{
    public function __construct()
    {
        parent::__construct('At least one field must be provided for update');
        $this->httpCode = 400;
    }
}
