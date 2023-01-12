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
                throw new Exception("契約書類の署名依頼は失敗しました");
            }
            $contractNextSignUser = $this->documentWorkFlow->getContractNextSignUser(documentId: $documentId, categoryId: $categoryId, mUserId: $mUserId);
            if (!$contractNextSignUser) {
                throw new Exception("契約書類の署名依頼は失敗しました");
            }
            $contractIsseuUser    = $this->documentWorkFlow->getContractIsseuUser(documentId: $documentId, categoryId: $categoryId);
            if (!$contractIsseuUser) {
                throw new Exception("契約書類の署名依頼は失敗しました");
            }
        } catch (Exception $e) {
            throw new Exception("契約書類の署名依頼は失敗しました");
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
                throw new Exception("取引書類の署名依頼は失敗しました");
            }
            $dealNextSignUser = $this->documentWorkFlow->getDealNextSignUser(documentId: $documentId, categoryId: $categoryId, mUserId: $mUserId);
            if (!$dealNextSignUser) {
                throw new Exception("取引書類の署名依頼は失敗しました");
            }
            $dealIsseuUser    = $this->documentWorkFlow->getDealIsseuUser(documentId: $documentId, categoryId: $categoryId);
            if (!$dealIsseuUser) {
                throw new Exception("取引書類の署名依頼は失敗しました");
            }
        } catch (Exception $e) {
            throw new Exception("取引書類の署名依頼は失敗しました");
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
                throw new Exception("社内書類の署名依頼は失敗しました");
            }

            $internalSignUserList = (object)$this->documentWorkFlow->getInternalSignUserList(documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId);
            if (!$internalSignUserList) {
                throw new Exception("社内書類の署名依頼は失敗しました");
            }
            $internalIsseuUser    = $this->documentWorkFlow->getInternalIsseuUser(documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId);
            if (!$internalIsseuUser) {
                throw new Exception("社内書類の署名依頼は失敗しました");
            }
        } catch (Exception $e) {
            throw new Exception("社内書類の署名依頼は失敗しました");
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
                throw new Exception("登録書類の署名依頼は失敗しました");
            }
            $archiveNextSignUser = $this->documentWorkFlow->getArchiveNextSignUser(documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId);
            if (!$archiveNextSignUser) {
                throw new Exception("登録書類の署名依頼は失敗しました");
            }
            $archiveIsseuUser    = $this->documentWorkFlow->getArchiveIsseuUser(documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId);
            if (!$archiveIsseuUser) {
                throw new Exception("登録書類の署名依頼は失敗しました");
            }
        } catch (Exception $e) {
            throw new Exception("登録書類の署名依頼は失敗しました");
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
