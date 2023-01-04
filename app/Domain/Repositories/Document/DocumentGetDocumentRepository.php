<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Document;

use App\Accessers\DB\Document\DocumentContract;
use App\Accessers\DB\Document\DocumentDeal;
use App\Accessers\DB\Document\DocumentInternal;
use App\Accessers\DB\Document\DocumentArchive;
use App\Domain\Entities\Document\DocumentGetList as DocumentGetListEntity;
use App\Domain\Repositories\Interface\Document\DocumentGetDocumentRepositoryInterface;

class DocumentGetDocumentRepository implements DocumentGetDocumentRepositoryInterface
{
    public function getContractSignUser(int $documentId, int $doctypeId, int $categoryId)
    {
        return 'test';
    }
}
