<?php

namespace App\Http\Controllers\Document;

use App\Http\Requests\Document\DocumentSignOrderRequest;
use App\Http\Responses\Document\DocumentSignOrderRespons;
use App\Domain\Services\Document\DocumentSignOrderService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Exception;

class DocumentSignOrderController extends Controller
{
    /** @var DocumentSignOrderService  */
    private DocumentSignOrderService $documentSignOrderService;

    public function __construct(DocumentSignOrderService $documentService)
    {
        $this->documentSignOrderService = $documentService;
    }

    /**
     * @param DocumentSignOrderRequest $request
     * @return JsonResponse
     */
    public function documentSignOrder(DocumentSignOrderRequest $request):JsonResponse
    {
        try {
            $mUserId        = $request->m_user['user_id'];
            $mUserCompanyId = $request->m_user['company_id'];
            $mUserTypeId    = $request->m_user['user_type'];
            $documentId     = $request->document_id;
            $docTypeId      = $request->doc_type_id;
            $categoryId     = $request->category_id;
            $updateDatetime = $request->update_datetime;
            $systemUrl      = url('');
            
            $documentSignOrderResult = $this->documentSignOrderService->signOrder(
                mUserId: $mUserId,
                mUserCompanyId: $mUserCompanyId,
                mUserTypeId: $mUserTypeId,
                documentId: $documentId,
                docTypeId: $docTypeId,
                categoryId: $categoryId,
                updateDatetime: $updateDatetime,
                systemUrl: $systemUrl
            );
            
            if ($documentSignOrderResult === false) {
                throw new Exception("common.message.permission");
            }

            return (new DocumentSignOrderRespons)->successSignOrder();
        } catch (Exception $e) {
            return (new DocumentSignOrderRespons)->faildSignOrder($e->getMessage());
        }
    }
}
