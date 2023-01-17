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
use App\Domain\Entities\Document\DocumentSignOrder;
use App\Domain\Repositories\Interface\Document\DocumentSignOrderRepositoryInterface;
use Exception;

class DocumentSignOrderRepository implements DocumentSignOrderRepositoryInterface
{
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
     * ログインユーザのワークフローを取得
     *
     * @param integer $mUserId
     * @param integer $mUserCompanyId
     * @return stdClass|null
     */
    public function getLoginUserWorkflow(int $mUserId, int $mUserCompanyId): ?\stdClass
    {
        $loginUserWorkflow = $this->mUser->getLoginUserWorkflow(mUserId: $mUserId, mUserCompanyId: $mUserCompanyId);
        return $loginUserWorkflow;
    }


    /**
     * 署名する契約書類、次の署名者、起票者の取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $loginUserWorkFlowSort
     * @return DocumentSignOrder|null
     */
    public function getContractIsseuAndNextSignUserInfo(int $documentId, int $categoryId, int $mUserId): ?DocumentSignOrder
    {
        try {
            $signDocContract      = $this->documentContract->getSignDocument(documentId: $documentId, categoryId: $categoryId);
            // var_export($signDocContract);
            if (!$signDocContract) {
                throw new Exception("common.messate.permission");
            }

            $contractNextSignUser = $this->documentWorkFlow->getContractNextSignUser(documentId: $documentId, categoryId: $categoryId, mUserId: $mUserId);
            if (!$contractNextSignUser) {
                throw new Exception("common.messate.permission");
            }

            $contractIsseuUser    = $this->documentWorkFlow->getContractIsseuUser(documentId: $documentId, categoryId: $categoryId);
            if (!$contractIsseuUser) {
                throw new Exception("common.messate.permission");
            }

        } catch (Exception $e) {
            throw new Exception("common.messate.permission");
        }
        return new DocumentSignOrder($signDocContract, $contractNextSignUser, $contractIsseuUser);
    }


    /**
     * 署名する取引書類、次の署名者、起票者の取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $loginUserWorkFlowSort
     * @return DocumentSignOrder|null
     */
    public function getDealIsseuAndNextSignUserInfo(int $documentId, int $categoryId, int $mUserId)
    {
        try {
            $signDocDeal      = $this->documentDeal->getSignDocument(documentId: $documentId, categoryId: $categoryId);
            if (!$signDocDeal) {
                throw new Exception("common.messate.permission");
            }

            $dealNextSignUser = $this->documentWorkFlow->getDealNextSignUser(documentId: $documentId, categoryId: $categoryId, mUserId: $mUserId);
            if (!$dealNextSignUser) {
                throw new Exception("common.messate.permission");
            }

            $dealIsseuUser    = $this->documentWorkFlow->getDealIsseuUser(documentId: $documentId, categoryId: $categoryId);
            if (!$dealIsseuUser) {
                throw new Exception("common.messate.permission");
            }

        } catch (Exception $e) {
            throw new Exception("common.messate.permission");
        }
        return new DocumentSignOrder($signDocDeal, $dealNextSignUser, $dealIsseuUser);
    }


    /**
     * 署名する社内書類、署名者全員、起票者の取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $mUserCompanyId
     * @return DocumentSignOrder|null
     */
    public function getInternalSignUserListInfo(int $documentId, int $categoryId, int $mUserCompanyId): ?DocumentSignOrder
    {
        try {
            $signDocInternal      = $this->documentInternal->getSignDocument(documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId);
            if (!$signDocInternal) {
                throw new Exception("common.messate.permission");
            }

            $internalSignUserList = (object)$this->documentWorkFlow->getInternalSignUserList(documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId);
            if (count(get_object_vars($internalSignUserList)) === 0) {
                throw new Exception("common.messate.permission");
            }

            $internalIsseuUser    = $this->documentWorkFlow->getInternalIsseuUser(documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId);
            if (!$internalIsseuUser) {
                throw new Exception("common.messate.permission");
            }

        } catch (Exception $e) {
            throw $e;
        }
        return new DocumentSignOrder($signDocInternal, $internalSignUserList, $internalIsseuUser);
    }


    /**
     * 署名する登録書類、署名するホスト署名者、起票者の取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $mUserCompanyId
     * @return DocumentSignOrder|null
     */
    public function getArchiveIsseuAndNextSignUserInfo(int $documentId, int $categoryId, int $mUserCompanyId): ?DocumentSignOrder
    {
        try {
            $signDocArchive      = $this->documentArchive->getSignDocument(documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId);
            if (!$signDocArchive) {
                throw new Exception("common.messate.permission");
            }

            $archiveNextSignUser = $this->documentWorkFlow->getArchiveNextSignUser(documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId);
            if (!$archiveNextSignUser) {
                throw new Exception("common.messate.permission");
            }

            $archiveIsseuUser    = $this->documentWorkFlow->getArchiveIsseuUser(documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId);
            if (!$archiveIsseuUser) {
                throw new Exception("common.messate.permission");
            }
            
        } catch (Exception $e) {
            throw new Exception("common.messate.permission");
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
