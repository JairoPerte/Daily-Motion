<?php

namespace App\Shared\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

class FileTooLargeException extends DailyMotionException
{
    public function __construct()
    {
        parent::__construct("File too large");
        $this->code = 400;
    }
}
