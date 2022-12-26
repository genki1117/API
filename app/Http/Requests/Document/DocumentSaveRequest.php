<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class DocumentSaveRequest extends FormRequest
{
    /**@var array */
    protected const tables = [
        "0" => "t_document_contract",
        "1" => "t_document_deal",
        "2" => "t_document_internal",
        "3" => "t_document_archive",
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            // 共通
            'document_id'      => 'numeric',
            'company_id'       => 'required | numeric',
            'category_id'      => 'required | numeric',
            'template_id'      => 'numeric',
            'doc_type_id'      => 'required | numeric',
            'status_id'        => 'numeric',

            'doc_no'           => 'string | max:30',
            'ref_doc_no'       => 'json',
            'product_name'     => 'string | max:255',
            'title'            => 'string | max:255',
            'amount'           => 'numeric',
            'currency_id'      => 'numeric',
            'counter_party_id' => 'numeric',
            'remarks'          => 'string',
            'doc_info'         => 'json',
            'sign_level'       => 'numeric',
            'create_user'      => 'numeric',
            'create_datetime'  => 'date',
            'update_user'      => 'numeric',
            'update_datetime'  => 'date',
            'delete_user'      => 'numeric',
            'delete_datetime'  => 'date',

            // 契約書類
            'cont_start_date'  => 'date|unique:' . self::tables[$this->request->all()['category_id']],
            'cont_end_date'    => 'date|unique:' . self::tables[$this->request->all()['category_id']],
            'conc_date'        => 'date|unique:' . self::tables[$this->request->all()['category_id']],
            'effective_date'   => 'date|unique:' . self::tables[$this->request->all()['category_id']],
            'cancel_date'      => 'date|unique:' . self::tables[$this->request->all()['category_id']],
            'expiry_date'      => 'date|unique:' . self::tables[$this->request->all()['category_id']],
            
            // 取引書類
            'issue_date'       => 'date|unique:' . self::tables[$this->request->all()['category_id']],
            'expiry_date'      => 'date|unique:' . self::tables[$this->request->all()['category_id']],
            'payment_date'     => 'date|unique:' . self::tables[$this->request->all()['category_id']],
            'transaction_date' => 'date|unique:' . self::tables[$this->request->all()['category_id']],
            'download_date'    => 'date|unique:' . self::tables[$this->request->all()['category_id']],

            // 社内書類
            'doc_create_date'  => 'date|unique:' . self::tables[$this->request->all()['category_id']],
            'sign_finish_date' => 'date|unique:' . self::tables[$this->request->all()['category_id']],
            'content'          => 'string|unique:' . self::tables[$this->request->all()['category_id']],

            // 登録書類
            'scan_doc_flg'     => 'required | numeric|unique:' . self::tables[$this->request->all()['category_id']],
            'issue_date'       => 'date|unique:' . self::tables[$this->request->all()['category_id']],
            'expiry_date'      => 'date|unique:' . self::tables[$this->request->all()['category_id']],
            'transaction_date' => 'date|unique:' . self::tables[$this->request->all()['category_id']],
            'timestamp_user'   => 'numeric|unique:' . self::tables[$this->request->all()['category_id']],

            // 各書類容量
            'file_name'          => 'string | max:255',
            'file_path'          => 'string | max:255',
            'file_hash'          => 'string | max:255',
            'file_prot_flg'      => 'numeric',
            'file_prot_pw_flg'   => 'numeric',
            'file_timestamp_flg' => 'numeric',
            'file_sign'          => 'numeric',
            'width'              => 'numeric',
            'height'             => 'numeric',
            'dpi'                => 'numeric',
            'color_depth'        => 'numeric',
            'pdf_type'           => 'string | max:5',
            'pdf_version'        => 'string | max:4',
            'sign_position'      => 'json',
            'total_pages'        => 'numeric'
        ];
    }
    public function messages()
    {
        return [
            'document_id.numeric' => '書類IDは数値のみ入力してください。',
            'category_id.required' => "書類カテゴリは必須入力項目です。",
            'category_id.numeric' => '書類カテゴリは数値のみ入力してください。',
            'doc_type_id.reuired' => '書類種別IDは必須入力項目です。',
            'doc_type_id.numeric' => '書類種別IDは数値のみの入力をしてください',
            'tempalte_id.numeric' => 'テンプレートIDは数値のみの入力をしてください',
            'status_id.numeric' => 'ステータスIDは数値のみの入力をしてください',
            'cont_start_date.date' => '契約開始日の形式を確認してください。',
            'cont_end_date.date' => '契約終了日の形式を確認してください。',
            'conc_date.date' => '締結日の形式を確認してください',
            'effective_date.date' => '効力発生日の形式を確認してください',
            'cancel_date.date' => '解約日の形式を確認してください',
            'expiry_date.date' => '失効日の形式を確認してください',
            'doc_no.string' => '書類NOは文字列で入力してください',
            'doc_no.max' => '書類NOは30文字以内で入力してください',
            'ref_doc_no.json' => '参照書類NOはJSON形式で入力してください。'

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
