<?php
declare(strict_types=1);
namespace App\Domain\Services\Document;

use App\Domain\Entities\Document\DocumentDetail;
use App\Domain\Repositories\Interface\Document\DocumentListRepositoryInterface;

class DocumentListService
{
    /** @var DocumentListRepositoryInterface */
    private DocumentListRepositoryInterface $documentRepository;

    /**
     * @param DocumentListRepositoryInterface $documentRepository
     */
    public function __construct(DocumentListRepositoryInterface $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

    /**
     * @param int $categoryId
     * @param int $documentId
     * @param int $companyId
     * @return DocumentDetail|null
     */
    public function getDetail(int $categoryId, int $documentId, int $companyId, int $userId): ?DocumentDetail
    {
        $documentDetail= $this->documentRepository->getDetail($categoryId, $documentId, $companyId, $userId);
        if (is_null($documentDetail->getDocumentList()) &&
            is_null($documentDetail->getDocumentPermissionList()) &&
            is_null($documentDetail->getDocumentWorkFlow()) &&
            is_null($documentDetail->getLogDocAccess()) &&
            is_null($documentDetail->getLogDocOperation())) {
            return null;
        }
        return $documentDetail;
    }

    /**
     * @param array $importLogData
     * @return bool
     */
    public function getInsLog(array $importLogData): ?bool
    {
        $blInsLog = $this->documentRepository->getInsLog($importLogData);
        return $blInsLog;
    }
}
