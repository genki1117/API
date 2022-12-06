<?php
declare(strict_types=1);
namespace App\Http\Requests\Document;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class DocumentGetListRequest extends FormRequest
{
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
            "condition.category_id" => "required|numeric",
            "condition.search_input" => "string",
            "condition.transaction_date.*" => "string",
            "condition.transaction_date.to" => "after_or_equal:condition.transaction_date.from",
            "condition.status_id" => "numeric",
            "condition.doc_type_id" => "numeric",
            "condition.title" => "string|max:255",
            "condition.amount.*" => "numeric",
            "condition.amount.to" => "after_or_equal:condition.amount.from",
            "condition.currency_id" => "numeric",
            "condition.product_name" => "string|max:255",
            "condition.document_id" => "numeric",
            "condition.counter_party_name" => "string|max:255",
            "condition.doc_info.*" => "string",
            "condition.app_user_id" => "numeric",
            "condition.app_user_id_guest" => "numeric",
            "condition.view_permission_user_id" => "numeric",
            "condition.create_datetime.*" => "string",
            "condition.create_datetime.to" => "after_or_equal:condition.create_datetime.from",
            "condition.contract_start_date.*" => "string",
            "condition.contract_start_date.to" => "after_or_equal:condition.contract_start_date.from",
            "condition.contract_end_date.*" => "string",
            "condition.contract_end_date.to" => "after_or_equal:condition.contract_end_date.from",
            "condition.conc_date.*" => "string",
            "condition.conc_date.to" => "after_or_equal:condition.conc_date.from",
            "condition.effective_date.*" => "string",
            "condition.effective_date.to" => "after_or_equal:condition.effective_date.from",
            "condition.issue_date.*" => "string",
            "condition.issue_date.to" => "after_or_equal:condition.issue_date.from",
            "condition.expiry_date.*" => "string",
            "condition.expiry_date.to" => "after_or_equal:condition.expiry_date.from",
            "condition.payment_date.*" => "string",
            "condition.payment_date.to" => "after_or_equal:condition.payment_date.from",
            "condition.doc_no" => "string|max:30",
            "condition.ref_doc_no" => "string|max:30",
            "condition.content" => "string|max:255",
            "condition.doc_create_date.*" => "string",
            "condition.doc_create_date.to" => "after_or_equal:condition.doc_create_date.from",
            "condition.sign_finish_date.*" => "string",
            "condition.sign_finish_date.to" => "after_or_equal:condition.sign_finish_date.from",
            "condition.scan_doc_flg" => "numeric",
            "condition.timestamp_user" => "numeric",
            "condition.remarks" => "string|max:20",
            "condition.download_date.*" => "string",
            "condition.download_date.to" => "after_or_equal:condition.download_date.from",
            "sort.column_name" => "string|in:".($this->request->all())['sort']['column_name'],
            "sort.sort_type" => "string|in:".($this->request->all())['sort']['sort_type'],
            "page.disp_page_count" => "numeric",
            "page.disp_count" => "numeric"
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            "condition.category_id.required" => "書類カテゴリIDは必須入力項目です。",
            "condition.category_id.numeric" => "書類カテゴリIDには数値のみ入力してください。",
            "condition.search_input.string" => "検索入力欄には文字のみ入力してください。",
            "condition.transaction_date.string" => "取引年月日には日付を入力してください。",
            "condition.transaction_date.after_or_equal" => "{0}の終了日付には、開始日付より未来日付を指定してください。",
            "condition.status_id.numeric" => "ステータスには数値のみ入力してください。",
            "condition.doc_type_id.numeric" => "書類の種類には数値のみ入力してください。",
            "condition.title.string" => "書類のタイトルには文字のみ入力してください。",
            "condition.title.max" => "書類のタイトルには255文字以下で入力してください。",
            "condition.amount.numeric" => "金額には数値のみ入力してください。",
            "condition.amount.after_or_equal" => "{0}の終了金額には、開始金額より未来金額を指定してください。",
            "condition.currency_id.numeric" => "通貨には数値のみ入力してください。",
            "condition.product_name.string" => "商品名には文字のみ入力してください。",
            "condition.product_name.max" => "商品名には255文字以下で入力してください。",
            "condition.document_id.numeric" => "書類IDには数値のみ入力してください。",
            "condition.counter_party_name.string" => "契約相手先名には文字のみ入力してください。",
            "condition.counter_party_name.max" => "契約相手先名には255文字以下で入力してください。",
            "condition.doc_info.string" => "摘要には文字のみ入力してください。",
            "condition.app_user_id.numeric" => "署名者には数値のみ入力してください。",
            "condition.app_user_id_guest.numeric" => "相手先署名者には数値のみ入力してください。",
            "condition.view_permission_user_id.numeric" => "閲覧者には数値のみ入力してください。",
            "condition.create_datetime.string" => "作成日には日付を入力してください。",
            "condition.create_datetime.after_or_equal" => "{0}の終了日付には、開始日付より未来日付を指定してください。",
            "condition.contract_start_date.string" => "契約開始日には日付を入力してください。",
            "condition.contract_start_date.after_or_equal" => "{0}の終了日付には、開始日付より未来日付を指定してください。",
            "condition.contract_end_date.string" => "契約終了日には日付を入力してください。",
            "condition.contract_end_date.after_or_equal" => "{0}の終了日付には、開始日付より未来日付を指定してください。",
            "condition.conc_date.string" => "契約締結日には日付を入力してください。",
            "condition.conc_date.after_or_equal" => "{0}の終了日付には、開始日付より未来日付を指定してください。",
            "condition.effective_date.string" => "効力発生日には日付を入力してください。",
            "condition.effective_date.after_or_equal" => "{0}の終了日付には、開始日付より未来日付を指定してください。",
            "condition.issue_date.string" => "発行日には日付を入力してください。",
            "condition.issue_date.after_or_equal" => "{0}の終了日付には、開始日付より未来日付を指定してください。",
            "condition.expiry_date.string" => "有効期限には日付を入力してください。",
            "condition.expiry_date.after_or_equal" => "{0}の終了日付には、開始日付より未来日付を指定してください。",
            "condition.payment_date.string" => "支払期日には日付を入力してください。",
            "condition.payment_date.after_or_equal" => "{0}の終了日付には、開始日付より未来日付を指定してください。",
            "condition.doc_no.string" => "書類番号には文字のみ入力してください。",
            "condition.doc_no.max" => "書類番号には30文字以下で入力してください。",
            "condition.ref_doc_no.string" => "参照書類番号には文字のみ入力してください。",
            "condition.ref_doc_no.max" => "参照書類番号には30文字以下で入力してください。",
            "condition.content.string" => "内容には文字のみ入力してください。",
            "condition.content.max" => "内容には255文字以下で入力してください。",
            "condition.doc_create_date.string" => "書類作成日には日付を入力してください。",
            "condition.doc_create_date.after_or_equal" => "{0}の終了日付には、開始日付より未来日付を指定してください。",
            "condition.sign_finish_date.string" => "署名完了日には日付を入力してください。",
            "condition.sign_finish_date.after_or_equal" => "{0}の終了日付には、開始日付より未来日付を指定してください。",
            "condition.scan_doc_flg.numeric" => "保存要件には数値のみ入力してください。",
            "condition.timestamp_user.numeric" => "タイムスタンプ付与者には数値のみ入力してください。",
            "condition.remarks.string" => "備考には文字のみ入力してください。",
            "condition.remarks.max" => "備考には20文字以下で入力してください。",
            "condition.download_date.string" => "ダウンロード日には日付を入力してください。",
            "condition.download_date.after_or_equal" => "{0}の終了日付には、開始日付より未来日付を指定してください。",
            "sort.column_name.string" => "ソート項目には文字のみ入力してください。",
            "sort.column_name.in" => "{0}の終了日付には、開始日付より未来日付を指定してください。",
            "sort.sort_type.string" => "並び順には文字のみ入力してください。",
            "sort.sort_type.in" => "{0}の終了日付には、開始日付より未来日付を指定してください。",
            "page.disp_page_count.numeric" => "表示ページ数には数値のみ入力してください。",
            "page.disp_count.numeric" => "表示件数には数値のみ入力してください。",
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
