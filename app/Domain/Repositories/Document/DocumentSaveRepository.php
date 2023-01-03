<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Document;

use App\Accessers\DB\Log\System\LogDocAccess;
use App\Accessers\DB\Log\System\LogDocOperation;
use App\Accessers\DB\Document\DocumentContract;
use App\Accessers\DB\Document\DocumentArchive;
use App\Accessers\DB\Document\DocumentInternal;
use App\Accessers\DB\Document\DocumentDeal;
use App\Accessers\DB\Document\DocumentPermissionArchive;
use App\Accessers\DB\Document\DocumentPermissionInternal;
use App\Accessers\DB\Document\DocumentPermissionTransaction;
use App\Accessers\DB\Document\DocumentPermissionContract;
use App\Accessers\DB\Document\DocumentStorageContract;
use App\Accessers\DB\Document\DocumentStorageTransaction;
use App\Accessers\DB\Document\DocumentStorageInternal;
use App\Accessers\DB\Document\DocumentStorageArchive;
use App\Accessers\DB\Document\DocumentWorkFlow;
use App\Domain\Repositories\Interface\Document\DocumentSaveRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Domain\Entities\Document\DocumentUpdate as DocumentUpdateEntity;

// use App\Domain\Entities\Document\DocumentDetail as DocumentEntity;

class DocumentSaveRepository implements DocumentSaveRepositoryInterface
{
    /** @var */
    protected const ISSUE_USER_WF_SORT   = 0; // 起票者のワークフローソート
    /** @var */
    protected const CONTRACT_INSERT_ERROR_MESSAGE   = '契約書類テーブルおよび契約書類閲覧権限および契約書類容量を登録出来ません。';
    /** @var */
    protected const CONTRACT_UPDATE_ERROR_MESSAGE   = '契約書類テーブルおよび契約書類閲覧権限および契約書類容量を更新出来ません。';
    /** @var */
    protected const DEAL_INSERT_ERROR_MESSAGE       = '取引書類テーブルおよび取引書類閲覧権限および取引書類容量を登録出来ません。';
    /** @var */
    protected const DEAL_UPDATE_ERROR_MESSAGE       = '取引書類テーブルおよび取引書類閲覧権限および取引書類容量を更新出来ません。';
    /** @var */
    protected const INTERNAL_INSERT_ERROR_MESSAGE   = '社内書類テーブルおよび社内書類閲覧権限および社内書類容量を登録出来ません。';
    /** @var */
    protected const INTERNAL_UPDATE_ERROR_MESSAGE   = '社内書類テーブルおよび社内書類閲覧権限および社内書類容量を更新出来ません。';
    /** @var */
    protected const ARCHIVE_INSERT_ERROR_MESSAGE    = '登録書類テーブルおよび登録書類閲覧権限および登録書類容量を登録出来ません。';
    /** @var */
    protected const ARCHIVE_UPDATE_ERROR_MESSAGE    = '登録書類テーブルおよび登録書類閲覧権限および登録書類容量を更新出来ません。';
    /** @var */
    protected const LOG_ERROR_MESSAGE               = 'ログを出力、登録できません';

    /**
     * @var Document
     */
    private DocumentContract $docContract;
    private DocumentDeal $docDeal;
    private DocumentInternal $docInternal;
    private DocumentArchive $docArchive;
    private DocumentPermissionContract $docPermissionContract;
    private DocumentPermissionTransaction $docPermissionTransaction;
    private DocumentPermissionInternal $docPermissionInternal;
    private DocumentPermissionArchive $docPermissionArchive;
    private DocumentStorageContract $docStorageContract;
    private DocumentStorageTransaction $docStorageTransaction;
    private DocumentStorageInternal $docStorageInternal;
    private DocumentStorageArchive $docStorageArchive;
    private DocumentWorkFlow $documentWorkFlow;
    private LogDocAccess $logDocAccess;
    private LogDocOperation $logDocOperation;


    /**
     * @param DocumentContract $docContract
     * @param DocumentDeal $docDeal
     * @param DocumentInternal $docInternal
     * @param DocumentArchive $docArchive
     * @param DocumentPermissionContract $docPermissionContract
     * @param DocumentPermissionTransaction $docPermissionTransaction
     * @param DocumentPermissionInternal $docPermissionInternal
     * @param DocumentPermissionArchive $docPermissionArchive
     * @param DocumentStorageContract $docStorageContract
     * @param DocumentStorageTransaction $docStorageTransaction
     * @param DocumentStorageInternal $docStorageInternal
     * @param DocumentStorageArchive $docStorageArchive
     * @param DocumentWorkFlow $documentWorkFlow
     * @param LogDocAccess $logDocAccess
     * @param LogDocOperation $logDocOperation;
     */

