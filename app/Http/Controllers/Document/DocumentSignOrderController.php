<?php

namespace App\Http\Controllers\Document;

use App\Http\Requests\Document\DocumentSignOrderRequest;
use App\Http\Responses\Document\DocumentSignOrderRespons;
use App\Domain\Services\Document\DocumentSignOrderService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class DocumentSignOrderController extends Controller
{

    /** @var DocumentSignOrderService  */
    private DocumentSignOrderService $documentService;

    public function __construct (DocumentSignOrderService $documentService) {
        $this->documentSignOrderService = $documentService;
    }

    /**
     * @param Request $request
     * @return void
     */
    public function documentSignOrder(DocumentSignOrderRequest $request): JsonResponse
    {
        try {
            $mUserId        = $request->m_user['user_id'];
            $mUserCompanyId = $request->m_user['company_id'];
            $mUserTypeId    = $request->m_user['user_type'];
            $documentId     = $request->document_id;
            $docTypeId      = $request->doc_type_id;
            $categoryId     = $request->category_id;
            $updateDatetime = $request->update_datetime;

            $documentSignOrderResult = $this->documentSignOrderService->signOrder($mUserId, $mUserCompanyId, $mUserTypeId, $documentId, $docTypeId, $categoryId, $updateDatetime);

            if ($documentSignOrderResult === false) {
                throw new Exception ("署名依頼に失敗しました。");
            }

            return (new DocumentSignOrderRespons)->successSignOrder();

        } catch (Exception $e) {

            return (new DocumentSignOrderRespons)->faildSignOrder($e->getMessage());
        }

        
    }
}
