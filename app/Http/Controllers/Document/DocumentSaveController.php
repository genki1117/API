<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\Document\DocumentSaveRequest;
use App\Domain\Services\Document\DocumentSaveService;
use App\Http\Responses\Document\DocumentSaveResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;


class DocumentSaveController extends Controller
{
    /** @var */
    protected const DOC_CONTRACT_TYPE = 0;
    /** @var */
    protected const DOC_DEAL_TYPE     = 1;
    /** @var */
    protected const DOC_INTERNAL_TYPE = 2;
    /** @var */
    protected const DOC_ARCHIVE_TYPE  = 3;

    /** @var DocumentSaveService */
    private DocumentSaveService $documentService;

    /** @param DocumentSaveService $documentService */

    public function __construct(DocumentSaveService $documentSaveService,
                                Carbon $carbon,
    )
    {
        $this->documentSaveService = $documentSaveService;
        $this->carbon          = $carbon;
    }

    /**
     * 書類保存、更新処理
     *
     * @param Request $request
     * @return DocumentSaveResponse
     */
    public function saveDocument(Request $request): JsonResponse
    {
        try {
            switch ($request->category_id) {
                case Self::DOC_CONTRACT_TYPE:
                    $requestContent['m_user_id']               = $request->m_user['user_id'];
                    $requestContent['m_user_company_id']       = $request->m_user['company_id'];
                    $requestContent['m_user_type_id']          = $request->m_user['user_type'];
                    $requestContent['document_id']             = $request->document_id ?? null;
                    $requestContent['company_id']              = $request->company_id;
                    $requestContent['category_id']             = $request->category_id;
                    $requestContent['template_id']             = $request->template_id;
                    $requestContent['doc_type_id']             = $request->doc_type_id;
                    $requestContent['status_id']               = $request->status_id;
                    $requestContent['cont_start_date']         = $request->cont_start_date ?? null;
                    $requestContent['cont_end_date']           = $request->cont_end_date ?? null;
                    $requestContent['conc_date']               = $request->conc_date ?? null;
                    $requestContent['effective_date']          = $request->effective_date ?? null;
                    $requestContent['cancel_date']             = $request->cancel_date ?? null;
                    $requestContent['expiry_date']             = $request->expiry_date ?? null;
                    $requestContent['doc_no']                  = $request->doc_no ?? null;
                    $requestContent['ref_doc_no']              = $request->input('ref_doc_no') ?? null;
                    $requestContent['product_name']            = $request->product_name ?? null;
                    $requestContent['select_sign_user']        = $request->input('select_sign_user') ?? null;
                    $requestContent['select_sign_guest_user']  = $request->input('select_sign_guest_user') ?? null;
                    $requestContent['title']                   = $request->title ?? null;
                    $requestContent['amount']                  = $request->amount ?? null;
                    $requestContent['currency_id']             = $request->currency_id ?? null;
                    $requestContent['counter_party_id']        = $request->counter_party_id ?? null;
                    $requestContent['remarks']                 = $request->remarks ?? null;
                    $requestContent['doc_info']                = $request->input('doc_info') ?? null;
                    $requestContent['sign_level']              = $request->sign_level ?? null;
                    $requestContent['upload_pdf']              = $request->input('upload_pdf') ?? null;
                    $requestContent['sign_position']           = $request->input('sign_position') ?? null;
                    $requestContent['total_pages']             = $request->total_pages ?? null;
                    $requestContent['create_user']             = $request->m_user['user_id'] ?? null;
                    $requestContent['create_datetime']         = $this->carbon->format('Y-m-d') ?? null;
                    $requestContent['update_user']             = $request->m_user['user_id'] ?? null;
                    $requestContent['update_datetime']         = $this->carbon->format('Y-m-d') ?? null;

                    // 書類の保存を実行
                    $this->documentSaveService->saveDocument($requestContent);

                    // ログの出力を実行
                    // $this->documentSaveService->getLog(

                    // );
                    break;

                case Self::DOC_DEAL_TYPE:
                    return 'test1';
                    break;

                case Self::DOC_INTERNAL_TYPE:
                    return 'test2';
                    break;

                case Self::DOC_ARCHIVE_TYPE:
                    return 'test3';
                    break;
            }
            return (new DocumentSaveResponse)->successSave();
        } catch (Exception $e) {
            // ログの出力
            return (new DocumentSaveResponse)->faildSave($e->getMessage());
        }
        

        
        // return $documentSaveResult;
        // return (new DocumentSaveResponse)->documentSaveResponse($documentSaveResult);
        // Requestクラスを直
        // $documentSaveResult = $this->documentService->saveDocument($request);
        // return (new DocumentSaveResponse)->documentSaveResponse($documentSaveResult);
    }
}
        