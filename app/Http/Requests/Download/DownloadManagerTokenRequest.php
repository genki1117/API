<?php

namespace App\Http\Requests\Download;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class DownloadManagerTokenRequest extends FormRequest
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
            'token' => ['required', 'string', 'exists:temp_token']
        ];
    }

    protected function PrepareForValidation()
    {
        $this->merge(['token' => $this->route('token')]);
    }

     /**
     * @return array
     */
    public function messages()
    {
        return [
            'token.required' => 'error.message.required',
            'token.string'   => 'error.message.string',
            'token.exits'    => 'common.message.expired'
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
