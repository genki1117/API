<?php
declare(strict_types=1);
namespace App\Http\Controllers\Document;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Responses\Document\DocumentGetListResponse;
use App\Http\Requests\Document\DocumentGetListRequest;
use App\Domain\Services\Document\DocumentGetListService;

class DocumentGetListController extends Controller
{
    /** @var DocumentGetListService */
    private DocumentGetListService $docGetListService;

    /** @var DocumentGetListResponse */
    private DocumentGetListResponse $docGetListResponse;

    /**
     * @param DocumentGetListService $docGetListService
     * @param DocumentGetListResponse $docGetListResponse
     */
    public function __construct(DocumentGetListService $docGetListService, DocumentGetListResponse $docGetListResponse)
    {
        $this->docGetListService = $docGetListService;
        $this->docGetListResponse = $docGetListResponse;
    }

    /**
     * @param DocumentGetListRequest $request
     * @return JsonResponse
     */
    public function getList(DocumentGetListRequest $request): JsonResponse
    {
        $docGetList = $this->docGetListService->getList($request->m_use, $request->condition, $request->sort, $request->page);
        
        if (empty($docGetList)) {
            return new JsonResponse();
        }
        
        $responseData = $this->docGetListResponse->setGetListResponse($docGetList->getList(), $docGetList->getListCount(), $request->page['disp_page'], $request->page['disp_count']);
        
        return (new DocumentGetListResponse)->getListResponse($responseData);
    }
}
