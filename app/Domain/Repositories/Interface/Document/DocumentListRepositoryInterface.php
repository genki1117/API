<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Interface\Document;

use Illuminate\Http\JsonResponse;
use App\Domain\Entities\Document\DocumentDetail;
use App\Domain\Entities\Document\DocumentDelete;

interface DocumentListRepositoryInterface
{
    /**
     * @param int $documentId
     * @param int $categoryId
     * @param int $companyId
     * @return DocumentDetail
     */
    public function getDetail(int $categoryId, int $documentId, int $companyId, int $userId): DocumentDetail;

    /**
     * @param array $importLogData
     * @return bool
     */
    public function getInsLog(array $importLogData): ?bool;

    /**
     * @param int $userId
     * @param int $companyId
     * @param int $documentId
     * @param int $updateDatetime
     * @return bool
     *
    */
    public function getDeleteContract(int $userId, int $companyId, int $documentId, int $updateDatetime): bool;

    /**
     * @param int $userId
     * @param int $companyId
     * @param int $documentId
     * @param int $updateDatetime
     * @return bool
     *
    */
    public function getDeleteDeal(int $userId, int $companyId, int $documentId, int $updateDatetime): bool;

    /**
     * @param int $userId
     * @param int $companyId
     * @param int $documentId
     * @param int $updateDatetime
     * @return bool
     *
    */
    public function getDeleteInternal(int $userId, int $companyId, int $documentId, int $updateDatetime): bool;

    /**
     * @param int $userId
     * @param int $companyId
     * @param int $documentId
     * @param int $updateDatetime
     * @return bool
     *
    */
    public function getDeleteArchive(int $userId, int $companyId, int $documentId, int $updateDatetime): bool;

    /**
     * @param int $companyId
     * @param int $documentId
     * @return DocumentDelete
     */
    public function getBeforOrAfterDeleteContract(int $companyId, int $documentId): DocumentDelete;

    /**
     * @param int $companyId
     * @param int $documentId
     * @return DocumentDelete
     */
    public function getBeforOrAfterDeleteDeal(int $companyId, int $documentId): DocumentDelete;

    /**
     * @param int $companyId
     * @param int $documentId
     * @return DocumentDelete
     */
    public function getBeforOrAfterDeleteInternal(int $companyId, int $documentId): DocumentDelete;

    /**
     * @param int $companyId
     * @param int $documentId
     * @return DocumentDelete
     */
    public function getBeforOrAfterDeleteArchive(int $companyId, int $documentId): DocumentDelete;

    /**
     * @param int|null $companyId
     * @param int|null $categoryId
     * @param int|null $userId
     * @param int|null $userType
     * @param string|null $ipAddress
     * @param int|null $documentId
     * @param string|null $accessContent
     * @param JsonResponse|null $beforeContent
     * @param JsonResponse|null $afterContet
     * @return bool
     */
    public function getDeleteLog(
        int $companyId = null,
        int $categoryId = null,
        int $userId = null,
        int $userType = null,
        string $ipAddress = null,
        int $documentId = null,
        string $accessContent = null,
        JsonResponse $beforeContent = null,
        JsonResponse $afterContet = null
    ): bool;
}
