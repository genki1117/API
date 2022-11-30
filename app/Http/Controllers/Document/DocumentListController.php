<?php
declare(strict_types=1);
namespace App\Http\Controllers\Document;

use App\Http\Responses\Document\DocumentDeleteResponse;
use App\Http\Requests\Document\DocumentDeleteRequest;
use App\Http\Responses\Document\DocumentGetDetailResponse;
use App\Http\Requests\Document\DocumentGetDetailRequest;
use App\Domain\Services\Document\DocumentListService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Exception;

class DocumentListController extends Controller
{
    /** @var DocumentListService */
    private DocumentListService $documentService;

    /** @param DocumentListService $documentService */
    public function __construct(DocumentListService $documentService)
    {
        $this->documentService = $documentService;
    }

    /**
     * @param DocumentGetDetailRequest $request
     * @return JsonResponse
     */
    public function getDetail(DocumentGetDetailRequest $request): JsonResponse
    {
        $categoryId = $request->category_id;
        $documentId = $request->document_id;
        $companyId = $request->company_id;
        $userId = $request->user_id;

        // データを抽出
        $docDetailList = $this->documentService->getDetail($categoryId, $documentId, $companyId, $userId);

        // Responseの格納、JSON形式
        return (new DocumentGetDetailResponse)->detail($categoryId, $docDetailList);
    }

    /**
     * @param DocumentDeleteRequest $request
     * @return JsonResponse
     */
    public function delete(DocumentDeleteRequest $request): JsonResponse
    {
        try {
            $updateTime = $request->update_datetime;
            $categoryId = $request->category_id;
            $documentId = $request->document_id;
            $companyId = $request->m_user['company_id'];
            $userId = $request->m_user['user_id'];
            $userType = $request->m_user['user_type'];
            $ipAddress = $request->ip;
            $accessContent = $request->access_content;
    
            $beforeList = $this->documentService->getBeforOrAfterDeleteData($categoryId, $companyId, $documentId);
            $this->documentService->getDelete($categoryId, $userId, $companyId, $documentId, $updateTime);
            $afterList = $this->documentService->getBeforOrAfterDeleteData($categoryId, $companyId, $documentId);
    
            $beforeDocContract = $beforeList->getDeleteDocument();
            $beforeContent = (new DocumentDeleteResponse)->beforeContent($beforeDocContract);
            $afterDocContract = $afterList->getDeleteDocument();
            $afterContet = (new DocumentDeleteResponse)->afterContent($afterDocContract);

            $this->documentService->getLog(
                $companyId,
                $categoryId,
                $userId,
                $userType,
                $ipAddress,
                $documentId,
                $accessContent,
                $beforeContent,
                $afterContet
            );
    
            return (new DocumentDeleteResponse)->successDelete();
        } catch(Exception $e) {
            return (new DocumentDeleteResponse)->faildDelete($e->getMessage());
        }
    }

}
