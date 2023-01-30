<?php

namespace App\Http\Requests\Document;

use Illuminate\Validation\ValidationException;

use App\Libraries\ResponseClient;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class DocumentSaveRequest extends FormRequest
{

    use ResponseClient;

    
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
            'data.document_id'       => 'numeric',
            'data.company_id'        => 'required | numeric',
            'data.category_id'       => 'required | numeric',
            'data.template_id'       => 'numeric',
            'data.doc_type_id'       => 'required | numeric',
            'data.status_id'         => 'numeric',
            'data.doc_no'            => 'string | max:20',
            'data.product_name'      => 'string | max:255',
            'data.title'             => 'required | string | max:100',
            'data.amount'            => 'required | numeric',
            'data.currency_id'       => 'required | numeric',
            'data.counter_party_id'  => 'numeric',
            'data.sign_level'        => 'numeric',

            // 契約書類
            'data.cont_start_date'   => 'date',
            'data.cont_end_date'     => 'date',
            'data.conc_date'         => 'date',
            'data.effective_date'    => 'date',
            'data.cancel_date'       => 'date',
            'data.expiry_date'       => 'date',
            
            // 取引書類
            'data.issue_date'        => 'date',
            'data.payment_date'      => 'date',
            'data.transaction_date'  => 'date',
            'data.download_date'     => 'date',

            // 社内書類
            'data.doc_create_date'   => 'date',
            'data.sign_finish_date'  => 'date',
            'data.content'           => 'string',

            // 登録書類
            'data.scan_doc_flg'      => 'numeric',
            'data.timestamp_user'    => 'numeric',
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
     * @return array
     */
    private function errorsTable():array
    {
        return [
            'document_id' => [
                "Numeric" =>  [
                    ["type" => "label", "content" => "common.label.item.document.id"],
                ],
            ],
            'company_id' => [
                "Required" =>  [
                    ["type" => "label", "content" => "common.label.item.company.id"],
                ],
                "Numeric" =>  [
                    ["type" => "label", "content" => "common.label.item.company.id"],
                ]
            ],
            'category_id' => [
                "Required" =>  [
                    ["type" => "label", "content" => "common.label.item.company.id"],
                ],
                "Numeric" =>  [
                    ["type" => "label", "content" => "common.label.item.company.id"],
                ]
            ],
            'template_id' => [
                "Numeric" =>  [
                    ["type" => "label", "content" => "admin-menu.label.template"],
                ]
            ],
            'doc_type_id' => [
                "Required" =>  [
                    ["type" => "label", "content" => "document.label.item.document-type"],
                ],
                "Numeric" =>  [
                    ["type" => "label", "content" => "document.label.item.document-type"],
                ]
            ],
            'status_id' => [
                "Numeric" =>  [
                    ["type" => "label", "content" => "document.label.item.status"],
                ]
            ],
            'doc_no' => [
                "String" =>  [
                    ["type" => "label", "content" => "document.label.item.document-number"],
                ],
                "Max" =>  [
                    ["type" => "label", "content" => "document.label.item.document-number"],
                    ["type" => "text", "content" => "20"],
                ],
            ],
            'product_name' => [
                "String" =>  [
                    ["type" => "label", "content" => "document.label.item.product-name"],
                ],
                "Max" =>  [
                    ["type" => "label", "content" => "document.label.item.product-name"],
                    ["type" => "text", "content" => "20"],
                ],
            ],
            'title' => [
                "Required" =>  [
                    ["type" => "label", "content" => "document.label.item.title"],
                ],
                "String" =>  [
                    ["type" => "label", "content" => "document.label.item.title"],
                ],
                "Max" =>  [
                    ["type" => "label", "content" => "document.label.item.title"],
                    ["type" => "text", "content" => "20"],
                ],
            ],
            'amount' => [
                "Required" =>  [
                    ["type" => "label", "content" => "document.label.item.amount"],
                ],
                "String" =>  [
                    ["type" => "label", "content" => "document.label.item.amount"],
                ]
            ],
            'currency_id' => [
                "Required" =>  [
                    ["type" => "label", "content" => "document.label.item.currency"],
                ],
                "Numeric" =>  [
                    ["type" => "label", "content" => "document.label.item.currency"],
                ]
            ],
            'counte_party_id' => [
                "Required" =>  [
                    ["type" => "label", "content" => "document.label.item.currency"],
                ],
                "Numeric" =>  [
                    ["type" => "label", "content" => "document.label.item.currency"],
                ]
            ],
            // TODO:[署名レベル]ラベル無し
            'sign_level' => [
                "Numeric" =>  [
                    ["type" => "label", "content" => "document.label.item.currency"],
                ]
            ],
            'cont_start_date' => [
                "Date" =>  [
                    ["type" => "label", "content" => "company.label.item.contract.start-date"],
                ]
            ],
            'cont_end_date' => [
                "Date" =>  [
                    ["type" => "label", "content" => "company.label.item.contract.end-date"],
                ]
            ],
            'conc_date' => [
                "Date" =>  [
                    ["type" => "label", "content" => "document.label.item.conclusion-date"],
                ]
            ],
            'effective_date' => [
                "Date" =>  [
                    ["type" => "label", "content" => "document.label.item.effective-date"],
                ]
            ],
            'cancel_date' => [
                "Date" =>  [
                    ["type" => "label", "content" => "document.label.item.cancel-date"],
                ]
            ],
            'expiry_date' => [
                "Date" =>  [
                    ["type" => "label", "content" => "document.label.item.expiry-date"],
                ]
            ],
            'issue_date' => [
                "Date" =>  [
                    ["type" => "label", "content" => "document.label.item.issue-date"],
                ]
            ],
            'payment_date' => [
                "Date" =>  [
                    ["type" => "label", "content" => "document.label.item.payment-date"],
                ]
            ],
            'transaction_date' => [
                "Date" =>  [
                    ["type" => "label", "content" => "document.label.item.deal-date"],
                ]
            ],
            'download_date' => [
                "Date" =>  [
                    ["type" => "label", "content" => "document.label.item.download-date"],
                ]
            ],
            'doc_create_date' => [
                "Date" =>  [
                    ["type" => "label", "content" => "document.label.item.document-create-date"],
                ]
            ],
            'sign_finish_date' => [
                "Date" =>  [
                    ["type" => "label", "content" => "document.label.item.sign-finish-date"],
                ]
            ],
            'content' => [
                "Date" =>  [
                    ["type" => "label", "content" => "document.label.item.add-info.content"],
                ]
            ],
            'scan_doc_flg' => [
                "Numeric" =>  [
                    ["type" => "label", "content" => "document.label.item.scanned-document"],
                ]
            ],
            'scan_doc_flg' => [
                "Numeric" =>  [
                    ["type" => "label", "content" => "common.label.add-timestamp"],
                ]
            ],
            'file_name' => [
                "Numeric" =>  [
                    ["type" => "label", "content" => "common.label.add-timestamp"],
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
