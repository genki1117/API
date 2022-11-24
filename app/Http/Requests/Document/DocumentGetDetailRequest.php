<?php
declare(strict_types=1);
namespace App\Http\Requests\Document;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class DocumentGetDetailRequest extends FormRequest
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
            'document_id' => 'required|numeric',
            'category_id'    => 'required|numeric'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'document_id.required' => "書類IDは必須入力項目です。",
            'document_id.numeric' => '書類IDは数値のみ入力してください。',
            'category_id.required' => "書類書類カテゴリは必須入力項目です。",
            'category_id.numeric' => '書類書類カテゴリは数値のみ入力してください。',
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
