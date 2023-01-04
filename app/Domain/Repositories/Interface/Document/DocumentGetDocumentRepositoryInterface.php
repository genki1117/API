<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Interface\Document;



interface DocumentGetDocumentRepositoryInterface
{
    public function getContractSignUser(int $documentId, int $doctypeId, int $categoryId);
}
