<?php
declare(strict_types=1);
namespace App\Exceptions;

use Exception;
use Throwable;

class AuthenticateException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        if (empty($message)) {
            $message = "common.message.expired";
        }
        parent::__construct($message, $code, $previous);
    }
}
