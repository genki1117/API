<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Document;

use App\Accessers\DB\TempToken;
use App\Accessers\DB\Master\MUser;
use App\Accessers\DB\Master\MUserRoleTest;
use App\Accessers\DB\Document\DocumentContract;
use App\Accessers\DB\Document\DocumentDeal;
use App\Accessers\DB\Document\DocumentInternal;
use App\Accessers\DB\Document\DocumentArchive;
use App\Accessers\DB\Document\DocumentWorkFlow;
use App\Domain\Entities\Document\DocumentSaveOrder;
use App\Domain\Repositories\Interface\Document\DocumentSignOrderRepositoryInterface;

class DocumentSaveOrderRepository implements DocumentSignOrderRepositoryInterface
{

    public function __construct(
        TempToken $tempToken,
        MUser $mUser,
        DocumentContract $documentContract,
        DocumentDeal $documentDeal,
        DocumentInternal $documentInternal,
        DocumentArchive $documentArchive,
        DocumentWorkFlow $documentWorkFlow,
        )
    {
        $this->token            = $tempToken;
        $this->mUser            = $mUser;
        $this->documentContract = $documentContract;
        $this->documentDeal     = $documentDeal;
        $this->documentInternal = $documentInternal;
        $this->documentArchive  = $documentArchive;
        $this->documentWorkFlow = $documentWorkFlow;
    }

    /**
     * ログインユーザのワークフローを取得
     *
     * @param integer $mUserId
     * @param integer $mUserCompanyId
     * @return stdClass|null
     */
    public function getLoginUserWorkflow (int $mUserId, int $mUserCompanyId): ?\stdClass
    {
        return $loginUserWorkflow = $this->mUser->getLoginUserWorkflow(mUserId: $mUserId, mUserCompanyId: $mUserCompanyId);  
    }


    /**
     * 署名する契約書類、次の署名者、起票者の取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $loginUserWorkFlowSort
     * @return DocumentSaveOrder|null
     */
    public function getContractIsseuAndNextSignUserInfo(int $documentId, int $categoryId, int $loginUserWorkFlowSort): ?DocumentSaveOrder
    {
        $signDocContract      = $this->documentContract->getSignDocument(documentId: $documentId, categoryId: $categoryId);
        $contractNextSignUser = $this->documentWorkFlow->getContractNextSignUser(documentId: $documentId, categoryId: $categoryId, loginUserWorkFlowSort: $loginUserWorkFlowSort);
        $contractIsseuUser    = $this->documentWorkFlow->getContractIsseuUser(documentId: $documentId, categoryId: $categoryId);
        return new DocumentSaveOrder($signDocContract, $contractNextSignUser, $contractIsseuUser);
    }


    /**
     * 署名する取引書類、次の署名者、起票者の取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $loginUserWorkFlowSort
     * @return DocumentSaveOrder|null
     */
    public function getDealIsseuAndNextSignUserInfo(int $documentId, int $categoryId, int $loginUserWorkFlowSort): ?DocumentSaveOrder
    {
        $signDocDeal      = $this->documentDeal->getSignDocument(documentId: $documentId, categoryId: $categoryId);
        $dealNextSignUser = $this->documentWorkFlow->getDealNextSignUser(documentId: $documentId, categoryId: $categoryId, loginUserWorkFlowSort: $loginUserWorkFlowSort);
        $dealIsseuUser    = $this->documentWorkFlow->getDealIsseuUser(documentId: $documentId, categoryId: $categoryId);
        return new DocumentSaveOrder($signDocDeal, $dealNextSignUser, $dealIsseuUser);
    }


    /**
     * 署名する社内書類、署名者全員、起票者の取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $mUserCompanyId
     * @return DocumentSaveOrder|null
     */
    public function getInternalSignUserListInfo(int $documentId, int $categoryId, int $mUserCompanyId): ?DocumentSaveOrder
    {
        $signDocInternal      = $this->documentInternal->getSignDocument(documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId);
        $internalSignUserList = (object)$this->documentWorkFlow->getInternalSignUserList(documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId);
        $internalIsseuUser    = $this->documentWorkFlow->getInternalIsseuUser(documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId);
        return new DocumentSaveOrder($signDocInternal, $internalSignUserList, $internalIsseuUser);
    }


    /**
     * 署名する登録書類、署名するホスト署名者、起票者の取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $mUserCompanyId
     * @return DocumentSaveOrder|null
     */
    public function getArchiveIsseuAndNextSignUserInfo(int $documentId, int $categoryId, int $mUserCompanyId): ?DocumentSaveOrder
    {
        $signDocArchive      = $this->documentArchive->getSignDocument(documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId);
        $archiveNextSignUser = $this->documentWorkFlow->getArchiveNextSignUser(documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId);
        $archiveIsseuUser    = $this->documentWorkFlow->getArchiveIsseuUser(documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId);
        return new DocumentSaveOrder($signDocArchive, $archiveNextSignUser, $archiveIsseuUser);
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
