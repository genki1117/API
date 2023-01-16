<?php
declare(strict_types=1);
namespace App\Exceptions;

use Illuminate\Validation\ValidationException;

class ValidationCustomException extends ValidationException
{
    /**
     * Create a new exception instance.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @param  \Symfony\Component\HttpFoundation\Response|null  $response
     * @param  string  $errorBag
     * @return void
     */
    public function __construct($validator, $response = null, $errorBag = 'default')
    {
        parent::__construct($validator, $response, $errorBag);
    }

    /**
     * フロント用に調整したエラー情報を返却する
     *
     * @return array
     */
    public function getAdjustErrors():array
    {
        return $this->validator->adjustErrors;
    }
}
