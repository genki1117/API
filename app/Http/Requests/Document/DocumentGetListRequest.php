<?php
declare(strict_types=1);
namespace App\Http\Requests\Document;

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
            'condition.amount.to' => 'after_or_equal:condition.amount.from',
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
            'condition.category_id.required' => '書類カテゴリIDは必須入力項目です。',
            'condition.category_id.numeric' => '書類カテゴリIDには数値のみ入力してください。',
            'condition.search_input.string' => '検索入力欄には文字のみ入力してください。',
            'condition.transaction_date.from.string' => '取引年月日には日付を入力してください。',
            'condition.transaction_date.to.string' => '取引年月日には日付を入力してください。',
            'condition.transaction_date.to.after_or_equal' => '取引年月日の終了日付には、開始日付より未来日付を指定してください。',
            'condition.status_id.numeric' => 'ステータスには数値のみ入力してください。',
            'condition.doc_type_id.numeric' => '書類の種類には数値のみ入力してください。',
            'condition.title.string' => '書類のタイトルには文字のみ入力してください。',
            'condition.title.max' => '書類のタイトルには255文字以下で入力してください。',
            'condition.amount.from.numeric' => '金額には数値のみ入力してください。',
            'condition.amount.to.numeric' => '金額には数値のみ入力してください。',
            'condition.amount.to.after_or_equal' => '金額終了には、開始金額より未来金額を指定してください。',
            'condition.currency_id.numeric' => '通貨には数値のみ入力してください。',
            'condition.product_name.string' => '商品名には文字のみ入力してください。',
            'condition.product_name.max' => '商品名には255文字以下で入力してください。',
            'condition.document_id.numeric' => '書類IDには数値のみ入力してください。',
            'condition.counter_party_name.string' => '契約相手先名には文字のみ入力してください。',
            'condition.counter_party_name.max' => '契約相手先名には255文字以下で入力してください。',
            'condition.doc_info.string' => '摘要には文字のみ入力してください。',
            'condition.app_user_id.numeric' => '署名者には数値のみ入力してください。',
            'condition.app_user_id_guest.numeric' => '相手先署名者には数値のみ入力してください。',
            'condition.view_permission_user_id.numeric' => '閲覧者には数値のみ入力してください。',
            'condition.create_datetime.from.string' => '作成日には日付を入力してください。',
            'condition.create_datetime.to.string' => '作成日には日付を入力してください。',
            'condition.create_datetime.to.after_or_equal' => '作成日の終了日付には、開始日付より未来日付を指定してください。',
            'condition.contract_start_date.from.string' => '契約開始日には日付を入力してください。',
            'condition.contract_start_date.to.string' => '契約開始日には日付を入力してください。',
            'condition.contract_start_date.to.after_or_equal' => '契約開始日の終了日付には、開始日付より未来日付を指定してください。',
            'condition.contract_end_date.from.string' => '契約終了日には日付を入力してください。',
            'condition.contract_end_date.to.string' => '契約終了日には日付を入力してください。',
            'condition.contract_end_date.to.after_or_equal' => '契約終了日の終了日付には、開始日付より未来日付を指定してください。',
            'condition.conc_date.from.string' => '契約締結日には日付を入力してください。',
            'condition.conc_date.to.string' => '契約締結日には日付を入力してください。',
            'condition.conc_date.to.after_or_equal' => '契約締結日の終了日付には、開始日付より未来日付を指定してください。',
            'condition.effective_date.from.string' => '効力発生日には日付を入力してください。',
            'condition.effective_date.to.string' => '効力発生日には日付を入力してください。',
            'condition.effective_date.to.after_or_equal' => '効力発生日の終了日付には、開始日付より未来日付を指定してください。',
            'condition.issue_date.from.string' => '発行日には日付を入力してください。',
            'condition.issue_date.to.string' => '発行日には日付を入力してください。',
            'condition.issue_date.to.after_or_equal' => '発行日の終了日付には、開始日付より未来日付を指定してください。',
            'condition.expiry_date.from.string' => '有効期限には日付を入力してください。',
            'condition.expiry_date.to.string' => '有効期限には日付を入力してください。',
            'condition.expiry_date.to.after_or_equal' => '有効期限の終了日付には、開始日付より未来日付を指定してください。',
            'condition.payment_date.from.string' => '支払期日には日付を入力してください。',
            'condition.payment_date.to.string' => '支払期日には日付を入力してください。',
            'condition.payment_date.to.after_or_equal' => '支払期日の終了日付には、開始日付より未来日付を指定してください。',
            'condition.doc_no.string' => '書類番号には文字のみ入力してください。',
            'condition.doc_no.max' => '書類番号には30文字以下で入力してください。',
            'condition.ref_doc_no.string' => '参照書類番号には文字のみ入力してください。',
            'condition.ref_doc_no.max' => '参照書類番号には30文字以下で入力してください。',
            'condition.content.string' => '内容には文字のみ入力してください。',
            'condition.content.max' => '内容には255文字以下で入力してください。',
            'condition.doc_create_date.from.string' => '書類作成日には日付を入力してください。',
            'condition.doc_create_date.to.string' => '書類作成日には日付を入力してください。',
            'condition.doc_create_date.to.after_or_equal' => '書類作成日の終了日付には、開始日付より未来日付を指定してください。',
            'condition.sign_finish_date.from.string' => '署名完了日には日付を入力してください。',
            'condition.sign_finish_date.to.string' => '署名完了日には日付を入力してください。',
            'condition.sign_finish_date.to.after_or_equal' => '署名完了日の終了日付には、開始日付より未来日付を指定してください。',
            'condition.scan_doc_flg.numeric' => '保存要件には数値のみ入力してください。',
            'condition.timestamp_user.numeric' => 'タイムスタンプ付与者には数値のみ入力してください。',
            'condition.remarks.string' => '備考には文字のみ入力してください。',
            'condition.remarks.max' => '備考には20文字以下で入力してください。',
            'condition.download_date.from.string' => 'ダウンロード日には日付を入力してください。',
            'condition.download_date.to.string' => 'ダウンロード日には日付を入力してください。',
            'condition.download_date.to.after_or_equal' => 'ダウンロード日の終了日付には、開始日付より未来日付を指定してください。',
            'sort.column_name.string' => 'ソート項目には文字のみ入力してください。',
            'sort.column_name.in' => 'ソート項目には{1}の値を指定してください。',
            'sort.sort_type.string' => '並び順には文字のみ入力してください。',
            'sort.sort_type.in' => '並び順には{1}の値を指定してください。',
            'page.disp_page_count.numeric' => '表示ページ数には数値のみ入力してください。',
            'page.disp_count.numeric' => '表示件数には数値のみ入力してください。',
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
