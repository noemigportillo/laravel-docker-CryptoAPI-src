<?php

namespace App\Application\Exceptions;

use Exception;

class InsuficientAmountException extends Exception
{
    public function __construct($message = 'not enough amount', $code = 406, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
