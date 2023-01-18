<?php

namespace App\Http\Requests\Document;

use App\Libraries\ResponseClient;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class DocumentSignOrderRequest extends FormRequest
{

    use ResponseClient;
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
     * @return array
     */
    private function errorsTable():array
    {
        return [
            'document_id' => [
                "Required" =>  [
                    ["type" => "label", "content" => "common.label.item.document.id"],
                ],
                "Numeric" =>  [
                    ["type" => "label", "content" => "common.label.item.document.id"],
                ],
            ],
            'doc_type_id' => [
                "Required" =>  [
                    ["type" => "label", "content" => "common.label.item.doc.type.id"],
                ],
                "Numeric" =>  [
                    ["type" => "label", "content" => "common.label.item.doc.type.id"],
                ],
            ],
            'category_id' => [
                "Required" =>  [
                    ["type" => "label", "content" => "common.label.item.category.id"],
                ],
                "Numeric" =>  [
                    ["type" => "label", "content" => "common.label.item.category.id"],
                ],
            ],
        ];
    }

    /**
     * @param Validator $validator
     * @throws Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($this->adjustValidator($validator));
    }
}
