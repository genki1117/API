<?php
declare(strict_types=1);
namespace App\Domain\Services\Document;

use App\Domain\Entities\Document\DocumentDetail;
use App\Domain\Repositories\Interface\Document\DocumentDetailRepositoryInterface;
use App\Domain\Repositories\Interface\Document\DocumentListRepositoryInterface;

/**
 * 書類詳細のビジネスロジッククラス
 */
class DocumentDetailService
{
    /** @var DocumentDetailRepositoryInterface */
    private DocumentDetailRepositoryInterface $documentRepository;

    /**
     * @param DocumentDetailRepositoryInterface $documentRepository
     */
    public function __construct(DocumentDetailRepositoryInterface $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

    /**
     * 書類詳細を取得
     * @param int $categoryId
     * @param int $documentId
     * @param int $companyId
     * @param int $userId
     * @return array
     */
    public function getDetail(int $categoryId, int $documentId, int $companyId, int $userId): array
    {
        $documentDetail = $this->documentRepository->getDetail(
            categoryId: $categoryId,
            documentId: $documentId,
            companyId: $companyId,
            userId: $userId
        );
        $accessLog = $this->documentRepository->getAccessLog(
            categoryId: $categoryId,
            documentId: $documentId,
            companyId: $companyId
        );
        $operationLog = $this->documentRepository->getOperationLog(
            categoryId: $categoryId,
            documentId: $documentId,
            companyId: $companyId
        );
        $selectSignGuestUsers = $this->documentRepository->getSelectSignGuestUsers(
            documentId: $documentId,
            categoryId: $categoryId,
            companyId: $companyId
        );
        $selectSignUsers = $this->documentRepository->getSelectSignUser(
            documentId: $documentId,
            categoryId: $categoryId,
            companyId: $companyId
        );
        $selectViewUsers = $this->documentRepository->getSelectViewUser(
            documentId: $documentId,
            categoryId: $categoryId,
            companyId: $companyId
        );

        if (is_null($documentDetail->getDocNo()) &&
            empty($accessLog) &&
            empty($operationLog) &&
            empty($selectSignGuestUsers) &&
            empty($selectSignUsers) &&
            empty($selectViewUsers)
        ) {
            return [];
        }

        return compact(
            'documentDetail',
            'accessLog',
            'operationLog',
            'selectSignGuestUsers',
            'selectViewUsers',
            'selectSignUsers'
        );
    }
}
