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
            'title'            => 'required | string | max:100',
            'amount'           => 'required | numeric',
            'currency_id'      => 'required | numeric',
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
            'cont_start_date'  => 'required |date|unique:' . self::tables[$this->request->all()['category_id']],
            'cont_end_date'    => 'required |date|unique:' . self::tables[$this->request->all()['category_id']],
            'conc_date'        => 'required |date|unique:' . self::tables[$this->request->all()['category_id']],
            'effective_date'   => 'required |date|unique:' . self::tables[$this->request->all()['category_id']],
            'cancel_date'      => 'required |date|unique:' . self::tables[$this->request->all()['category_id']],
            'expiry_date'      => 'required |date|unique:' . self::tables[$this->request->all()['category_id']],
            
            // 取引書類
            'issue_date'       => 'required |date|unique:' . self::tables[$this->request->all()['category_id']],
            'expiry_date'      => 'required |date|unique:' . self::tables[$this->request->all()['category_id']],
            'payment_date'     => 'required |date|unique:' . self::tables[$this->request->all()['category_id']],
            'transaction_date' => 'required |date|unique:' . self::tables[$this->request->all()['category_id']],
            'download_date'    => 'required |date|unique:' . self::tables[$this->request->all()['category_id']],

            // 社内書類
            'doc_create_date'  => 'required |date|unique:' . self::tables[$this->request->all()['category_id']],
            'sign_finish_date' => 'required |date|unique:' . self::tables[$this->request->all()['category_id']],
            'content'          => 'required |string|unique:' . self::tables[$this->request->all()['category_id']],

            // 登録書類
            'scan_doc_flg'     => 'required | numeric|unique:' . self::tables[$this->request->all()['category_id']],
            'issue_date'       => 'required |date|unique:' . self::tables[$this->request->all()['category_id']],
            'expiry_date'      => 'required |date|unique:' . self::tables[$this->request->all()['category_id']],
            'transaction_date' => 'required |date|unique:' . self::tables[$this->request->all()['category_id']],
            'timestamp_user'   => 'required |numeric|unique:' . self::tables[$this->request->all()['category_id']],

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
            
            'document_id.numeric'  => 'error.message.number',
            'company_id.required'  => 'error.message.required',
            'company_id.numeric'   => 'error.message.number',
            'category_id.required' => 'error.message.required',
            'category_id.numeric'  => 'error.message.number',
            'template_od.numeric'  => 'error.message.number',
            'doc_type_id.required' => 'error.message.required',
            'doc_type_id.number' => 'error.message.number',
            'status_id.numeric' =>'error.message.number',


            'doc_no.string' => 'error.message.string',
            'doc_no.length' => 'error.message.lenth.over',

            'product_name.string' => 'error.message.string',
            'product_name.length' => 'error.message.length.over',
            'product_name.string' => 'error.message.string',
            'product_name.string' => 'error.message.length.over',
            'titile.required'     => 'error.message.required',
            'titile.string'       => 'error.message.string',
            'titile.length'       => 'error.message.length.over',
            'amount.required'     => 'error.message.required',
            'amount.numeric'      => 'error.message.number',
            'currency_id.required'=> 'error.message.required',
            'currency_id.numeric' => 'error.message.numeric',
            'counter_party_id.numeric' => 'error.message.numeric',
            'remarks.sring'       => 'error.message.string',
            'sign_level.numeric'  => 'error.message.number',
            'create_user.numeric' => 'error.message.number',
            'update_datetime.required' => 'error.message.required',
            'update_datetime.date' => 'error.message.datetime',
            'update_datetime.unique' => 'common.message.save-conflict',
             'cont_start_date.required'  => 'error.message.required',
             'cont_start_date.date'  => 'error.message.datetime',
             'cont_end_date.required'  => 'error.message.required',
             'cont_end_date.date'  => 'error.message.datetime',
             'conc_date.required'  => 'error.message.required',
             'conc_date.date'  => 'error.message.datetime',
             'effective_date.required'  => 'error.message.required',
             'effective_date.date'  => 'error.message.datetime',
             'cancel_date.required'  => 'error.message.required',
             'cancel_date.date'  => 'error.message.datetime',
             'expiry_date.required'  => 'error.message.required',
             'expiry_date.date'  => 'error.message.datetime',
             'issue_date.required'  => 'error.message.required',
             'issue_date.date'  => 'error.message.datetime',
             'expiry_date.required'  => 'error.message.required',
             'expiry_date.date'  => 'error.message.datetime',
             'payment_date.required'  => 'error.message.required',
             'payment_date.date'  => 'error.message.datetime',
             'transaction_date.required'  => 'error.message.required',
             'transaction_date.date'  => 'error.message.datetime',
             'download_date.required'  => 'error.message.required',
             'download_date.date'  => 'error.message.datetime',
             'doc_create_date.required'  => 'error.message.required',
             'doc_create_date.date'  => 'error.message.datetime',
             'sign_finish_date.required'  => 'error.message.required',
             'sign_finish_date.date'  => 'error.message.datetime',
             'content.required'  => 'error.message.required',
             'content.date'  => 'error.message.string',
             'scan_doc_flg.required'  => 'error.message.required',
             'scan_doc_flg.date'  => 'error.message.numeric',
             'issue_date.required'  => 'error.message.required',
             'issue_date.date'  => 'error.message.datetime',
             'expiry_date.required'  => 'error.message.required',
             'expiry_date.date'  => 'error.message.datetime',
             'transaction_date.required'  => 'error.message.required',
             'transaction_date.date'  => 'error.message.datetime',
             'timestamp_user.required'  => 'error.message.required',
             'timestamp_user.numeric'  => 'error.message.numeric',
             'file_name.length'  => 'error.message.length.over',
             'file_name.string'  => 'error.message.string',
             'file_path.length'  => 'error.message.length.over',
             'file_path.string'  => 'error.message.string',
             'file_hash.length'  => 'error.message.length.over',
             'file_hash.string'  => 'error.message.string',
             'file_prot_flg.numeric'  => 'error.message.numeric',
             'file_prot_pw_flg.numeric'  => 'error.message.numeric',
             'file_timestamp_flg.numeric'  => 'error.message.numeric',
             'file_sign.numeric'  => 'error.message.numeric',
             'width.numeric'  => 'error.message.numeric',
             'height.numeric'  => 'error.message.numeric',
             'dpi.numeric'  => 'error.message.numeric',
             'color_depth.numeric'  => 'error.message.numeric',
             'pdf_type.string'      => 'error.message.string',
             'pdf_type.length'  => 'error.message.length.over',
             'pdf_version.string'  => 'error.message.string',
             'pdf_version.length'  => 'error.message.length',
             'total_pages.numeric'  => 'error.message.numeric',

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
