<?php

namespace App\Http\Controllers\Document;

use App\Domain\Services\Document\DocumentSignOrderService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
    public function save_order(Request $request)
    {
        $mUserId        = $request->m_user['user_id'];
        $mUserCompanyId = $request->m_user['company_id'];
        $mUserTypeId    = $request->m_user['user_type'];
        $documentId     = $request->document_id;
        $docTypeId      = $request->doc_type_id;
        $categoryId     = $request->category_id;
        $updateDatetime = $request->update_datetime;

        return $this->documentSignOrderService->signOrder($documentId, $docTypeId, $categoryId, $updateDatetime);
    }
}
