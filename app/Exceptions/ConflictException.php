<?php
declare(strict_types=1);
namespace App\Exceptions;

use Exception;
use Throwable;

class ConflictException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        if (empty($message)) {
            $message = "common.message.save-conflict";
        }
        parent::__construct($message, $code, $previous);
    }
}