    public function __construct(
        DocumentContract $docContract,
        DocumentDeal $docDeal,
        DocumentInternal $docInternal,
        DocumentArchive $docArchive,
        DocumentPermissionContract $docPermissionContract,
        DocumentPermissionTransaction $docPermissionTransaction,
        DocumentPermissionInternal $docPermissionInternal,
        DocumentPermissionArchive $docPermissionArchive,
        DocumentStorageContract $docStorageContract,
        DocumentStorageTransaction $docStorageTransaction,
        DocumentStorageInternal $docStorageInternal,
        DocumentStorageArchive $docStorageArchive,
        DocumentWorkFlow $documentWorkFlow,
        LogDocAccess $logDocAccess,
        LogDocOperation $logDocOperation
    ) {
        $this->docContract              = $docContract;
        $this->docDeal                  = $docDeal;
        $this->docInternal              = $docInternal;
        $this->docArchive               = $docArchive;
        $this->docPermissionContract    = $docPermissionContract;
        $this->docPermissionTransaction = $docPermissionTransaction;
        $this->docPermissionInternal    = $docPermissionInternal;
        $this->docPermissionArchive     = $docPermissionArchive;
        $this->docStorageContract       = $docStorageContract;
        $this->docStorageTransaction    = $docStorageTransaction;
        $this->docStorageInternal       = $docStorageInternal;
        $this->docStorageArchive        = $docStorageArchive;
        $this->documentWorkFlow         = $documentWorkFlow;
        $this->logDocAccess             = $logDocAccess;
        $this->logDocOperation          = $logDocOperation;
    }

    /**
     * -------------------------
     * 契約書類新規登録
     * -------------------------
     *
     * @param array $requestContent
     * @return boolean
     */
    public function contractInsert(array $requestContent)
    {
        // 契約書類登録
        $docInsertResult           = $this->docContract->insert($requestContent);

        // 契約書類閲覧権限登録
        $docPermissionInsertResult = $this->docPermissionContract->insert($requestContent) ;

        // 契約書類容量登録
        $docStorageInsertResult    = $this->docStorageContract->insert($requestContent);

        // ワークフローテーブル登録
        // ワークフローが起票者のみ
        if (count($requestContent['select_sign_user']) === 1) {
            $companyId  = $requestContent['company_id'];
            $categoryId = $requestContent['category_id'];
            $appUserId  = $requestContent['select_sign_user'][0]['user_id'];
            $wfSort     = Self::ISSUE_USER_WF_SORT;
            $userId     = $requestContent['m_user_id'];
            $createDate = $requestContent['create_datetime'];

            $documentWorkFlowResult = $this->documentWorkFlow->insertContract($companyId, $categoryId, $appUserId, $wfSort, $userId, $createDate);
            if (!$documentWorkFlowResult) {
                throw new Exception(Self::CONTRACT_INSERT_ERROR_MESSAGE);
                exit;
            }

        // ゲスト署名者が未入力の場合
        } elseif ($requestContent['select_sign_guest_user'] === null) {
            $selectSignUserList = $requestContent['select_sign_user'];
            foreach ($selectSignUserList as $wf_sort => $selectSignUser) {
                $companyId              = $requestContent['company_id'];
                $categoryId             = $requestContent['category_id'];
                $appUserId              = $selectSignUser['user_id'];
                // $wfSort                 = $wf_sort * 10; //連番に変更
                $wfSort                 = $selectSignUser['wf_sort'];
                $userId                 = $requestContent['m_user_id'];
                $createDate             = $requestContent['create_datetime'];

                $documentWorkFlowResult = $this->documentWorkFlow->insertContract($companyId, $categoryId, $appUserId, $wfSort, $userId, $createDate);
                if (!$documentWorkFlowResult) {
                    throw new Exception(Self::CONTRACT_INSERT_ERROR_MESSAGE);
                    exit;
                }
            }
        } else {
            $selectSignUserList = array_merge($requestContent['select_sign_user'], $requestContent['select_sign_guest_user']);
            foreach ($selectSignUserList as $wf_sort => $selectSignUser) {
                $companyId              = $requestContent['company_id'];
                $categoryId             = $requestContent['category_id'];
                $appUserId              = $selectSignUser['user_id'];
                $wfSort                 = $wf_sort * 10; //連番に変更
                $userId                 = $requestContent['m_user_id'];
                $createDate             = $requestContent['create_datetime'];

                $documentWorkFlowResult = $this->documentWorkFlow->insertContract($companyId, $categoryId, $appUserId, $wfSort, $userId, $createDate);
                if (!$documentWorkFlowResult) {
                    throw new Exception(Self::CONTRACT_INSERT_ERROR_MESSAGE);
                    exit;
                }
            }
        }
        if ($docInsertResult === false || $docPermissionInsertResult === false || $docStorageInsertResult === false || $documentWorkFlowResult === false) {
            throw new Exception(Self::CONTRACT_INSERT_ERROR_MESSAGE);
        }

        return true;
    }
    /**
     * -------------------------
     * 契約書類更新
     * -------------------------
     *
     * @param array $requestContent
     * @return boolean
     */
    public function contractUpdate(array $requestContent)
    {
        // 契約書類更新
        $docUpdateResult           = $this->docContract->update($requestContent);

        // 契約書類閲覧権限更新
        $docPermissionUpdateResult = $this->docPermissionContract->update($requestContent);

        // 契約書類容量更新
        $docStorageUpdateResult    = $this->docStorageContract->update($requestContent);


        if (!$docUpdateResult === 1 || !$docPermissionUpdateResult === 1 || !$docStorageUpdateResult === 1) {
            throw new Exception(Self::CONTRACT_UPDATE_ERROR_MESSAGE);
        }
        return true;
    }


