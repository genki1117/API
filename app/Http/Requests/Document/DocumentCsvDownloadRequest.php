<?php
declare(strict_types=1);
namespace App\Http\Requests\Document;


use App\Libraries\ResponseClient;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class DocumentCsvDownloadRequest extends FormRequest
{
    use ResponseClient;

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
            'category_id' => ['required', 'numeric', 'in:0,1,2,3']
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'category_id.required' => 'error.message.required',
            'category_id.numeric'  => 'error.message.number',
            'category_id.in'       => 'error.message.exists'
        ];
    }

    /**
     * @return array
     */
    private function errorsTable():array
    {
        return [
            'category_id' => [
                "Required" =>  [
                    ["type" => "label", "content" => "common.label.item.document.id"],
                ],
                "Numeric" =>  [
                    ["type" => "label", "content" => "common.label.item.document.id"],
                ],
                "In" => [
                    ["type" => "label", "content" => "common.label.item.document.id"],
                ]
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
