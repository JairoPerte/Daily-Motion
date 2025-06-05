<?php

namespace App\Shared\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

class FileBadProcessedException extends DailyMotionException
{
    public function __construct()
    {
        parent::__construct("File bad processed");
        $this->code = 400;
    }
}