    /**
     * -------------------------
     * 取引書類登録
     * -------------------------
     *
     * @param array $requestContent
     * @return boolean
     */
    public function dealInsert(array $requestContent)
    {
        // 取引書類登録
        $docInsertResult           = $this->docDeal->insert($requestContent);

        // 取引書類閲覧権限登録
        $docPermissionInsertResult = $this->docPermissionTransaction->insert($requestContent) ;

        // 取引書類容量登録
        $docStorageInsertResult    = $this->docStorageTransaction->insert($requestContent);

        // ワークフローテーブル登録
        // ワークフローが起票者のみ
        if (count($requestContent['select_sign_user']) === 1) {
            $companyId  = $requestContent['company_id'];
            $categoryId = $requestContent['category_id'];
            $appUserId  = $requestContent['select_sign_user'][0]['user_id'];
            $wfSort     = Self::ISSUE_USER_WF_SORT;
            $userId     = $requestContent['m_user_id'];
            $createDate = $requestContent['create_datetime'];

            $documentWorkFlowResult = $this->documentWorkFlow->insertDeal($companyId, $categoryId, $appUserId, $wfSort, $userId, $createDate);
            if (!$documentWorkFlowResult) {
                throw new Exception(Self::DEAL_INSERT_ERROR_MESSAGE);
                exit;
            }

        // ゲスト署名者が未入力の場合
        } elseif ($requestContent['select_sign_guest_user'] === null) {
            $selectSignUserList = $requestContent['select_sign_user'];
            foreach ($selectSignUserList as $wf_sort => $selectSignUser) {
                $companyId              = $requestContent['company_id'];
                $categoryId             = $requestContent['category_id'];
                $appUserId              = $selectSignUser['user_id'];
                $wfSort                 = $selectSignUser['wf_sort'];
                $userId                 = $requestContent['m_user_id'];
                $createDate             = $requestContent['create_datetime'];

                $documentWorkFlowResult = $this->documentWorkFlow->insertDeal($companyId, $categoryId, $appUserId, $wfSort, $userId, $createDate);
                if (!$documentWorkFlowResult) {
                    throw new Exception(Self::DEAL_INSERT_ERROR_MESSAGE);
                    exit;
                }
            }
        } else {
            $selectSignUserList = array_merge($requestContent['select_sign_user'], $requestContent['select_sign_guest_user']);
            foreach ($selectSignUserList as $wf_sort => $selectSignUser) {
                $companyId              = $requestContent['company_id'];
                $categoryId             = $requestContent['category_id'];
                $appUserId              = $selectSignUser['user_id'];
                $wfSort                 = $selectSignUser['wf_sort'];
                $userId                 = $requestContent['m_user_id'];
                $createDate             = $requestContent['create_datetime'];

                $documentWorkFlowResult = $this->documentWorkFlow->insertDeal($companyId, $categoryId, $appUserId, $wfSort, $userId, $createDate);
                if (!$documentWorkFlowResult) {
                    throw new Exception(Self::DEAL_INSERT_ERROR_MESSAGE);
                    exit;
                }
            }
        }
        if ($docInsertResult === false || $docPermissionInsertResult === false || $docStorageInsertResult === false || $documentWorkFlowResult === false) {
            throw new Exception(Self::DEAL_INSERT_ERROR_MESSAGE);
        }
        return true;
    }
    /**
     * -------------------------
     * 取引書類更新
     * -------------------------
     *
     * @param array $requestContent
     * @return boolean
     */
    public function dealUpdate(array $requestContent)
    {
        // 取引書類更新
        $docUpdateResult           = $this->docDeal->update($requestContent);

        // 取引書類閲覧権限更新
        $docPermissionUpdateResult = $this->docPermissionTransaction->update($requestContent);

        // 取引書類容量更新
        $docStorageUpdateResult    = $this->docStorageTransaction->update($requestContent);

        if (!$docUpdateResult === 1 || !$docPermissionUpdateResult === 1 || !$docStorageUpdateResult === 1) {
            throw new Exception(Self::DEAL_UPDATE_ERROR_MESSAGE);
        }
        return true;
    }

