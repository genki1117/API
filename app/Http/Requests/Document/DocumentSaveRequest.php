<?php

namespace App\Http\Requests\Document;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
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
            // 共通
            'document_id'       => 'numeric',
            'company_id'        => 'required | numeric',
            'category_id'       => 'required | numeric',
            'template_id'       => 'numeric',
            'doc_type_id'       => 'required | numeric',
            'status_id'         => 'numeric',
            'doc_no'            => 'string | max:20',
            'product_name'      => 'string | max:255',
            'title'             => 'required | string | max:100',
            'amount'            => 'required | numeric',
            'currency_id'       => 'required | numeric',
            'counter_party_id'  => 'numeric',
            'sign_level'        => 'numeric',

            // 契約書類
            'cont_start_date'   => 'date',
            'cont_end_date'     => 'date',
            'conc_date'         => 'date',
            'effective_date'    => 'date',
            'cancel_date'       => 'date',
            'expiry_date'       => 'date',
            
            // 取引書類
            'issue_date'        => 'date',
            'payment_date'      => 'date',
            'transaction_date'  => 'date',
            'download_date'     => 'date',

            // 社内書類
            'doc_create_date'   => 'date',
            'sign_finish_date'  => 'date',
            'content'           => 'string',

            // 登録書類
            'scan_doc_flg'      => 'numeric',
            'issue_date'        => 'date',
            'expiry_date'       => 'date',
            'transaction_date'  => 'date',
            'timestamp_user'    => 'numeric',

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
            'total_pages'        => 'numeric'
        ];
    }
    public function messages()
    {
        return [
            
             'document_id.numeric'         => 'error.message.number',
             'company_id.required'         => 'error.message.required',
             'company_id.numeric'          => 'error.message.number',
             'category_id.required'        => 'error.message.required',
             'category_id.numeric'         => 'error.message.number',
             'template_id.numeric'         => 'error.message.number',
             'doc_type_id.required'        => 'error.message.required',
             'doc_type_id.number'          => 'error.message.number',
             'status_id.numeric'           =>'error.message.number',
             'doc_no.string'               => 'error.message.string',
             'doc_no.max'                  => 'error.message.lenth.over',
             'product_name.string'         => 'error.message.string',
             'product_name.max'            => 'error.message.length.over',
             'title.required'              => 'error.message.required',
             'title.string'                => 'error.message.string',
             'title.max'                   => 'error.message.length.over',
             'amount.required'             => 'error.message.required',
             'amount.numeric'              => 'error.message.number',
             'currency_id.required'        => 'error.message.required',
             'currency_id.numeric'         => 'error.message.number',
             'counter_party_id.numeric'    => 'error.message.number',
             'sign_level.numeric'          => 'error.message.number',
             'cont_start_date.required'    => 'error.message.required',
             'cont_start_date.stirng'      => 'error.message.string',
             'cont_end_date.required'      => 'error.message.required',
             'cont_end_date.string'        => 'error.message.string',
             'conc_date.required'          => 'error.message.required',
             'conc_date.string'            => 'error.message.string',
             'effective_date.required'     => 'error.message.required',
             'effective_date.string'       => 'error.message.string',
             'cancel_date.required'        => 'error.message.required',
             'cancel_date.string'          => 'error.message.string',
             'expiry_date.required'        => 'error.message.required',
             'expiry_date.string'          => 'error.message.string',
             'issue_date.required'         => 'error.message.required',
             'issue_date.string'           => 'error.message.string',
             'expiry_date.required'        => 'error.message.required',
             'expiry_date.string'          => 'error.message.string',
             'payment_date.required'       => 'error.message.required',
             'payment_date.string'         => 'error.message.string',
             'transaction_date.required'   => 'error.message.required',
             'transaction_date.string'     => 'error.message.string',
             'download_date.required'      => 'error.message.required',
             'download_date.string'        => 'error.message.string',
             'doc_create_date.required'    => 'error.message.required',
             'doc_create_date.string'      => 'error.message.string',
             'sign_finish_date.required'   => 'error.message.required',
             'sign_finish_date.string'     => 'error.message.string',
             'content.required'            => 'error.message.required',
             'content.string'              => 'error.message.string',
             'scan_doc_flg.required'       => 'error.message.required',
             'scan_doc_flg.numeric'        => 'error.message.number',
             'issue_date.required'         => 'error.message.required',
             'issue_date.string'           => 'error.message.string',
             'expiry_date.required'        => 'error.message.required',
             'expiry_date.string'          => 'error.message.string',
             'transaction_date.required'   => 'error.message.required',
             'transaction_date.string'     => 'error.message.string',
             'timestamp_user.required'     => 'error.message.required',
             'timestamp_user.numeric'      => 'error.message.number',
             'file_name.max'               => 'error.message.length.over',
             'file_name.string'            => 'error.message.string',
             'file_path.max'               => 'error.message.length.over',
             'file_path.string'            => 'error.message.string',
             'file_hash.max'               => 'error.message.length.over',
             'file_hash.string'            => 'error.message.string',
             'file_prot_flg.numeric'       => 'error.message.number',
             'file_prot_pw_flg.numeric'    => 'error.message.number',
             'file_timestamp_flg.numeric'  => 'error.message.number',
             'file_sign.numeric'           => 'error.message.number',
             'width.numeric'               => 'error.message.number',
             'height.numeric'              => 'error.message.number',
             'dpi.numeric'                 => 'error.message.number',
             'color_depth.numeric'         => 'error.message.number',
             'pdf_type.string'             => 'error.message.string',
             'pdf_type.max'                => 'error.message.length.over',
             'pdf_version.string'          => 'error.message.string',
             'pdf_version.max'             => 'error.message.length.over',
             'total_pages.numeric'         => 'error.message.number',

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
