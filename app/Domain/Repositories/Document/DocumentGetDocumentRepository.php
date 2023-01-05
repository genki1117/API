<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Document;

use App\Accessers\DB\Master\MUser;
use App\Accessers\DB\Document\DocumentContract;
use App\Accessers\DB\Document\DocumentDeal;
use App\Accessers\DB\Document\DocumentInternal;
use App\Accessers\DB\Document\DocumentArchive;
use App\Accessers\DB\Document\DocumentWorkFlow;
// use App\Domain\Entities\Document\DocumentGetList as DocumentGetListEntity;
use App\Domain\Repositories\Interface\Document\DocumentGetDocumentRepositoryInterface;

class DocumentGetDocumentRepository implements DocumentGetDocumentRepositoryInterface
{

    public function __construct(
        MUser $mUser,
        DocumentContract $documentContract,
        DocumentDeal $documentDeal,
        DocumentInternal $documentInternal,
        DocumentArchive $documentArchive,
        DocumentWorkFlow $documentworkFlow,
        )
    {
        $this->mUser = $mUser;
        $this->documentContract = $documentContract;
        $this->documentDeal     = $documentDeal;
        $this->documentInternal = $documentInternal;
        $this->documentArchive  = $documentArchive;
        $this->documentWorkFlow = $documentWorkFlow;
    }

    public function getLoginUser (int $mUserId, int $mUserCompanyId)
    {
        $this->mUser->getLoginUser($mUserId, $mUserCompanyId);
        // return ;
    }
    public function getContractSignUser(int $documentId, int $doctypeId, int $categoryId)
    {
        $this->documentWorkFlow->getSignUser();
    }
}
