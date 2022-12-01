<?php
declare(strict_types=1);
namespace App\Domain\Services\Document;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Domain\Entities\Document\DocumentDetail;
use App\Domain\Entities\Document\DocumentDelete;
use App\Domain\Repositories\Interface\Document\DocumentListRepositoryInterface;

class DocumentListService
{
    /** @var int */
    protected const DOC_CONTRACT_TYPE = 0;
    /** @var int */
    protected const DOC_DEAL_TYPE = 1;
    /** @var int */
    protected const DOC_INTERNAL_TYPE = 2;
    /** @var int */
    protected const DOC_ARCHIVE_TYPE = 3;

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
