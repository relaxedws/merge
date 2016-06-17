<?php

namespace Relaxed\Merge\ConflictException;

use Exception;

class ConflictException extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}