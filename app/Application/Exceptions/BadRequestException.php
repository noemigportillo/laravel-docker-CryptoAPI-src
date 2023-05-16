<?php

namespace App\Application\Exceptions;

use Exception;

class BadRequestException extends Exception
{
    public function __construct($message = 'bad request error', $code = 400, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
