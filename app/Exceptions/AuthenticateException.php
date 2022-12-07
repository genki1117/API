<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Lang;
use Throwable;

class AuthenticateException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        if (empty($message)) {
            $message = Lang::get("common.message.expired");
        }
        parent::__construct($message, $code, $previous);
    }
}
