<?php
declare(strict_types=1);
namespace App\Http\Requests\Document;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class DocumentDeleteRequest extends FormRequest
{
    /** @var array */
    protected const tables = [
        "0" => "t_document_contract",
        "1" => "t_document_deal",
        "2" => "t_document_internal",
        "3" => "t_document_internal"
    ];

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
            'category_id' => 'required|numeric',
            'update_datetime' => 'required|date|unique:'.self::tables[$this->request->all()['category_id']]
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
            'update_datetime.required' => "更新日時は必須入力項目です。",
            'update_datetime.date' => '更新日時は付与でエラーが発生しました。?',
            'update_datetime.unique' => '他のユーザによってデータが更新されました。データを再読込みしてから変更を再度入力してください。'
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        // update_datetimeにてUNIXタイムスタンプをコンピューターのシステム時刻に変更する
        $array = $this->all();
        $array['update_datetime'] = date('Y-m-d', $array['update_datetime']);
        return $array;
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
