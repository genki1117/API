<?php
declare(strict_types=1);
namespace App\Http\Requests\Document;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class DocumentCsvDownloadRequest extends FormRequest
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
            'category_id' => 'required | numeric'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        // TODO:存在チェック error.message.document_exist 0,1,2,3以外のリクエストならエラー
        return [
            'category_id.required' => 'error.message.required',
            'category_id.numeriv'  => 'error.message.number'
        ];
    }

    /**
     * @param Validator $validator
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $response['errors']  = $validator->errors()->toArray();
        $exception = new HttpResponseException(response()->json($response, 400));
        throw $exception;
    }
}
