<?php

namespace App\Application\Exceptions;

use Exception;

class WalletNotFoundException extends Exception
{
    public function __construct($message = 'not found error', $code = 404, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
