<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Interface\Document;

use Illuminate\Http\JsonResponse;
use App\Domain\Entities\Document\DocumentDetail;
use App\Domain\Entities\Document\DocumentDelete;
use App\Http\Requests\Document\DocumentDeleteRequest;

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
}
