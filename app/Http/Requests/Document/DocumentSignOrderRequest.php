<?php

namespace App\Http\Requests\Document;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class DocumentSignOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "document_id" => "required | numeric",
            "doc_type_id" => "required | numeric",
            "category_id" => "required | numeric"
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            "document_id.required" => "error.message.required",
            "document_id.numeric"  => "error.message.number",
            "category_id.required" => "error.message.required",
            "category_id.numeric"  => "error.message.number",
            "doc_type_id.required" => "error.message.required",
            "doc_type_id.numeric"  => "error.message.number"
            
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
