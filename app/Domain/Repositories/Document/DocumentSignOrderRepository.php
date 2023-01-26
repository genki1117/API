<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Document;

use App\Accessers\DB\TempToken;
use App\Accessers\DB\Master\MUser;
use App\Accessers\DB\Document\DocumentContract;
use App\Accessers\DB\Document\DocumentDeal;
use App\Accessers\DB\Document\DocumentInternal;
use App\Accessers\DB\Document\DocumentArchive;
use App\Accessers\DB\Document\DocumentWorkFlow;
use App\Domain\Entities\Document\DocumentSignOrder;
use App\Domain\Repositories\Interface\Document\DocumentSignOrderRepositoryInterface;


class DocumentSignOrderRepository implements DocumentSignOrderRepositoryInterface
{
    /** @var TempToken $tempToken */
    private $token;

    /** @var MUser $mUser */
    private $mUser;

    /** @var DocumentContract $documentContract */
    private $documentContract;

    /** @var DocumentDeal $documentDeal */
    private $documentDeal;

    /** @var DocumentInternal $documentInternal */
    private $documentInternal;

    /** @var DocumentArchive $documentArchive */
    private $documentArchive;

    /** @var DocumentWorkFlow $documentWorkFlow */
    private $documentWorkFlow;

    public function __construct(
        TempToken $tempToken,
        MUser $mUser,
        DocumentContract $documentContract,
        DocumentDeal $documentDeal,
        DocumentInternal $documentInternal,
        DocumentArchive $documentArchive,
        DocumentWorkFlow $documentWorkFlow,
    ) {
        $this->token            = $tempToken;
        $this->mUser            = $mUser;
        $this->documentContract = $documentContract;
        $this->documentDeal     = $documentDeal;
        $this->documentInternal = $documentInternal;
        $this->documentArchive  = $documentArchive;
        $this->documentWorkFlow = $documentWorkFlow;
    }


    /**
     * 署名する契約書類、次の署名者、起票者の取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $loginUserWorkFlowSort
     * @return DocumentSignOrder
     */
    public function getContractIsseuAndNextSignUserInfo(int $documentId, int $categoryId, int $mUserId): DocumentSignOrder
    {
    
        $signDocContract      = $this->documentContract->getSignDocument(documentId: $documentId, categoryId: $categoryId);

        $contractNextSignUser = $this->documentWorkFlow->getContractNextSignUser(documentId: $documentId, categoryId: $categoryId, mUserId: $mUserId);

        $contractIsseuUser    = $this->documentWorkFlow->getContractIsseuUser(documentId: $documentId, categoryId: $categoryId);

        if (empty($signDocContract) && empty($contractNextSignUser) && empty($contractIsseuUser)) {
            return new DocumentSignOrder();
        }

        return new DocumentSignOrder($signDocContract, $contractNextSignUser, $contractIsseuUser);
    }


    /**
     * 署名する取引書類、次の署名者、起票者の取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $loginUserWorkFlowSort
     * @return DocumentSignOrder
     */
    public function getDealIsseuAndNextSignUserInfo(int $documentId, int $categoryId, int $mUserId): DocumentSignOrder
    {
        
            $signDocDeal      = $this->documentDeal->getSignDocument(documentId: $documentId, categoryId: $categoryId);

            $dealNextSignUser = $this->documentWorkFlow->getDealNextSignUser(documentId: $documentId, categoryId: $categoryId, mUserId: $mUserId);

            $dealIsseuUser    = $this->documentWorkFlow->getDealIsseuUser(documentId: $documentId, categoryId: $categoryId);

            if (empty($signDocDeal) && empty($dealNextSignUser) && empty($dealIsseuUser)) {
                return new DocumentSignOrder();
            }
            return new DocumentSignOrder($signDocDeal, $dealNextSignUser, $dealIsseuUser);
    }


    /**
     * 署名する社内書類、署名者全員、起票者の取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $mUserCompanyId
     * @return DocumentSignOrder
     */
    public function getInternalSignUserListInfo(int $documentId, int $categoryId, int $mUserCompanyId): DocumentSignOrder
    {
            $signDocInternal      = $this->documentInternal->getSignDocument(documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId);
            
            $internalSignUserList = (object)$this->documentWorkFlow->getInternalSignUserList(documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId);

            $internalIsseuUser    = $this->documentWorkFlow->getInternalIsseuUser(documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId);
           
            if (empty($signDocInternal) && count(get_object_vars($internalSignUserList)) === 0 && empty($internalIsseuUser)) {
                return new DocumentSignOrder();
            }

            return new DocumentSignOrder($signDocInternal, $internalSignUserList, $internalIsseuUser);
    }


    /**
     * 署名する登録書類、署名するホスト署名者、起票者の取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $mUserCompanyId
     * @return DocumentSignOrder
     */
    public function getArchiveIsseuAndNextSignUserInfo(int $documentId, int $categoryId, int $mUserCompanyId): DocumentSignOrder
    {

        $signDocArchive      = $this->documentArchive->getSignDocument(documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId);

        $archiveNextSignUser = $this->documentWorkFlow->getArchiveNextSignUser(documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId);

        $archiveIsseuUser    = $this->documentWorkFlow->getArchiveIsseuUser(documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId);

        if (empty($signDocArchive) && empty($archiveNextSignUser) && empty($archiveIsseuUser)) {
            return new DocumentSignOrder();
        }
            
        return new DocumentSignOrder($signDocArchive, $archiveNextSignUser, $archiveIsseuUser);
    }

    /**
     * トークン登録
     *
     * @param string $token
     * @param array $dataContent
     * @return boolean
     */
    public function insertToken(string $token, array $dataContent): bool
    {
        return $this->token->insertToken(token: $token, dataContent: $dataContent);
    }
}
