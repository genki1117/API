<?php
declare(strict_types=1);
namespace App\Http\Requests\Samples;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UseSampleRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mail_address' => 'required',
            'password'    => 'required'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'mail_address.required' => "メールアドレスは必須です。",
            'password.required'     => "パスワードは必須です。",
        ];
    }

    /**
     * @param Validator $validator
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $exception = new ValidationException($validator);
        $exception->status(400);
        throw $exception;
    }
}
