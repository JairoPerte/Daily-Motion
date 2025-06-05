<?php

namespace App\Shared\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

class FileTypeNotSupportedException extends DailyMotionException
{
    public function __construct()
    {
        parent::__construct("File type not supported");
        $this->code = 400;
    }
}