    /**
     * -------------------------
     * 社内書類登録
     * -------------------------
     *
     * @param array $requestContent
     * @return boolean
     */
    public function internalInsert(array $requestContent)
    {
        // 社内書類登録
        $docInsertResult           = $this->docInternal->insert($requestContent);

        // 社内書類閲覧権限登録
        $docPermissionInsertResult = $this->docPermissionInternal->insert($requestContent) ;

        // 社内書類容量登録
        $docStorageInsertResult    = $this->docStorageInternal->insert($requestContent);

        // ワークフローテーブル登録
        // ワークフローが起票者のみ
        if (count($requestContent['select_sign_user']) === 1) {
            $companyId  = $requestContent['company_id'];
            $categoryId = $requestContent['category_id'];
            $appUserId  = $requestContent['select_sign_user'][0]['user_id'];
            $wfSort     = Self::ISSUE_USER_WF_SORT;
            $userId     = $requestContent['m_user_id'];
            $createDate = $requestContent['create_datetime'];

            $documentWorkFlowResult = $this->documentWorkFlow->insertInternal($companyId, $categoryId, $appUserId, $wfSort, $userId, $createDate);
            if (!$documentWorkFlowResult) {
                throw new Exception(Self::INTERNAL_INSERT_ERROR_MESSAGE);
                exit;
            }

        // ゲスト署名者が未入力の場合
        } elseif ($requestContent['select_sign_guest_user'] === null) {
            $selectSignUserList = $requestContent['select_sign_user'];
            foreach ($selectSignUserList as $wf_sort => $selectSignUser) {
                $companyId              = $requestContent['company_id'];
                $categoryId             = $requestContent['category_id'];
                $appUserId              = $selectSignUser['user_id'];
                $wfSort                 = $selectSignUser['wf_sort'];
                $userId                 = $requestContent['m_user_id'];
                $createDate             = $requestContent['create_datetime'];

                $documentWorkFlowResult = $this->documentWorkFlow->insertInternal($companyId, $categoryId, $appUserId, $wfSort, $userId, $createDate);
                if (!$documentWorkFlowResult) {
                    throw new Exception(Self::INTERNAL_INSERT_ERROR_MESSAGE);
                    exit;
                }
            }
        } else {
            $selectSignUserList = array_merge($requestContent['select_sign_user'], $requestContent['select_sign_guest_user']);
            foreach ($selectSignUserList as $wf_sort => $selectSignUser) {
                $companyId              = $requestContent['company_id'];
                $categoryId             = $requestContent['category_id'];
                $appUserId              = $selectSignUser['user_id'];
                $wfSort                 = $selectSignUser['wf_sort'];
                $userId                 = $requestContent['m_user_id'];
                $createDate             = $requestContent['create_datetime'];

                $documentWorkFlowResult = $this->documentWorkFlow->insertInternal($companyId, $categoryId, $appUserId, $wfSort, $userId, $createDate);
                if (!$documentWorkFlowResult) {
                    throw new Exception(Self::INTERNAL_INSERT_ERROR_MESSAGE);
                    exit;
                }
            }
        }
        if ($docInsertResult === false || $docPermissionInsertResult === false || $docStorageInsertResult === false || $documentWorkFlowResult === false) {
            throw new Exception(Self::INTERNAL_INSERT_ERROR_MESSAGE);
        }
        return true;
    }
    /**
     * -------------------------
     * 社内書類更新
     * -------------------------
     *
     * @param array $requestContent
     * @return boolean
     */
    public function InternalUpdate(array $requestContent)
    {
        // 社内書類更新
        $docUpdateResult           = $this->docInternal->update($requestContent);

        // 社内書類閲覧権限更新
        $docPermissionUpdateResult = $this->docPermissionInternal->update($requestContent);

        // 社内書類容量更新
        $docStorageUpdateResult    = $this->docStorageInternal->update($requestContent);

        if (!$docUpdateResult === 1 || !$docPermissionUpdateResult === 1 || !$docStorageUpdateResult === 1) {
            throw new Exception(Self::INTERNAL_UPDATE_ERROR_MESSAGE);
        }
        return true;
    }


