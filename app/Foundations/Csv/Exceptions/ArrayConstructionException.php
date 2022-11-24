<?php
declare(strict_types=1);
namespace App\Foundations\Csv\Exceptions;

use Throwable;

class ArrayConstructionException extends \LogicException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, 500, $previous);
    }
}
