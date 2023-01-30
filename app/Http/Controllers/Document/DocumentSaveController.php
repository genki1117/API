<?php

namespace App\Http\Controllers\Document;

use App\Domain\Consts\DocumentConst;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\DocumentSaveRequest;
use App\Domain\Services\Document\DocumentSaveService;
use App\Http\Responses\Document\DocumentSaveResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class DocumentSaveController extends Controller
{
    /** @var DocumentConst */
    private DocumentConst $docConst;

    /** @var DocumentSaveService */
    private DocumentSaveService $documentSaveService;

    /**
     * @param DocumentSaveService $documentSaveService
     * @param Carbon $carbon
     * @param DocumentConst $docConst
     */
    public function __construct(
        DocumentSaveService $documentSaveService,
        DocumentConst $docConst
    ) {
        $this->documentSaveService = $documentSaveService;
        $this->docConst            = $docConst;
    }

    /**
     * 書類保存、更新処理
     *
     * @param Request $request
     * @return DocumentSaveResponse
     */
    
    public function saveDocument(DocumentSaveRequest $request): JsonResponse
    {
        DB::beginTransaction($request);
        try {
            $request = $request->all();
            switch ($request['data']['category_id']) {
                // 契約書類処理
                case $this->docConst::DOCUMENT_CONTRACT:
                    $requestContent['m_user_id']               = $request['m_user']['user_id'];
                    $requestContent['m_user_company_id']       = $request['m_user']['company_id'];
                    $requestContent['m_user_type_id']          = $request['m_user']['user_type'];
                    $requestContent['ip_address']              = $request['m_user']['ip_address'];
                    $requestContent['document_id']             = $request['data']['document_id'] ?? null;
                    $requestContent['company_id']              = $request['data']['company_id'];
                    $requestContent['category_id']             = $request['data']['category_id'];
                    $requestContent['template_id']             = $request['data']['template_id'];
                    $requestContent['doc_type_id']             = $request['data']['doc_type_id'];
                    $requestContent['status_id']               = $request['data']['status_id'];
                    $requestContent['cont_start_date']         = $request['data']['cont_start_date'] ?? null;
                    $requestContent['cont_end_date']           = $request['data']['cont_end_date'] ?? null;
                    $requestContent['conc_date']               = $request['data']['conc_date'] ?? null;
                    $requestContent['effective_date']          = $request['data']['effective_date'] ?? null;
                    $requestContent['cancel_date']             = $request['data']['cancel_date'] ?? null;
                    $requestContent['expiry_date']             = $request['data']['expiry_date'] ?? null;
                    $requestContent['doc_no']                  = $request['data']['doc_no'] ?? null;
                    $requestContent['ref_doc_no']              = $request['data']['ref_doc_no'] ?? null;
                    $requestContent['product_name']            = $request['data']['product_name'] ?? null;
                    $requestContent['select_sign_user']        = $request['data']['select_sign_user'] ?? null;
                    $requestContent['select_sign_guest_user']  = $request['data']['select_sign_guest_user'] ?? null;
                    $requestContent['title']                   = $request['data']['title'] ?? null;
                    $requestContent['amount']                  = $request['data']['amount'] ?? null;
                    $requestContent['currency_id']             = $request['data']['currency_id'] ?? null;
                    $requestContent['counter_party_id']        = $request['data']['counter_party_id'] ?? null;
                    $requestContent['remarks']                 = $request['data']['remarks'] ?? null;
                    $requestContent['doc_info']                = $request['data']['doc_info'] ?? null;
                    $requestContent['sign_level']              = $request['data']['sign_level'] ?? null;
                    $requestContent['upload_pdf']              = $request['data']['upload_pdf'] ?? null;
                    $requestContent['sign_position']           = $request['data']['sign_position'] ?? null;
                    $requestContent['access_content']          = $request['data']['access_content'];
                    $requestContent['total_pages']             = $request['data']['total_pages'] ?? null;
                    $requestContent['create_user']             = $request['m_user']['user_id'] ?? null;
                    $requestContent['create_datetime']         = date('Y-m-d H:i:s', $request['data']['create_datetime']);
                    $requestContent['update_user']             = $request['m_user']['user_id'] ?? null;
                    $requestContent['update_datetime']         = date('Y-m-d H:i:s', $request['data']['update_datetime']);

                    // 書類の保存を実行
                    $this->documentSaveService->saveDocument($requestContent);
                    break;

                case $this->docConst::DOCUMENT_DEAL:
                    // 取引書類処理
                    $requestContent['m_user_id']               = $request['m_user']['user_id'];
                    $requestContent['m_user_company_id']       = $request['m_user']['company_id'];
                    $requestContent['m_user_type_id']          = $request['m_user']['user_type'];
                    $requestContent['ip_address']              = $request['m_user']['ip_address'];
                    $requestContent['document_id']             = $request['data']['document_id'] ?? null;
                    $requestContent['company_id']              = $request['data']['company_id'];
                    $requestContent['category_id']             = $request['data']['category_id'];
                    $requestContent['template_id']             = $request['data']['template_id'];
                    $requestContent['doc_type_id']             = $request['data']['doc_type_id'];
                    $requestContent['status_id']               = $request['data']['status_id'];
                    $requestContent['issue_date']              = $request['data']['issue_date'] ?? null;
                    $requestContent['expiry_date']             = $request['data']['expiry_date'] ?? null;
                    $requestContent['payment_date']            = $request['data']['payment_date'] ?? null;
                    $requestContent['transaction_date']        = $request['data']['transaction_date'] ?? null;
                    $requestContent['download_date']           = $request['data']['download_date'] ?? null;
                    $requestContent['doc_no']                  = $request['data']['doc_no'] ?? null;
                    $requestContent['ref_doc_no']              = $request['data']['ref_doc_no'] ?? null;
                    $requestContent['product_name']            = $request['data']['product_name'] ?? null;
                    $requestContent['select_sign_user']        = $request['data']['select_sign_user'] ?? null;
                    $requestContent['select_sign_guest_user']  = $request['data']['select_sign_guest_user'] ?? null;
                    $requestContent['title']                   = $request['data']['title'] ?? null;
                    $requestContent['amount']                  = $request['data']['amount'] ?? null;
                    $requestContent['currency_id']             = $request['data']['currency_id'] ?? null;
                    $requestContent['counter_party_id']        = $request['data']['counter_party_id'] ?? null;
                    $requestContent['remarks']                 = $request['data']['remarks'] ?? null;
                    $requestContent['doc_info']                = $request['data']['doc_info'] ?? null;
                    $requestContent['sign_level']              = $request['data']['sign_level'] ?? null;
                    $requestContent['upload_pdf']              = $request['data']['upload_pdf'] ?? null;
                    $requestContent['sign_position']           = $request['data']['sign_position'] ?? null;
                    $requestContent['access_content']          = $request['data']['access_content'];
                    $requestContent['total_pages']             = $request['data']['total_pages'] ?? null;
                    $requestContent['create_user']             = $request['m_user']['user_id'] ?? null;
                    $requestContent['create_datetime']         = date('Y-m-d H:i:s', $request['data']['create_datetime']);
                    $requestContent['update_user']             = $request['m_user']['user_id'] ?? null;
                    $requestContent['update_datetime']         = date('Y-m-d H:i:s', $request['data']['update_datetime']);

                    // 書類保存の実行
                    $this->documentSaveService->saveDocument($requestContent);
                    break;

                case $this->docConst::DOCUMENT_INTERNAL:
                    // 社内書類処理
                    $requestContent['m_user_id']               = $request['m_user']['user_id'];
                    $requestContent['m_user_company_id']       = $request['m_user']['company_id'];
                    $requestContent['m_user_type_id']          = $request['m_user']['user_type'];
                    $requestContent['ip_address']              = $request['m_user']['ip_address'];
                    $requestContent['access_content']          = $request['data']['access_content'];
                    $requestContent['document_id']             = $request['data']['document_id'] ?? null;
                    $requestContent['company_id']              = $request['data']['company_id'];
                    $requestContent['category_id']             = $request['data']['category_id'];
                    $requestContent['template_id']             = $request['data']['template_id'];
                    $requestContent['doc_type_id']             = $request['data']['doc_type_id'];
                    $requestContent['status_id']               = $request['data']['status_id'];
                    $requestContent['doc_create_date']         = $request['data']['doc_create_date'] ?? null;
                    $requestContent['sign_finish_date']        = $request['data']['sign_finish_date'] ?? null;
                    $requestContent['doc_no']                  = $request['data']['doc_no'] ?? null;
                    $requestContent['ref_doc_no']              = $request['data']['ref_doc_no'] ?? null;
                    $requestContent['product_name']            = $request['data']['product_name'] ?? null;
                    $requestContent['select_sign_user']        = $request['data']['select_sign_user'] ?? null;
                    $requestContent['title']                   = $request['data']['title'] ?? null;
                    $requestContent['amount']                  = $request['data']['amount'] ?? null;
                    $requestContent['currency_id']             = $request['data']['currency_id'] ?? null;
                    $requestContent['counter_party_id']        = $request['data']['counter_party_id'] ?? null;
                    $requestContent['content']                 = $request['data']['content'] ?? null;
                    $requestContent['remarks']                 = $request['data']['remarks'] ?? null;
                    $requestContent['doc_info']                = $request['data']['doc_info'] ?? null;
                    $requestContent['sign_level']              = $request['data']['sign_level'] ?? null;
                    $requestContent['upload_pdf']              = $request['data']['upload_pdf'] ?? null;
                    $requestContent['sign_position']           = $request['data']['sign_position'] ?? null;
                    $requestContent['total_pages']             = $request['data']['total_pages'] ?? null;
                    $requestContent['create_user']             = $request['m_user']['user_id'] ?? null;
                    $requestContent['create_datetime']         = date('Y-m-d H:i:s', $request['data']['create_datetime']);
                    $requestContent['update_user']             = $request['m_user']['user_id'] ?? null;
                    $requestContent['update_datetime']         = date('Y-m-d H:i:s', $request['data']['update_datetime']);

                    // 書類保存の実行
                    $this->documentSaveService->saveDocument($requestContent);
                    break;

                case $this->docConst::DOCUMENT_ARCHIVE:
                    // 登録書類処理
                    $requestContent['m_user_id']               = $request['m_user']['user_id'];
                    $requestContent['m_user_company_id']       = $request['m_user']['company_id'];
                    $requestContent['m_user_type_id']          = $request['m_user']['user_type'];
                    $requestContent['ip_address']              = $request['m_user']['ip_address'];
                    $requestContent['access_content']          = $request['data']['access_content'];
                    $requestContent['document_id']             = $request['data']['document_id'] ?? null;
                    $requestContent['company_id']              = $request['data']['company_id'];
                    $requestContent['category_id']             = $request['data']['category_id'];
                    $requestContent['template_id']             = $request['data']['template_id'];
                    $requestContent['doc_type_id']             = $request['data']['doc_type_id'];
                    $requestContent['scan_doc_flg']            = $request['data']['scan_doc_flg'];
                    $requestContent['status_id']               = $request['data']['status_id'];
                    $requestContent['issue_date']              = $request['data']['issue_date'];
                    $requestContent['expiry_date']             = $request['data']['expiry_date'] ?? null;
                    $requestContent['transaction_date']        = $request['data']['transaction_date'] ?? null;
                    $requestContent['doc_no']                  = $request['data']['doc_no'] ?? null;
                    $requestContent['ref_doc_no']              = $request['data']['ref_doc_no'] ?? null;
                    $requestContent['product_name']            = $request['data']['product_name'] ?? null;
                    $requestContent['select_sign_user']        = $request['data']['select_sign_user'] ?? null;
                    $requestContent['title']                   = $request['data']['title'] ?? null;
                    $requestContent['amount']                  = $request['data']['amount'] ?? null;
                    $requestContent['currency_id']             = $request['data']['currency_id'] ?? null;
                    $requestContent['counter_party_id']        = $request['data']['counter_party_id'] ?? null;
                    $requestContent['content']                 = $request['data']['content'] ?? null;
                    $requestContent['remarks']                 = $request['data']['remarks'] ?? null;
                    $requestContent['doc_info']                = $request['data']['doc_info'] ?? null;
                    $requestContent['sign_level']              = $request['data']['sign_level'] ?? null;
                    $requestContent['upload_pdf']              = $request['data']['upload_pdf'] ?? null;
                    $requestContent['sign_position']           = $request['data']['sign_position'] ?? null;
                    $requestContent['total_pages']             = $request['data']['total_pages'] ?? null;
                    $requestContent['timestamp_user']          = $request['data']['timestamp_user'] ?? null;
                    $requestContent['create_user']             = $request['m_user']['user_id'] ?? null;
                    $requestContent['create_datetime']         = date('Y-m-d H:i:s', $request['data']['create_datetime']);
                    $requestContent['update_user']             = $request['m_user']['user_id'] ?? null;
                    $requestContent['update_datetime']         = date('Y-m-d H:i:s', $request['data']['update_datetime']);

                    // 書類保存の実行
                    $this->documentSaveService->saveDocument($requestContent);
                    break;
            }
            DB::commit();
            return (new DocumentSaveResponse)->successSave();
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            return (new DocumentSaveResponse)->faildSave($e->getMessage());
        }
    }
}