    /**
     * -------------------------
     * 登録書類登録
     * -------------------------
     *
     * @param array $requestContent
     * @return boolean
     */
    public function archiveInsert(array $requestContent)
    {
        // 取引書類登録
        $docInsertResult           = $this->docArchive->insert($requestContent);

        // 取引書類閲覧権限登録
        $docPermissionInsertResult = $this->docPermissionArchive->insert($requestContent) ;

        // 取引書類容量登録
        $docStorageInsertResult    = $this->docStorageArchive->insert($requestContent);

        // ワークフローテーブル登録
        // ワークフローが起票者のみ
        if (count($requestContent['select_sign_user']) === 1) {
            $companyId  = $requestContent['company_id'];
            $categoryId = $requestContent['category_id'];
            $appUserId  = $requestContent['select_sign_user'][0]['user_id'];
            $wfSort     = Self::ISSUE_USER_WF_SORT;
            $userId     = $requestContent['m_user_id'];
            $createDate = $requestContent['create_datetime'];

            $documentWorkFlowResult = $this->documentWorkFlow->insertArchive($companyId, $categoryId, $appUserId, $wfSort, $userId, $createDate);
            if (!$documentWorkFlowResult) {
                throw new Exception(Self::ARCHIVE_INSERT_ERROR_MESSAGE);
                exit;
            }

        // ゲスト署名者が未入力の場合
        } elseif ($requestContent['select_sign_guest_user'] === null) {
            $selectSignUserList = $requestContent['select_sign_user'];
            foreach ($selectSignUserList as $wf_sort => $selectSignUser) {
                $companyId              = $requestContent['company_id'];
                $categoryId             = $requestContent['category_id'];
                $appUserId              = $selectSignUser['user_id'];
                $wfSort                 = $selectSignUser['wf_sort'];
                $userId                 = $requestContent['m_user_id'];
                $createDate             = $requestContent['create_datetime'];

                $documentWorkFlowResult = $this->documentWorkFlow->insertArchive($companyId, $categoryId, $appUserId, $wfSort, $userId, $createDate);
                if (!$documentWorkFlowResult) {
                    throw new Exception(Self::ARCHIVE_INSERT_ERROR_MESSAGE);
                    exit;
                }
            }
        } else {
            $selectSignUserList = array_merge($requestContent['select_sign_user'], $requestContent['select_sign_guest_user']);
            foreach ($selectSignUserList as $wf_sort => $selectSignUser) {
                $companyId              = $requestContent['company_id'];
                $categoryId             = $requestContent['category_id'];
                $appUserId              = $selectSignUser['user_id'];
                $wfSort                 = $selectSignUser['wf_sort'];
                $userId                 = $requestContent['m_user_id'];
                $createDate             = $requestContent['create_datetime'];

                $documentWorkFlowResult = $this->documentWorkFlow->insertArchive($companyId, $categoryId, $appUserId, $wfSort, $userId, $createDate);
                if (!$documentWorkFlowResult) {
                    throw new Exception(Self::ARCHIVE_INSERT_ERROR_MESSAGE);
                    exit;
                }
            }
        }
        if ($docInsertResult === false || $docPermissionInsertResult === false || $docStorageInsertResult === false || $documentWorkFlowResult === false) {
            throw new Exception(Self::ARCHIVE_INSERT_ERROR_MESSAGE);
        }
        return true;
    }
    /**
     * -------------------------
     * 登録書類更新
     * -------------------------
     *
     * @param array $requestContent
     * @return boolean
     */
    public function archiveUpdate(array $requestContent)
    {
        // 契約書類更新
        $docUpdateResult           = $this->docArchive->update($requestContent);

        // 契約書類閲覧権限更新
        $docPermissionUpdateResult = $this->docPermissionArchive->update($requestContent);

        // 契約書類容量更新
        $docStorageUpdateResult    = $this->docStorageArchive->update($requestContent);

        if (!$docUpdateResult === 1 || !$docPermissionUpdateResult === 1 || !$docStorageUpdateResult === 1) {
            throw new Exception(Self::ARCHIVE_UPDATE_ERROR_MESSAGE);
        }
        return true;
    }

