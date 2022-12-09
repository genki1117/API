<?php
declare(strict_types=1);
namespace App\Http\Requests\Document;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class DocumentGetListRequest extends FormRequest
{
    /** @var array */
    protected const table_param = [
        '0' => [
            'document_id',
            'category_id',
            'conc_date',
            'doc_type_id',
            'title',
            'amount',
            'currency_id',
            'status_id',
            'update_datetime',
            'app_status',
            'full_name',
            'company_id',
            'counter_party_name'
        ],
        '1' => [
            'document_id',
            'category_id',
            'transaction_date',
            'doc_type_id',
            'title',
            'amount',
            'currency_id',
            'status_id',
            'update_datetime',
            'app_status',
            'full_name',
            'company_id',
            'counter_party_name'
        ],
        '2' => [
            'document_id',
            'category_id',
            'doc_create_date',
            'doc_type_id',
            'title',
            'amount',
            'currency_id',
            'status_id',
            'update_datetime',
            'app_status',
            'm_user.full_name',
            'company_id',
            'counter_party_name'
        ],
        '3' => [
            'document_id',
            'category_id',
            'transaction_date',
            'doc_type_id',
            'title',
            'amount',
            'currency_id',
            'status_id',
            'update_datetime',
            'app_status',
            'full_name',
            'company_id',
            'counter_party_name'
        ]
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
            'condition.category_id' => 'required|numeric',
            'condition.search_input' => 'string',
            'condition.transaction_date.from' => 'string',
            'condition.transaction_date.to' => 'string',
            'condition.transaction_date.to' => 'after_or_equal:condition.transaction_date.from',
            'condition.status_id' => 'numeric',
            'condition.doc_type_id' => 'numeric',
            'condition.title' => 'string|max:255',
            'condition.amount.from' => 'numeric',
            'condition.amount.to' => 'numeric',
            'condition.amount.from' => 'after_or_equal:condition.amount.to',
            'condition.currency_id' => 'numeric',
            'condition.product_name' => 'string|max:255',
            'condition.document_id' => 'numeric',
            'condition.counter_party_name' => 'string|max:255',
            'condition.doc_info.from' => 'string',
            'condition.doc_info.to' => 'string',
            'condition.app_user_id' => 'numeric',
            'condition.app_user_id_guest' => 'numeric',
            'condition.view_permission_user_id' => 'numeric',
            'condition.create_datetime.from' => 'string',
            'condition.create_datetime.to' => 'string',
            'condition.create_datetime.to' => 'after_or_equal:condition.create_datetime.from',
            'condition.contract_start_date.from' => 'string',
            'condition.contract_start_date.to' => 'string',
            'condition.contract_start_date.to' => 'after_or_equal:condition.contract_start_date.from',
            'condition.contract_end_date.from' => 'string',
            'condition.contract_end_date.to' => 'string',
            'condition.contract_end_date.to' => 'after_or_equal:condition.contract_end_date.from',
            'condition.conc_date.from' => 'string',
            'condition.conc_date.to' => 'string',
            'condition.conc_date.to' => 'after_or_equal:condition.conc_date.from',
            'condition.effective_date.from' => 'string',
            'condition.effective_date.to' => 'string',
            'condition.effective_date.to' => 'after_or_equal:condition.effective_date.from',
            'condition.issue_date.from' => 'string',
            'condition.issue_date.to' => 'string',
            'condition.issue_date.to' => 'after_or_equal:condition.issue_date.from',
            'condition.expiry_date.from' => 'string',
            'condition.expiry_date.to' => 'string',
            'condition.expiry_date.to' => 'after_or_equal:condition.expiry_date.from',
            'condition.payment_date.from' => 'string',
            'condition.payment_date.to' => 'string',
            'condition.payment_date.to' => 'after_or_equal:condition.payment_date.from',
            'condition.doc_no' => 'string|max:30',
            'condition.ref_doc_no' => 'string|max:30',
            'condition.content' => 'string|max:255',
            'condition.doc_create_date.from' => 'string',
            'condition.doc_create_date.to' => 'string',
            'condition.doc_create_date.to' => 'after_or_equal:condition.doc_create_date.from',
            'condition.sign_finish_date.from' => 'string',
            'condition.sign_finish_date.to' => 'string',
            'condition.sign_finish_date.to' => 'after_or_equal:condition.sign_finish_date.from',
            'condition.scan_doc_flg' => 'numeric',
            'condition.timestamp_user' => 'numeric',
            'condition.remarks' => 'string|max:20',
            'condition.download_date.from' => 'string',
            'condition.download_date.to' => 'string',
            'condition.download_date.to' => 'after_or_equal:condition.download_date.from',
            'sort.column_name' => 'string|in:'.implode(',', self::table_param[($this->request->all())['condition']['category_id']]),
            'sort.sort_type' => 'string|in:asc, desc',
            'page.disp_page_count' => 'numeric',
            'page.disp_count' => 'numeric'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'condition.category_id.required' => 'error.message.required',
            'condition.category_id.numeric' => 'error.message.number',
            'condition.search_input.string' => 'error.message.text',
            'condition.transaction_date.from.string' => 'error.message.date',
            'condition.transaction_date.to.string' => 'error.message.date',
            'condition.transaction_date.to.after_or_equal' => 'error.message.date.fromto',
            'condition.status_id.numeric' => 'error.message.number',
            'condition.doc_type_id.numeric' => 'error.message.number',
            'condition.title.string' => 'error.message.text',
            'condition.title.max' => 'error.message.length.over',
            'condition.amount.from.numeric' => 'error.message.number',
            'condition.amount.to.numeric' => 'error.message.number',
            'condition.amount.from.after_or_equal' => 'error.message.date.fromto',
            'condition.currency_id.numeric' => 'error.message.number',
            'condition.product_name.string' => 'error.message.text',
            'condition.product_name.max' => 'error.message.length.over',
            'condition.document_id.numeric' => 'error.message.number',
            'condition.counter_party_name.string' => 'error.message.text',
            'condition.counter_party_name.max' => 'error.message.length.over',
            'condition.doc_info.from.string' => 'error.message.text',
            'condition.doc_info.to.string' => 'error.message.text',
            'condition.app_user_id.numeric' => 'error.message.number',
            'condition.app_user_id_guest.numeric' => 'error.message.number',
            'condition.view_permission_user_id.numeric' => 'error.message.number',
            'condition.create_datetime.from.string' => 'error.message.date',
            'condition.create_datetime.to.string' => 'error.message.date',
            'condition.create_datetime.to.after_or_equal' => 'error.message.date.fromto',
            'condition.contract_start_date.from.string' => 'error.message.date',
            'condition.contract_start_date.to.string' => 'error.message.date',
            'condition.contract_start_date.to.after_or_equal' => 'error.message.date.fromto',
            'condition.contract_end_date.from.string' => 'error.message.date',
            'condition.contract_end_date.to.string' => 'error.message.date',
            'condition.contract_end_date.to.after_or_equal' => 'error.message.date.fromto',
            'condition.conc_date.from.string' => 'error.message.date',
            'condition.conc_date.to.string' => 'error.message.date',
            'condition.conc_date.to.after_or_equal' => 'error.message.date.fromto',
            'condition.effective_date.from.string' => 'error.message.date',
            'condition.effective_date.to.string' => 'error.message.date',
            'condition.effective_date.to.after_or_equal' => 'error.message.date.fromto',
            'condition.issue_date.from.string' => 'error.message.date',
            'condition.issue_date.to.string' => 'error.message.date',
            'condition.issue_date.to.after_or_equal' => 'error.message.date.fromto',
            'condition.expiry_date.from.string' => 'error.message.date',
            'condition.expiry_date.to.string' => 'error.message.date',
            'condition.expiry_date.to.after_or_equal' => 'error.message.date.fromto',
            'condition.payment_date.from.string' => 'error.message.date',
            'condition.payment_date.to.string' => 'error.message.date',
            'condition.payment_date.to.after_or_equal' => 'error.message.date.fromto',
            'condition.doc_no.string' => 'error.message.text',
            'condition.doc_no.max' => 'error.message.length.over',
            'condition.ref_doc_no.string' => 'error.message.text',
            'condition.ref_doc_no.max' => 'error.message.length.over',
            'condition.content.string' => 'error.message.text',
            'condition.content.max' => 'error.message.length.over',
            'condition.doc_create_date.from.string' => 'error.message.date',
            'condition.doc_create_date.to.string' => 'error.message.date',
            'condition.doc_create_date.to.after_or_equal' => 'error.message.fromto',
            'condition.sign_finish_date.from.string' => 'error.message.date',
            'condition.sign_finish_date.to.string' => 'error.message.date',
            'condition.sign_finish_date.to.after_or_equal' => 'error.message.fromto',
            'condition.scan_doc_flg.numeric' => 'error.message.number',
            'condition.timestamp_user.numeric' => 'error.message.number',
            'condition.remarks.string' => 'error.message.string',
            'condition.remarks.max' => 'error.message.length.over',
            'condition.download_date.from.string' => 'error.message.date',
            'condition.download_date.to.string' => 'error.message.date',
            'condition.download_date.to.after_or_equal' => 'error.message.fromto',
            'sort.column_name.string' => 'error.message.text',
            'sort.column_name.in' => 'error.message.in_array',
            'sort.sort_type.string' => 'error.message.text',
            'sort.sort_type.in' => 'error.message.in_array',
            'page.disp_page_count.numeric' => 'error.message.number',
            'page.disp_count.numeric' => 'error.message.number',
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
