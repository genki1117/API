<?php
declare(strict_types=1);
namespace App\Http\Controllers\Document;

use App\Domain\Consts\DocumentConst;
use App\Domain\Services\Document\DocumentDetailService;
use App\Http\Requests\Document\DocumentGetDetailRequest;
use App\Http\Responses\Document\DocumentGetDetailResponse;
use Illuminate\Http\JsonResponse;

/**
 * 書類詳細制御
 */
class DocumentDetailController
{
    /** @var DocumentDetailService 書類詳細ビジネスロジッククラス */
    private DocumentDetailService $documentService;

    /**
     * @param DocumentDetailService $documentService
     */
    public function __construct(DocumentDetailService $documentService)
    {
        $this->documentService = $documentService;
    }

    /**
     * 書類詳細取得
     * @param DocumentGetDetailRequest $request
     * @return JsonResponse
     */
    public function getDetail(DocumentGetDetailRequest $request): JsonResponse
    {
        $request = $request->all();
        $categoryId = $request['category_id'];
        $documentId = $request['document_id'];
        $companyId = 1; //TODO 認証基盤実装がされた場合にLoggedInUserContextから取得する
        $userId = 1;    //TODO 認証基盤実装がされた場合にLoggedInUserContextから取得する

        // データを抽出
        $data = $this->documentService->getDetail(
            categoryId: $categoryId,
            documentId: $documentId,
            companyId: $companyId,
            userId: $userId
        );

        // 書類詳細が存在しない場合
        if (empty($data)) {
            return (new DocumentGetDetailResponse)->notFound();
        }

        [$documentDetail, $accessLog, $operationLog, $selectSignGuestUsers, $selectViewUsers, $selectSignUsers] = $data;

        $categoryId = $documentDetail->getCategoryId();
        if (DocumentConst::DOCUMENT_CONTRACT=== $categoryId) {
            return (new DocumentGetDetailResponse)->emitContract(
                document: $documentDetail,
                selectSignUser: $selectSignUsers,
                selectSignGuestUser: $selectSignGuestUsers,
                selectViewUser: $selectViewUsers,
                operationData: $operationLog,
                accessData: $accessLog
            );
        }

        if (DocumentConst::DOCUMENT_DEAL === $categoryId) {
            return (new DocumentGetDetailResponse)->emitDeal(
                document: $documentDetail,
                selectSignUser: $selectSignUsers,
                selectSignGuestUser: $selectSignGuestUsers,
                selectViewUser: $selectViewUsers,
                operationData: $operationLog,
                accessData: $accessLog
            );
        }

        if (DocumentConst::DOCUMENT_INTERNAL === $categoryId) {
            return (new DocumentGetDetailResponse)->emitInternal(
                document: $documentDetail,
                selectSignUser: $selectSignUsers,
                selectSignGuestUser: $selectSignGuestUsers,
                selectViewUser: $selectViewUsers,
                operationData: $operationLog,
                accessData: $accessLog
            );
        }

        if (DocumentConst::DOCUMENT_ARCHIVE === $categoryId) {
            return (new DocumentGetDetailResponse)->emitArchive(
                document: $documentDetail,
                selectSignUser: $selectSignUsers,
                selectSignGuestUser: $selectSignGuestUsers,
                selectViewUser: $selectViewUsers,
                operationData: $operationLog,
                accessData: $accessLog
            );
        }

        return (new DocumentGetDetailResponse)->notFound();
    }
}