    /**
     * -------------------------
     * 契約書類ログ取得
     * -------------------------
     *
     * @param array $requestContent
     * @return
     */
    public function getBeforOrAfterUpdateContract(array $requestContent)
    {
        $contract    = $this->docContract->getBeforeOrAfterUpdateData($requestContent);
        $perContract = $this->docPermissionContract->getBeforeOrAfterUpdateData($requestContent);
        $stoContract = $this->docStorageContract->getBeforeOrAfterUpdateData($requestContent);
        if (empty($contract) && empty($perContract) && empty($stoContract)) {
            return new DocumentUpdateEntity();
        }
        return new DocumentUpdateEntity($contract, $perContract, $stoContract);
    }

    /**
     * -------------------------
     * 取引書類ログ取得
     * -------------------------
     *
     * @param array $requestContent
     * @return
     */
    public function getBeforOrAfterUpdateDeal(array $requestContent)
    {
        $deal = $this->docDeal->getBeforeOrAfterUpdateData($requestContent);
        $perDeal = $this->docDeal->getBeforeOrAfterUpdateData($requestContent);
        $stoDeal = $this->docDeal->getBeforeOrAfterUpdateData($requestContent);
        if (empty($dela) && empty($perDeal) && empty($stoDeal)) {
            return new DocumentUpdateEntity();
        }
        return new DocumentUpdateEntity($deal, $perDeal, $stoDeal);
    }

    /**
     * -------------------------
     * 社内書類ログ取得
     * -------------------------
     *
     * @param array $requestContent
     * @return
     */
    public function getBeforOrAfterUpdateInternal(array $requestContent)
    {
        $internal = $this->docInternal->getBeforeOrAfterUpdateData($requestContent);
        $perInternal = $this->docInternal->getBeforeOrAfterUpdateData($requestContent);
        $stoInternal = $this->docInternal->getBeforeOrAfterUpdateData($requestContent);
        if (empty($internal) && empty($perInternal) && empty($stoInternal)) {
            return new DocumentUpdateEntity();
        }
        return new DocumentUpdateEntity($internal, $perInternal, $stoInternal);
    }

    /**
     * -------------------------
     * 登録書類ログ取得
     * -------------------------
     *
     * @param array $requestContent
     * @return
     */
    public function getBeforOrAfterUpdateArchive(array $requestContent)
    {
        $archive = $this->docArchive->getBeforeOrAfterUpdateData($requestContent);
        $perArchive = $this->docArchive->getBeforeOrAfterUpdateData($requestContent);
        $stoArchive = $this->docArchive->getBeforeOrAfterUpdateData($requestContent);
        if (empty($archive) && empty($perArchive) && empty($stoArchive)) {
            return new DocumentUpdateEntity();
        }
        return new DocumentUpdateEntity($archive, $perArchive, $stoArchive);
    }

    /**
     * -------------------------
     * ログ登録
     * -------------------------
     *
     * @param array $requestContent
     * @param [type] $beforeContent
     * @param [type] $afterContet
     * @return boolean
     */
    public function getUpdateLog(array $requestContent, $beforeContent, $afterContet)
    {
        $companyId     = $requestContent['m_user_company_id'];
        $categoryId    = $requestContent['category_id'];
        $documentId    = $requestContent['document_id'];
        $userId        = $requestContent['m_user_id'];
        $userType      = $requestContent['m_user_type_id'];
        $ipAddress     = $requestContent['ip_address'];
        $accessContent = $requestContent['access_content'];

        //アクセスログに登録
        $accessLogResult    = $this->logDocAccess->insert($companyId, $categoryId, $documentId, $userId, $userType, $ipAddress, $accessContent);

        // 操作ログに登録
        $operationLogResutl = $this->logDocOperation->insert($companyId, $categoryId, $documentId, $userId, $beforeContent, $afterContet, $ipAddress);

        if (!$accessLogResult || !$operationLogResutl) {
            throw new Exception(Self::LOG_ERROR_MESSAGE);
        }
        return true;
    }
}
