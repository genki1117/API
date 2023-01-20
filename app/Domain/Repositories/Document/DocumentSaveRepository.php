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
    public function contractInsert(array $requestContent): ?bool
    {
        try {
            // 契約書類登録
            $docInsertResult           = $this->docContract->insert(requestContent: $requestContent);
            if (!$docInsertResult) {
                throw new Exception('common.message.permission');
            }

            // 契約書類閲覧権限登録
            $docPermissionInsertResult = $this->docPermissionContract->insert(requestContent: $requestContent);
            if (!$docPermissionInsertResult) {
                throw new Exception('common.message.permission');
            }

            // 契約書類容量登録
            $docStorageInsertResult    = $this->docStorageContract->insert(requestContent: $requestContent);
            if (!$docStorageInsertResult) {
                throw new Exception('common.message.permission');
            }

            // ワークフローテーブル登録
            // ワークフローが起票者のみ
            if (count($requestContent['select_sign_user']) === 1) {
                $companyId  = $requestContent['company_id'];
                $categoryId = $requestContent['category_id'];
                $appUserId  = $requestContent['select_sign_user'][0]['user_id'];
                $wfSort     = Self::ISSUE_USER_WF_SORT;
                $userId     = $requestContent['m_user_id'];
                $createDate = $requestContent['create_datetime'];

                $documentWorkFlowResult = $this->documentWorkFlow->insertContract(companyId: $companyId, categoryId: $categoryId, appUserId: $appUserId, wfSort: $wfSort, userId: $userId, createDate: $createDate);
                if (!$documentWorkFlowResult) {
                    throw new Exception('common.message.permission');
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

                    $documentWorkFlowResult = $this->documentWorkFlow->insertContract(companyId: $companyId, categoryId: $categoryId, appUserId: $appUserId, wfSort: $wfSort, userId: $userId, createDate: $createDate);
                    if (!$documentWorkFlowResult) {
                        throw new Exception('common.message.permission');
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

                    $documentWorkFlowResult = $this->documentWorkFlow->insertContract(companyId: $companyId, categoryId: $categoryId, appUserId: $appUserId, wfSort: $wfSort, userId: $userId, createDate: $createDate);
                    if (!$documentWorkFlowResult) {
                        throw new Exception('common.message.permission');
                    }
                }
            }
        } catch (Exception $e) {
            throw $e;
            return false;
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
    public function contractUpdate(array $requestContent): ?bool
    {
        try {
            // 契約書類更新
            $docUpdateResult           = $this->docContract->update(requestContent: $requestContent);
            if (!$docUpdateResult) {
                throw new Exception('common.message.save-conflict');
            }
            
            // 契約書類閲覧権限更新
            $docPermissionUpdateResult = $this->docPermissionContract->update(requestContent: $requestContent);
            if (!$docPermissionUpdateResult) {
                throw new Exception('common.message.save-conflict');
            }

            // 契約書類容量更新
            $docStorageUpdateResult    = $this->docStorageContract->update(requestContent: $requestContent);

            if (!$docStorageUpdateResult) {
                throw new Exception('common.message.save-conflict');
            }
        } catch (Exception $e) {
            throw $e;
            return false;
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
    public function dealInsert(array $requestContent): ?bool
    {
        try {
            // 取引書類登録
            $docInsertResult           = $this->docDeal->insert(requestContent: $requestContent);
            if (!$docInsertResult) {
                throw new Exception('common.message.permission');
            }

            // 取引書類閲覧権限登録
            $docPermissionInsertResult = $this->docPermissionTransaction->insert(requestContent: $requestContent) ;
            if (!$docPermissionInsertResult) {
                throw new Exception('common.message.permission');
            }

            // 取引書類容量登録
            $docStorageInsertResult    = $this->docStorageTransaction->insert(requestContent: $requestContent);
            if (!$docStorageInsertResult) {
                throw new Exception('common.message.permission');
            }

            // ワークフローテーブル登録
            // ワークフローが起票者のみ
            if (count($requestContent['select_sign_user']) === 1) {
                $companyId  = $requestContent['company_id'];
                $categoryId = $requestContent['category_id'];
                $appUserId  = $requestContent['select_sign_user'][0]['user_id'];
                $wfSort     = Self::ISSUE_USER_WF_SORT;
                $userId     = $requestContent['m_user_id'];
                $createDate = $requestContent['create_datetime'];

                $documentWorkFlowResult = $this->documentWorkFlow->insertDeal(companyId: $companyId, categoryId: $categoryId, appUserId: $appUserId, wfSort: $wfSort, userId: $userId, createDate: $createDate);
                if (!$documentWorkFlowResult) {
                    throw new Exception('common.message.permission');
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

                    $documentWorkFlowResult = $this->documentWorkFlow->insertDeal(companyId: $companyId, categoryId: $categoryId, appUserId: $appUserId, wfSort: $wfSort, userId: $userId, createDate: $createDate);
                    if (!$documentWorkFlowResult) {
                        throw new Exception('common.message.permission');
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

                    $documentWorkFlowResult = $this->documentWorkFlow->insertDeal(companyId: $companyId, categoryId: $categoryId, appUserId: $appUserId, wfSort: $wfSort, userId: $userId, createDate: $createDate);
                    if (!$documentWorkFlowResult) {
                        throw new Exception('common.message.permission');
                    }
                }
            }
        } catch (Exception $e) {
            throw $e;
            return false;
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
    public function dealUpdate(array $requestContent): ?bool
    {
        try {
            // 取引書類更新
            $docUpdateResult           = $this->docDeal->update(requestContent: $requestContent);
            if (!$docUpdateResult) {
                throw new Exception('common.message.save-conflict');
            }
            
            // 取引書類閲覧権限更新
            $docPermissionUpdateResult = $this->docPermissionTransaction->update(requestContent: $requestContent);
            if (!$docPermissionUpdateResult) {
                throw new Exception('common.message.save-conflict');
            }

            // 取引書類容量更新
            $docStorageUpdateResult    = $this->docStorageTransaction->update(requestContent: $requestContent);
            if (!$docStorageUpdateResult) {
                throw new Exception('common.message.save-conflict');
            }
        } catch (Exception $e) {
            throw $e;
            return false;
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
    public function internalInsert(array $requestContent): ?bool
    {
        try {
            // 社内書類登録
            $docInsertResult           = $this->docInternal->insert(requestContent: $requestContent);
            if (!$docInsertResult) {
                throw new Exception('common.message.permission');
            }

            // 社内書類閲覧権限登録
            $docPermissionInsertResult = $this->docPermissionInternal->insert(requestContent: $requestContent) ;
            if (!$docPermissionInsertResult) {
                throw new Exception('common.message.permission');
            }

            // 社内書類容量登録
            $docStorageInsertResult    = $this->docStorageInternal->insert(requestContent: $requestContent);
            if (!$docStorageInsertResult) {
                throw new Exception('common.message.permission');
            }
            

            // ワークフローテーブル登録
            // ワークフローが起票者のみ
            if (count($requestContent['select_sign_user']) === 1) {
                $companyId  = $requestContent['company_id'];
                $categoryId = $requestContent['category_id'];
                $appUserId  = $requestContent['select_sign_user'][0]['user_id'];
                $wfSort     = Self::ISSUE_USER_WF_SORT;
                $userId     = $requestContent['m_user_id'];
                $createDate = $requestContent['create_datetime'];

                $documentWorkFlowResult = $this->documentWorkFlow->insertInternal(companyId: $companyId, categoryId: $categoryId, appUserId: $appUserId, wfSort: $wfSort, userId: $userId, createDate: $createDate);
                if (!$documentWorkFlowResult) {
                    throw new Exception('common.message.permission');
                    exit;
                }
            } else {
                $selectSignUserList = $requestContent['select_sign_user'];
                foreach ($selectSignUserList as $wf_sort => $selectSignUser) {
                    $companyId              = $requestContent['company_id'];
                    $categoryId             = $requestContent['category_id'];
                    $appUserId              = $selectSignUser['user_id'];
                    $wfSort                 = $selectSignUser['wf_sort'];
                    $userId                 = $requestContent['m_user_id'];
                    $createDate             = $requestContent['create_datetime'];

                    $documentWorkFlowResult = $this->documentWorkFlow->insertInternal(companyId: $companyId, categoryId: $categoryId, appUserId: $appUserId, wfSort: $wfSort, userId: $userId, createDate: $createDate);
                    if (!$documentWorkFlowResult) {
                        throw new Exception('common.message.permission');
                        exit;
                    }
                }
            }
        } catch (Exception $e) {
            throw $e;
            return false;
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
    public function InternalUpdate(array $requestContent): ?bool
    {
        try {
            // 社内書類更新
            $docUpdateResult           = $this->docInternal->update(requestContent: $requestContent);
            if (!$docUpdateResult) {
                throw new Exception('common.message.save-conflict');
            }
            
            // 社内書類閲覧権限更新
            $docPermissionUpdateResult = $this->docPermissionInternal->update(requestContent: $requestContent);
            if (!$docPermissionUpdateResult) {
                throw new Exception('common.message.save-conflict');
            }

            // 社内書類容量更新
            $docStorageUpdateResult    = $this->docStorageInternal->update(requestContent: $requestContent);
            if (!$docStorageUpdateResult) {
                throw new Exception('common.message.save-conflict');
            }
        } catch (Exception $e) {
            throw $e;
            return false;
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
    public function archiveInsert(array $requestContent): ?bool
    {
        try {
                // 登録書類登録
                $docInsertResult           = $this->docArchive->insert(requestContent: $requestContent);
                if (!$docInsertResult) {
                    throw new Exception('common.message.permission');
                }

                // 登録書類閲覧権限登録
                $docPermissionInsertResult = $this->docPermissionArchive->insert(requestContent: $requestContent) ;
                if (!$docPermissionInsertResult) {
                    throw new Exception('common.message.permission');
                }

                // 登録書類容量登録
                $docStorageInsertResult    = $this->docStorageArchive->insert(requestContent: $requestContent);
                if (!$docStorageInsertResult) {
                    throw new Exception('common.message.permission');
                }

                // ワークフローテーブル登録
                // ワークフローが起票者のみ
                if (count($requestContent['select_sign_user']) === 1) {
                    $companyId  = $requestContent['company_id'];
                    $categoryId = $requestContent['category_id'];
                    $appUserId  = $requestContent['select_sign_user'][0]['user_id'];
                    $wfSort     = Self::ISSUE_USER_WF_SORT;
                    $userId     = $requestContent['m_user_id'];
                    $createDate = $requestContent['create_datetime'];

                    $documentWorkFlowResult = $this->documentWorkFlow->insertArchive(companyId: $companyId, categoryId: $categoryId, appUserId: $appUserId, wfSort: $wfSort, userId: $userId, createDate: $createDate);
                    if (!$documentWorkFlowResult) {
                        throw new Exception('common.message.permission');
                        exit;
                    }
                } else {
                    $selectSignUserList = $requestContent['select_sign_user'];
                    foreach ($selectSignUserList as $wf_sort => $selectSignUser) {
                        $companyId              = $requestContent['company_id'];
                        $categoryId             = $requestContent['category_id'];
                        $appUserId              = $selectSignUser['user_id'];
                        $wfSort                 = $selectSignUser['wf_sort'];
                        $userId                 = $requestContent['m_user_id'];
                        $createDate             = $requestContent['create_datetime'];

                        $documentWorkFlowResult = $this->documentWorkFlow->insertArchive(companyId: $companyId, categoryId: $categoryId, appUserId: $appUserId, wfSort: $wfSort, userId: $userId, createDate: $createDate);
                        if (!$documentWorkFlowResult) {
                            throw new Exception('common.message.permission');
                            exit;
                        }
                    }
                }
            } catch (Exception $e) {
                throw $e;
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
    public function archiveUpdate(array $requestContent): ?bool
    {
        try {
            // 社内書類更新
            $docUpdateResult           = $this->docArchive->update(requestContent: $requestContent);
            if (!$docUpdateResult) {
                throw new Exception('common.message.save-conflict');
            }
            
            // 社内書類閲覧権限更新
            $docPermissionUpdateResult = $this->docPermissionArchive->update(requestContent: $requestContent);
            if (!$docPermissionUpdateResult) {
                throw new Exception('common.message.save-conflict');
            }

            // 社内書類容量更新
            $docStorageUpdateResult    = $this->docStorageArchive->update(requestContent: $requestContent);
            if (!$docStorageUpdateResult) {
                throw new Exception('common.message.save-conflict');
            }
        } catch (Exception $e) {
            throw $e;
            return false;
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
    public function getBeforOrAfterUpdateContract(array $requestContent): DocumentUpdateEntity
    {
        $contract    = $this->docContract->getBeforeOrAfterUpdateData(requestContent: $requestContent);
        $perContract = $this->docPermissionContract->getBeforeOrAfterUpdateData(requestContent: $requestContent);
        $stoContract = $this->docStorageContract->getBeforeOrAfterUpdateData(requestContent: $requestContent);
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
    public function getBeforOrAfterUpdateDeal(array $requestContent): DocumentUpdateEntity
    {
        $deal = $this->docDeal->getBeforeOrAfterUpdateData(requestContent: $requestContent);
        $perDeal = $this->docDeal->getBeforeOrAfterUpdateData(requestContent: $requestContent);
        $stoDeal = $this->docDeal->getBeforeOrAfterUpdateData(requestContent: $requestContent);
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
    public function getBeforOrAfterUpdateInternal(array $requestContent): DocumentUpdateEntity
    {
        $internal = $this->docInternal->getBeforeOrAfterUpdateData(requestContent: $requestContent);
        $perInternal = $this->docInternal->getBeforeOrAfterUpdateData(requestContent: $requestContent);
        $stoInternal = $this->docInternal->getBeforeOrAfterUpdateData(requestContent: $requestContent);
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
    public function getBeforOrAfterUpdateArchive(array $requestContent): DocumentUpdateEntity
    {
        $archive = $this->docArchive->getBeforeOrAfterUpdateData(requestContent: $requestContent);
        $perArchive = $this->docArchive->getBeforeOrAfterUpdateData(requestContent: $requestContent);
        $stoArchive = $this->docArchive->getBeforeOrAfterUpdateData(requestContent: $requestContent);
        if (empty($archive) && empty($perArchive) && empty($stoArchive)) {
            return new DocumentUpdateEntity();
        }
        return new DocumentUpdateEntity($archive, $perArchive, $stoArchive);
    }

    /**
     * -------------------------
     * 契約書類ログ登録
     * -------------------------
     *
     * @param array $requestContent
     * @param [type] $beforeContent
     * @param [type] $afterContet
     * @return boolean
     */
    public function getUpdateLogContract(array $requestContent, $beforeContent, $afterContent): ?bool
    {
        try {
            $companyId     = $requestContent['m_user_company_id'];
            $categoryId    = $requestContent['category_id'];
            $documentId    = $requestContent['document_id'];
            $userId        = $requestContent['m_user_id'];
            $userType      = $requestContent['m_user_type_id'];
            $ipAddress     = $requestContent['ip_address'];
            $accessContent = $requestContent['access_content'];

            $beforeContentArray = $this->beforeContetArrayContract($beforeContent, $afterContent);

            $afterContentArray = $this->afterContentArrayContract($beforeContent, $afterContent);

            //アクセスログに登録
            $accessLogResult    = $this->logDocAccess->insert(
                companyId: $companyId,
                categoryId: $categoryId,
                documentId: $documentId,
                userId: $userId,
                userType: $userType,
                ipAddress: $ipAddress,
                accessContent: $accessContent
            );


            $operationLogResutl = $this->logDocOperation->insert(
                companyId: $companyId,
                categoryId: $categoryId,
                documentId: $documentId,
                userId: $userId,
                beforeContentArray: $beforeContentArray,
                afterContentArray: $afterContentArray,
                ipAddress: $ipAddress
            );


            if (!$accessLogResult || !$operationLogResutl) {
                throw new Exception('common.message.permission');
            }
            return true;
        } catch (Exception $e) {
            throw $e;
            return false;
        }
        
    }

    /**
     * -------------------------
     * 取引書類ログ登録
     * -------------------------
     *
     * @param array $requestContent
     * @param [type] $beforeContent
     * @param [type] $afterContet
     * @return boolean
     */
    public function getUpdateLogDeal(array $requestContent, $beforeContent, $afterContent): ?bool
    {
        try {
            $companyId     = $requestContent['m_user_company_id'];
            $categoryId    = $requestContent['category_id'];
            $documentId    = $requestContent['document_id'];
            $userId        = $requestContent['m_user_id'];
            $userType      = $requestContent['m_user_type_id'];
            $ipAddress     = $requestContent['ip_address'];
            $accessContent = $requestContent['access_content'];

            $beforeContentArray = $this->beforeContetArrayDeal($beforeContent, $afterContent);

            $afterContentArray = $this->afterContentArrayDeal($beforeContent, $afterContent);


            //アクセスログに登録
            $accessLogResult    = $this->logDocAccess->insert(
                companyId: $companyId,
                categoryId: $categoryId,
                documentId: $documentId,
                userId: $userId,
                userType: $userType,
                ipAddress: $ipAddress,
                accessContent: $accessContent
            );

            // 操作ログ出力
            $operationLogResutl = $this->logDocOperation->insert(
                companyId: $companyId,
                categoryId: $categoryId,
                documentId: $documentId,
                userId: $userId,
                beforeContentArray: $beforeContentArray,
                afterContentArray: $afterContentArray,
                ipAddress: $ipAddress
            );

            $a;
            if (!$accessLogResult || !$operationLogResutl) {
                throw new Exception('common.message.permission');
            }
            return true;
        } catch (Exception $e) {
            throw $e;
            return false;
        }
        
    }

    /**
     * -------------------------
     * 社内書類ログ登録
     * -------------------------
     *
     * @param array $requestContent
     * @param [type] $beforeContent
     * @param [type] $afterContet
     * @return boolean
     */
    public function getUpdateLogInternal(array $requestContent, $beforeContent, $afterContent): ?bool
    {
        try {
            $companyId     = $requestContent['m_user_company_id'];
            $categoryId    = $requestContent['category_id'];
            $documentId    = $requestContent['document_id'];
            $userId        = $requestContent['m_user_id'];
            $userType      = $requestContent['m_user_type_id'];
            $ipAddress     = $requestContent['ip_address'];
            $accessContent = $requestContent['access_content'];

            // beforeContent取得
            $beforeContentArray = $this->beforeContetArrayInternal($beforeContent, $afterContent);

            // afterContent取得
            $afterContentArray = $this->afterContentArrayInternal($beforeContent, $afterContent);

            //アクセスログに登録
            $accessLogResult    = $this->logDocAccess->insert(
                companyId: $companyId,
                categoryId: $categoryId,
                documentId: $documentId,
                userId: $userId,
                userType: $userType,
                ipAddress: $ipAddress,
                accessContent: $accessContent
            );

            // 操作ログ出力
            $operationLogResutl = $this->logDocOperation->insert(
                companyId: $companyId,
                categoryId: $categoryId,
                documentId: $documentId,
                userId: $userId,
                beforeContentArray: $beforeContentArray,
                afterContentArray: $afterContentArray,
                ipAddress: $ipAddress
            );

            $a;
            if (!$accessLogResult || !$operationLogResutl) {
                throw new Exception('common.message.permission');
            }
            return true;
        } catch (Exception $e) {
            throw $e;
            return false;
        }
    }

    public function getUpdateLogArchive(array $requestContent, $beforeContent, $afterContent): ?bool
    {
        try {
            $companyId     = $requestContent['m_user_company_id'];
            $categoryId    = $requestContent['category_id'];
            $documentId    = $requestContent['document_id'];
            $userId        = $requestContent['m_user_id'];
            $userType      = $requestContent['m_user_type_id'];
            $ipAddress     = $requestContent['ip_address'];
            $accessContent = $requestContent['access_content'];

            // beforeContent取得
            $beforeContentArray = $this->beforeContentArrayArchive($beforeContent, $afterContent);

            // afterContent取得
            $afterContentArray = $this->afterContentArrayArchive($beforeContent, $afterContent);

            //アクセスログに登録
            $accessLogResult    = $this->logDocAccess->insert(
                companyId: $companyId,
                categoryId: $categoryId,
                documentId: $documentId,
                userId: $userId,
                userType: $userType,
                ipAddress: $ipAddress,
                accessContent: $accessContent
            );

            // 操作ログ出力
            $operationLogResutl = $this->logDocOperation->insert(
                companyId: $companyId,
                categoryId: $categoryId,
                documentId: $documentId,
                userId: $userId,
                beforeContentArray: $beforeContentArray,
                afterContentArray: $afterContentArray,
                ipAddress: $ipAddress
            );

            if (!$accessLogResult || !$operationLogResutl) {
                throw new Exception('common.message.permission');
            }
            return true;
        } catch (Exception $e) {
            throw $e;
            return false;
        }
    }


/**
     * 登録書類更新beforeContent取得
     *
     * @param object $beforeContent
     * @param object $afterContet
     * @return array
     */
    public function beforeContentArrayArchive(object $beforeContent, object $afterContet): array
    {
        $beforeContentArray = json_decode(json_encode($beforeContent), true);
        $afterContentArray   = json_decode(json_encode($afterContet), true);

        $returnBeforeArchiveArray['operation'] = [];

        if ($beforeContentArray['template_id'] !== $afterContentArray['template_id']) {
            array_push($returnBeforeArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.template',
                'target_param_content' => $beforeContentArray['template_id']
            ]);    
        }
        if ($beforeContentArray['doc_type_id'] !== $afterContentArray['doc_type_id']) {
            array_push($returnBeforeArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.document-type',
                'target_param_content' => $beforeContentArray['doc_type_id']
            ]);    
        }
        if ($beforeContentArray['issue_date'] !== $afterContentArray['issue_date']) {
            array_push($returnBeforeArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.issue-date',
                'target_param_content' => $beforeContentArray['issue_date']
            ]);    
        }
        if ($beforeContentArray['expiry_date'] !== $afterContentArray['expiry_date']) {
            array_push($returnBeforeArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.expiry-date',
                'target_param_content' => $beforeContentArray['expiry_date']
            ]);    
        }
        if ($beforeContentArray['transaction_date'] !== $afterContentArray['transaction_date']) {
            array_push($returnBeforeArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.deal-date',
                'target_param_content' => $beforeContentArray['transaction_date']
            ]);    
        }
        if ($beforeContentArray['doc_no'] !== $afterContentArray['doc_no']) {
            array_push($returnBeforeArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.document-number',
                'target_param_content' => $beforeContentArray['doc_no']
            ]);    
        }
        if ($beforeContentArray['ref_doc_no'] !== $afterContentArray['ref_doc_no']) {
            array_push($returnBeforeArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.ref-document.no',
                'target_param_content' => $beforeContentArray['ref_doc_no']
            ]);    
        }
        if ($beforeContentArray['product_name'] !== $afterContentArray['product_name']) {
            array_push($returnBeforeArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.product-name',
                'target_param_content' => $beforeContentArray['product_name']
            ]);    
        }
        if ($beforeContentArray['title'] !== $afterContentArray['title']) {
            array_push($returnBeforeArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.title',
                'target_param_content' => $beforeContentArray['title']
            ]);    
        }
        if ($beforeContentArray['amount'] !== $afterContentArray['amount']) {
            array_push($returnBeforeArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.amount',
                'target_param_content' => $beforeContentArray['amount']
            ]);    
        }
        if ($beforeContentArray['currency_id'] !== $afterContentArray['currency_id']) {
            array_push($returnBeforeArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.currency',
                'target_param_content' => $beforeContentArray['currency_id']
            ]); 
        }
        if ($beforeContentArray['counter_party_id'] !== $afterContentArray['counter_party_id']) {
            array_push($returnBeforeArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.common.label.item.counter-party.id',
                'target_param_content' => $beforeContentArray['counter_party_id']
            ]);    
        }
        if ($beforeContentArray['remarks'] !== $afterContentArray['remarks']) {
            array_push($returnBeforeArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.common.label.item.remark',
                'target_param_content' => $beforeContentArray['remarks']
            ]);    
        }
        if ($beforeContentArray['doc_info'] !== $afterContentArray['doc_info']) {
            array_push($returnBeforeArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.description',
                'target_param_content' => $beforeContentArray['doc_info']
            ]);    
        }
        if ($beforeContentArray['sign_level'] !== $afterContentArray['sign_level']) {
            array_push($returnBeforeArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.sign-level',
                'target_param_content' => $beforeContentArray['sign_level']
            ]);    
        }
        return $returnBeforeArchiveArray;
    }

    /**
     * 登録書類更新afterContent取得
     *
     * @param object $beforeContent
     * @param object $afterContet
     * @return array
     */
    public function afterContentArrayArchive(object $beforeContent, object $afterContet): array
    {
        $beforeContentArray = json_decode(json_encode($beforeContent), true);
        $afterContentArray   = json_decode(json_encode($afterContet), true);

        $returnAfterArchiveArray['operation'] = [];

        if ($beforeContentArray['template_id'] !== $afterContentArray['template_id']) {
            array_push($returnAfterArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.template',
                'target_param_content' => $afterContentArray['template_id']
            ]);    
        }
        if ($beforeContentArray['doc_type_id'] !== $afterContentArray['doc_type_id']) {
            array_push($returnAfterArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.document-type',
                'target_param_content' => $afterContentArray['doc_type_id']
            ]);    
        }
        if ($beforeContentArray['issue_date'] !== $afterContentArray['issue_date']) {
            array_push($returnAfterArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.issue-date',
                'target_param_content' => $afterContentArray['issue_date']
            ]);    
        }
        if ($beforeContentArray['expiry_date'] !== $afterContentArray['expiry_date']) {
            array_push($returnAfterArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.expiry-date',
                'target_param_content' => $afterContentArray['expiry_date']
            ]);    
        }
        if ($beforeContentArray['transaction_date'] !== $afterContentArray['transaction_date']) {
            array_push($returnAfterArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.deal-date',
                'target_param_content' => $afterContentArray['transaction_date']
            ]);    
        }
        if ($beforeContentArray['doc_no'] !== $afterContentArray['doc_no']) {
            array_push($returnAfterArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.document-number',
                'target_param_content' => $afterContentArray['doc_no']
            ]);    
        }
        if ($beforeContentArray['ref_doc_no'] !== $afterContentArray['ref_doc_no']) {
            array_push($returnAfterArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.ref-document.no',
                'target_param_content' => $afterContentArray['ref_doc_no']
            ]);    
        }
        if ($beforeContentArray['product_name'] !== $afterContentArray['product_name']) {
            array_push($returnAfterArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.product-name',
                'target_param_content' => $afterContentArray['product_name']
            ]);    
        }
        if ($beforeContentArray['title'] !== $afterContentArray['title']) {
            array_push($returnAfterArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.title',
                'target_param_content' => $afterContentArray['title']
            ]);    
        }
        if ($beforeContentArray['amount'] !== $afterContentArray['amount']) {
            array_push($returnAfterArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.amount',
                'target_param_content' => $afterContentArray['amount']
            ]);    
        }
        if ($beforeContentArray['currency_id'] !== $afterContentArray['currency_id']) {
            array_push($returnAfterArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.currency',
                'target_param_content' => $afterContentArray['currency_id']
            ]); 
        }
        if ($beforeContentArray['counter_party_id'] !== $afterContentArray['counter_party_id']) {
            array_push($returnAfterArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.common.label.item.counter-party.id',
                'target_param_content' => $afterContentArray['counter_party_id']
            ]);    
        }
        if ($beforeContentArray['remarks'] !== $afterContentArray['remarks']) {
            array_push($returnAfterArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.common.label.item.remark',
                'target_param_content' => $afterContentArray['remarks']
            ]);    
        }
        if ($beforeContentArray['doc_info'] !== $afterContentArray['doc_info']) {
            array_push($returnAfterArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.description',
                'target_param_content' => $afterContentArray['doc_info']
            ]);    
        }
        if ($beforeContentArray['sign_level'] !== $afterContentArray['sign_level']) {
            array_push($returnAfterArchiveArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.sign-level',
                'target_param_content' => $afterContentArray['sign_level']
            ]);    
        }
        return $returnAfterArchiveArray;
    }

    /**
     * 取引書類更新beforeContent取得
     *
     * @param object $beforeContent
     * @param object $afterContet
     * @return array
     */
    public function beforeContetArrayInternal(object $beforeContent, object $afterContet): array
    {
        $beforeContentArray = json_decode(json_encode($beforeContent), true);
        $afterContentArray   = json_decode(json_encode($afterContet), true);
        // var_export($beforeContentArray['product_name']);
        // exit();
        $returnBeforeInternalArray['operation'] = [];

        if ($beforeContentArray['content'] !== $afterContentArray['content']) {
            array_push($returnBeforeInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.content',
                'target_param_content' => $afterContentArray['content']
            ]);    
        }

        if ($beforeContentArray['template_id'] !== $afterContentArray['template_id']) {
            array_push($returnBeforeInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.template',
                'target_param_content' => $afterContentArray['template_id']
            ]);    
        }
        if ($beforeContentArray['doc_type_id'] !== $afterContentArray['doc_type_id']) {
            array_push($returnBeforeInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.document-type',
                'target_param_content' => $afterContentArray['doc_type_id']
            ]);    
        }
        if ($beforeContentArray['doc_create_date'] !== $afterContentArray['doc_create_date']) {
            array_push($returnBeforeInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.document-create-date',
                'target_param_content' => $afterContentArray['doc_create_date']
            ]);    
        }
        if ($beforeContentArray['sign_finish_date'] !== $afterContentArray['sign_finish_date']) {
            array_push($returnBeforeInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.sign-finish-date',
                'target_param_content' => $afterContentArray['sign_finish_date']
            ]);    
        }
        if ($beforeContentArray['doc_no'] !== $afterContentArray['doc_no']) {
            array_push($returnBeforeInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.document.no',
                'target_param_content' => $afterContentArray['doc_no']
            ]);    
        }
        if ($beforeContentArray['ref_doc_no'] !== $afterContentArray['ref_doc_no']) {
            array_push($returnBeforeInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.ref-document.no',
                'target_param_content' => $afterContentArray['ref_doc_no']
            ]);    
        }
        if ($beforeContentArray['product_name'] !== $afterContentArray['product_name']) {
            array_push($returnBeforeInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.ref-document.no',
                'target_param_content' => $afterContentArray['product_name']
            ]);    
        }
        if ($beforeContentArray['title'] !== $afterContentArray['title']) {
            array_push($returnBeforeInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.title',
                'target_param_content' => $afterContentArray['title']
            ]);    
        }
        if ($beforeContentArray['amount'] !== $afterContentArray['amount']) {
            array_push($returnBeforeInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.amount',
                'target_param_content' => $afterContentArray['amount']
            ]);    
        }
        if ($beforeContentArray['currency_id'] !== $afterContentArray['currency_id']) {
            array_push($returnBeforeInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.currency',
                'target_param_content' => $afterContentArray['currency_id']
            ]);    
        }
        if ($beforeContentArray['counter_party_id'] !== $afterContentArray['counter_party_id']) {
            array_push($returnBeforeInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.counter-party.id',
                'target_param_content' => $afterContentArray['counter_party_id']
            ]);    
        }
        if ($beforeContentArray['remarks'] !== $afterContentArray['remarks']) {
            array_push($returnBeforeInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.remarks',
                'target_param_content' => $afterContentArray['remarks']
            ]);    
        }
        if ($beforeContentArray['doc_info'] !== $afterContentArray['doc_info']) {
            array_push($returnBeforeInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.description',
                'target_param_content' => $afterContentArray['doc_info']
            ]); 
        }
        if ($beforeContentArray['sign_level'] !== $afterContentArray['sign_level']) {
            array_push($returnBeforeInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.sign-level',
                'target_param_content' => $afterContentArray['sign_level']
            ]);    
        }

        return $returnBeforeInternalArray;
    }

    /**
     * 社内書類更新afterContent取得
     *
     * @param object $beforeContent
     * @param object $afterContet
     * @return array
     */
    public function afterContentArrayInternal(object $beforeContent, object $afterContet): array
    {
        $beforeContentArray = json_decode(json_encode($beforeContent), true);
        $afterContentArray   = json_decode(json_encode($afterContet), true);

        $returnAfterInternalArray['operation'] = [];

        if ($beforeContentArray['content'] !== $afterContentArray['content']) {
            array_push($returnAfterInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.template',
                'target_param_content' => $beforeContentArray['content']
            ]);    
        }
        if ($beforeContentArray['template_id'] !== $afterContentArray['template_id']) {
            array_push($returnAfterInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.template',
                'target_param_content' => $beforeContentArray['template_id']
            ]);    
        }
        if ($beforeContentArray['doc_type_id'] !== $afterContentArray['doc_type_id']) {
            array_push($returnAfterInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.document-type',
                'target_param_content' => $beforeContentArray['doc_type_id']
            ]);    
        }
        if ($beforeContentArray['doc_create_date'] !== $afterContentArray['doc_create_date']) {
            array_push($returnAfterInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.document-create-date',
                'target_param_content' => $beforeContentArray['doc_create_date']
            ]);    
        }
        if ($beforeContentArray['sign_finish_date'] !== $afterContentArray['sign_finish_date']) {
            array_push($returnAfterInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.sign-finish-date',
                'target_param_content' => $beforeContentArray['sign_finish_date']
            ]);    
        }
        if ($beforeContentArray['doc_no'] !== $afterContentArray['doc_no']) {
            array_push($returnAfterInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.document.no',
                'target_param_content' => $beforeContentArray['doc_no']
            ]);    
        }
        if ($beforeContentArray['ref_doc_no'] !== $afterContentArray['ref_doc_no']) {
            array_push($returnAfterInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.ref-document.no',
                'target_param_content' => $beforeContentArray['ref_doc_no']
            ]);    
        }
        if ($beforeContentArray['product_name'] !== $afterContentArray['product_name']) {
            array_push($returnAfterInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.product-name',
                'target_param_content' => $beforeContentArray['product_name']
            ]);    
        }
        if ($beforeContentArray['title'] !== $afterContentArray['title']) {
            array_push($returnAfterInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.title',
                'target_param_content' => $beforeContentArray['title']
            ]);    
        }
        if ($beforeContentArray['amount'] !== $afterContentArray['amount']) {
            array_push($returnAfterInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.amount',
                'target_param_content' => $beforeContentArray['amount']
            ]);    
        }
        if ($beforeContentArray['currency_id'] !== $afterContentArray['currency_id']) {
            array_push($returnAfterInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.currency',
                'target_param_content' => $beforeContentArray['currency_id']
            ]);    
        }
        if ($beforeContentArray['counter_party_id'] !== $afterContentArray['counter_party_id']) {
            array_push($returnAfterInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.counter-party.id',
                'target_param_content' => $beforeContentArray['counter_party_id']
            ]);    
        }
        if ($beforeContentArray['remarks'] !== $afterContentArray['remarks']) {
            array_push($returnAfterInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.remarks',
                'target_param_content' => $beforeContentArray['remarks']
            ]);    
        }
        if ($beforeContentArray['doc_info'] !== $afterContentArray['doc_info']) {
            array_push($returnAfterInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.description',
                'target_param_content' => $beforeContentArray['doc_info']
            ]); 
        }
        if ($beforeContentArray['sign_level'] !== $afterContentArray['sign_level']) {
            array_push($returnAfterInternalArray['operation'], [
                'message_key' => 'common.message.document.operation-history',
                'operation_label' => 'document.label.item.sign-level',
                'target_param_content' => $beforeContentArray['sign_level']
            ]);    
        }

        return $returnAfterInternalArray;
    }


    /**
     * 取引書類更新beforeContent取得
     *
     * @param object $beforeContent
     * @param object $afterContet
     * @return array
     */
    public function beforeContetArrayDeal(object $beforeContent, object $afterContet): array
    {
            $beforeContentArray = json_decode(json_encode($beforeContent), true);
            $afterContentArray   = json_decode(json_encode($afterContet), true);

            $returnBeforeDealArray['operation'] = [];

            if ($beforeContentArray['template_id'] !== $afterContentArray['template_id']) {
                array_push($returnBeforeDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.template',
                    'target_param_content' => $beforeContentArray['template_id']
                ]);    
            }
            if ($beforeContentArray['doc_type_id'] !== $afterContentArray['doc_type_id']) {
                
                array_push($returnBeforeDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.document-type',
                    'target_param_content' => $beforeContentArray['doc_type_id']
                ]);    
            }
            if ($beforeContentArray['issue_date'] !== $afterContentArray['issue_date']) {
                array_push($returnBeforeDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.issue-date',
                    'target_param_content' => $beforeContentArray['issue_date']
                ]);    
            }
            if ($beforeContentArray['expiry_date'] !== $afterContentArray['expiry_date']) {
                array_push($returnBeforeDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.expiry-date',
                    'target_param_content' => $beforeContentArray['expiry_date']
                ]);    
            }
            // TODO: ラベル無し
            if ($beforeContentArray['payment_date'] !== $afterContentArray['payment_date']) {
                array_push($returnBeforeDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.payment-date',
                    'target_param_content' => $beforeContentArray['payment_date']
                ]);    
            }
            if ($beforeContentArray['transaction_date'] !== $afterContentArray['transaction_date']) {
                array_push($returnBeforeDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.deal-date',
                    'target_param_content' => $beforeContentArray['transaction_date']
                ]);    
            }
            if ($beforeContentArray['download_date'] !== $afterContentArray['download_date']) {
                array_push($returnBeforeDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.download-date',
                    'target_param_content' => $beforeContentArray['download_date']
                ]);    
            }
            if ($beforeContentArray['doc_no'] !== $afterContentArray['doc_no']) {
                array_push($returnBeforeDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.document.no',
                    'target_param_content' => $beforeContentArray['doc_no']
                ]);    
            }
            if ($beforeContentArray['ref_doc_no'] !== $afterContentArray['ref_doc_no']) {
                array_push($returnBeforeDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.ref-document.no',
                    'target_param_content' => $beforeContentArray['ref_doc_no']
                ]);    
            }
            if ($beforeContentArray['product_name'] !== $afterContentArray['product_name']) {
                array_push($returnBeforeDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.product-name',
                    'target_param_content' => $beforeContentArray['product_name']
                ]);    
            }
            if ($beforeContentArray['title'] !== $afterContentArray['title']) {
                array_push($returnBeforeDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.title',
                    'target_param_content' => $beforeContentArray['title']
                ]);    
            }
            if ($beforeContentArray['amount'] !== $afterContentArray['amount']) {
                array_push($returnBeforeDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.amount',
                    'target_param_content' => $beforeContentArray['amount']
                ]);    
            }
            if ($beforeContentArray['currency_id'] !== $afterContentArray['currency_id']) {
                array_push($returnBeforeDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.currency',
                    'target_param_content' => $beforeContentArray['currency_id']
                ]);    
            }
            if ($beforeContentArray['counter_party_id'] !== $afterContentArray['counter_party_id']) {
                array_push($returnBeforeDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.counter-party.id',
                    'target_param_content' => $beforeContentArray['counter_party_id']
                ]);    
            }
            if ($beforeContentArray['remarks'] !== $afterContentArray['remarks']) {
                array_push($returnBeforeDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.remarks',
                    'target_param_content' => $beforeContentArray['remarks']
                ]);    
            }
            if ($beforeContentArray['doc_info'] !== $afterContentArray['doc_info']) {
                array_push($returnBeforeDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.description',
                    'target_param_content' => $beforeContentArray['doc_info']
                ]);    
            }
            if ($beforeContentArray['sign_level'] !== $afterContentArray['sign_level']) {
                array_push($returnBeforeDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.sign-level',
                    'target_param_content' => $beforeContentArray['sign_level']
                ]);    
            }

            return $returnBeforeDealArray;
    }

    /**
     * 取引書類更新afterContent取得
     *
     * @param object $beforeContent
     * @param object $afterContet
     * @return array
     */
    public function afterContentArrayDeal(object $beforeContent, object $afterContet): array
    {
            $beforeContentArray = json_decode(json_encode($beforeContent), true);
            $afterContentArray   = json_decode(json_encode($afterContet), true);

            $returnAfterDealArray['operation'] = [];

            if ($beforeContentArray['template_id'] !== $afterContentArray['template_id']) {
                array_push($returnAfterDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.template',
                    'target_param_content' => $afterContentArray['template_id']
                ]);    
            }
            if ($beforeContentArray['doc_type_id'] !== $afterContentArray['doc_type_id']) {
                
                array_push($returnAfterDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.document-type',
                    'target_param_content' => $afterContentArray['doc_type_id']
                ]);    
            }
            if ($beforeContentArray['issue_date'] !== $afterContentArray['issue_date']) {
                array_push($returnAfterDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.issue-date',
                    'target_param_content' => $afterContentArray['issue_date']
                ]);    
            }
            if ($beforeContentArray['expiry_date'] !== $afterContentArray['expiry_date']) {
                array_push($returnAfterDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.expiry-date',
                    'target_param_content' => $afterContentArray['expiry_date']
                ]);    
            }
            // TODO: ラベル無し
            if ($beforeContentArray['payment_date'] !== $afterContentArray['payment_date']) {
                array_push($returnAfterDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.payment-date',
                    'target_param_content' => $afterContentArray['payment_date']
                ]);    
            }
            if ($beforeContentArray['transaction_date'] !== $afterContentArray['transaction_date']) {
                array_push($returnAfterDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.deal-date',
                    'target_param_content' => $afterContentArray['transaction_date']
                ]);    
            }
            if ($beforeContentArray['download_date'] !== $afterContentArray['download_date']) {
                array_push($returnAfterDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.download-date',
                    'target_param_content' => $afterContentArray['download_date']
                ]);    
            }
            if ($beforeContentArray['doc_no'] !== $afterContentArray['doc_no']) {
                array_push($returnAfterDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.document.no',
                    'target_param_content' => $afterContentArray['doc_no']
                ]);    
            }
            if ($beforeContentArray['ref_doc_no'] !== $afterContentArray['ref_doc_no']) {
                array_push($returnAfterDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.ref-document.no',
                    'target_param_content' => $afterContentArray['ref_doc_no']
                ]);    
            }
            if ($beforeContentArray['product_name'] !== $afterContentArray['product_name']) {
                array_push($returnAfterDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.product-name',
                    'target_param_content' => $afterContentArray['product_name']
                ]);    
            }
            if ($beforeContentArray['title'] !== $afterContentArray['title']) {
                array_push($returnAfterDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.title',
                    'target_param_content' => $afterContentArray['title']
                ]);    
            }
            if ($beforeContentArray['amount'] !== $afterContentArray['amount']) {
                array_push($returnAfterDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.amount',
                    'target_param_content' => $afterContentArray['amount']
                ]);    
            }
            if ($beforeContentArray['currency_id'] !== $afterContentArray['currency_id']) {
                array_push($returnAfterDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.currency',
                    'target_param_content' => $afterContentArray['currency_id']
                ]);    
            }
            if ($beforeContentArray['counter_party_id'] !== $afterContentArray['counter_party_id']) {
                array_push($returnAfterDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.counter-party.id',
                    'target_param_content' => $afterContentArray['counter_party_id']
                ]);    
            }
            if ($beforeContentArray['remarks'] !== $afterContentArray['remarks']) {
                array_push($returnAfterDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.remarks',
                    'target_param_content' => $afterContentArray['remarks']
                ]);    
            }
            if ($beforeContentArray['doc_info'] !== $afterContentArray['doc_info']) {
                array_push($returnAfterDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.description',
                    'target_param_content' => $afterContentArray['doc_info']
                ]);    
            }
            if ($beforeContentArray['sign_level'] !== $afterContentArray['sign_level']) {
                array_push($returnAfterDealArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.sign-level',
                    'target_param_content' => $afterContentArray['sign_level']
                ]);    
            }

            return $returnAfterDealArray;
    }

    /**
     * 契約書類更新beforeContent
     *
     * @param object $beforeContent
     * @param object $afterContet
     * @return array
     */
    public function beforeContetArrayContract(object $beforeContent, object $afterContet): array
    {
            $beforeContentArray = json_decode(json_encode($beforeContent), true);
            $afterContentArray   = json_decode(json_encode($afterContet), true);

            $returnBeforeContetArray['operation'] = [];

            if ($beforeContentArray['template_id'] !== $afterContentArray['template_id']) {
                array_push($returnBeforeContetArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.template',
                    'target_param_content' => $beforeContentArray['template_id']
                ]);    
            }
            if ($beforeContentArray['doc_type_id'] !== $afterContentArray['doc_type_id']) {
                
                array_push($returnBeforeContetArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.document-type',
                    'target_param_content' => $beforeContentArray['doc_type_id']
                ]);    
            }
            if ($beforeContentArray['cont_start_date'] !== $afterContentArray['cont_start_date']) {
                array_push($returnBeforeContetArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.contract-date.start',
                    'target_param_content' => $beforeContentArray['cont_start_date']
                ]);    
            }
            if ($beforeContentArray['cont_end_date'] !== $afterContentArray['cont_end_date']) {
                array_push($returnBeforeContetArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.contract-date.end',
                    'target_param_content' => $beforeContentArray['cont_end_date']
                ]);    
            }
            if ($beforeContentArray['conc_date'] !== $afterContentArray['conc_date']) {
                array_push($returnBeforeContetArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.conclusion-date',
                    'target_param_content' => $beforeContentArray['conc_date']
                ]);    
            }
            if ($beforeContentArray['effective_date'] !== $afterContentArray['effective_date']) {
                array_push($returnBeforeContetArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.effective-date',
                    'target_param_content' => $beforeContentArray['effective_date']
                ]);    
            }
            if ($beforeContentArray['cancel_date'] !== $afterContentArray['cancel_date']) {
                array_push($returnBeforeContetArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.cancel-date',
                    'target_param_content' => $beforeContentArray['cancel_date']
                ]);    
            }
            if ($beforeContentArray['expiry_date'] !== $afterContentArray['expiry_date']) {
                array_push($returnBeforeContetArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.expiry-date',
                    'target_param_content' => $beforeContentArray['expiry_date']
                ]);    
            }
            if ($beforeContentArray['doc_no'] !== $afterContentArray['doc_no']) {
                array_push($returnBeforeContetArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.document.no',
                    'target_param_content' => $beforeContentArray['doc_no']
                ]);    
            }
            if ($beforeContentArray['ref_doc_no'] !== $afterContentArray['ref_doc_no']) {
                array_push($returnBeforeContetArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.ref-document.no',
                    'target_param_content' => $beforeContentArray['ref_doc_no']
                ]);    
            }
            if ($beforeContentArray['product_name'] !== $afterContentArray['product_name']) {
                array_push($returnBeforeContetArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.product-name',
                    'target_param_content' => $beforeContentArray['product_name']
                ]);    
            }
            if ($beforeContentArray['title'] !== $afterContentArray['title']) {
                array_push($returnBeforeContetArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.title',
                    'target_param_content' => $beforeContentArray['title']
                ]);    
            }
            if ($beforeContentArray['amount'] !== $afterContentArray['amount']) {
                array_push($returnBeforeContetArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.amount',
                    'target_param_content' => $beforeContentArray['amount']
                ]);    
            }
            if ($beforeContentArray['currency_id'] !== $afterContentArray['currency_id']) {
                array_push($returnBeforeContetArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.currency',
                    'target_param_content' => $beforeContentArray['currency_id']
                ]);    
            }
            if ($beforeContentArray['counter_party_id'] !== $afterContentArray['counter_party_id']) {
                array_push($returnBeforeContetArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.counter-party.id',
                    'target_param_content' => $beforeContentArray['counter_party_id']
                ]);    
            }
            if ($beforeContentArray['remarks'] !== $afterContentArray['remarks']) {
                array_push($returnBeforeContetArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.remarks',
                    'target_param_content' => $beforeContentArray['remarks']
                ]);    
            }
            if ($beforeContentArray['doc_info'] !== $afterContentArray['doc_info']) {
                array_push($returnBeforeContetArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.description',
                    'target_param_content' => $beforeContentArray['doc_info']
                ]);    
            }
            if ($beforeContentArray['sign_level'] !== $afterContentArray['sign_level']) {
                array_push($returnBeforeContetArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.sign-level',
                    'target_param_content' => $beforeContentArray['sign_level']
                ]);    
            }

            return $returnBeforeContetArray;
    }

    /**
     * 契約書類更新aftercontent
     *
     * @param object $beforeContent
     * @param object $afterContet
     * @return array
     */    
    public function afterContentArrayContract(object $beforeContent, object $afterContet): array
    {
            $beforeContentArray = json_decode(json_encode($beforeContent), true);
            $afterContentArray   = json_decode(json_encode($afterContet), true);

            $returnAfterContentArray['operation'] = [];

            if ($beforeContentArray['template_id'] !== $afterContentArray['template_id']) {
                array_push($returnAfterContentArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.template',
                    'target_param_content' => $afterContentArray['template_id']
                ]);    
            }
            if ($beforeContentArray['doc_type_id'] !== $afterContentArray['doc_type_id']) {
                array_push($returnAfterContentArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.document-type',
                    'target_param_content' => $afterContentArray['doc_type_id']
                ]);    
            }
            if ($beforeContentArray['cont_start_date'] !== $afterContentArray['cont_start_date']) {
                array_push($returnAfterContentArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.contract-date.start',
                    'target_param_content' => $afterContentArray['cont_start_date']
                ]);    
            }
            if ($beforeContentArray['cont_end_date'] !== $afterContentArray['cont_end_date']) {
                array_push($returnAfterContentArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.contract-date.end',
                    'target_param_content' => $afterContentArray['cont_end_date']
                ]);    
            }
            if ($beforeContentArray['conc_date'] !== $afterContentArray['conc_date']) {
                array_push($returnAfterContentArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.conclusion-date',
                    'target_param_content' => $afterContentArray['conc_date']
                ]);    
            }
            if ($beforeContentArray['effective_date'] !== $afterContentArray['effective_date']) {
                array_push($returnAfterContentArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.effective-date',
                    'target_param_content' => $afterContentArray['effective_date']
                ]);    
            }
            if ($beforeContentArray['cancel_date'] !== $afterContentArray['cancel_date']) {
                array_push($returnAfterContentArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.cancel-date',
                    'target_param_content' => $afterContentArray['cancel_date']
                ]);    
            }
            if ($beforeContentArray['expiry_date'] !== $afterContentArray['expiry_date']) {
                array_push($returnAfterContentArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.expiry-date',
                    'target_param_content' => $afterContentArray['expiry_date']
                ]);    
            }
            if ($beforeContentArray['doc_no'] !== $afterContentArray['doc_no']) {
                array_push($returnAfterContentArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.document.no',
                    'target_param_content' => $afterContentArray['doc_no']
                ]);    
            }
            if ($beforeContentArray['ref_doc_no'] !== $afterContentArray['ref_doc_no']) {
                array_push($returnAfterContentArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.ref-document.no',
                    'target_param_content' => $afterContentArray['ref_doc_no']
                ]);    
            }
            if ($beforeContentArray['product_name'] !== $afterContentArray['product_name']) {
                array_push($returnAfterContentArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.product-name',
                    'target_param_content' => $afterContentArray['product_name']
                ]);    
            }
            if ($beforeContentArray['title'] !== $afterContentArray['title']) {
                array_push($returnAfterContentArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.title',
                    'target_param_content' => $afterContentArray['title']
                ]);    
            }
            if ($beforeContentArray['amount'] !== $afterContentArray['amount']) {
                array_push($returnAfterContentArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.amount',
                    'target_param_content' => $afterContentArray['amount']
                ]);    
            }
            if ($beforeContentArray['currency_id'] !== $afterContentArray['currency_id']) {
                array_push($returnAfterContentArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.currency',
                    'target_param_content' => $afterContentArray['currency_id']
                ]);    
            }
            if ($beforeContentArray['counter_party_id'] !== $afterContentArray['counter_party_id']) {
                array_push($returnAfterContentArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.counter-party.id',
                    'target_param_content' => $afterContentArray['counter_party_id']
                ]);    
            }
            if ($beforeContentArray['remarks'] !== $afterContentArray['remarks']) {
                array_push($returnAfterContentArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.remarks',
                    'target_param_content' => $afterContentArray['remarks']
                ]);    
            }
            if ($beforeContentArray['doc_info'] !== $afterContentArray['doc_info']) {
                array_push($returnAfterContentArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.description',
                    'target_param_content' => $afterContentArray['doc_info']
                ]);    
            }
            if ($beforeContentArray['sign_level'] !== $afterContentArray['sign_level']) {
                array_push($returnAfterContentArray['operation'], [
                    'message_key' => 'common.message.document.operation-history',
                    'operation_label' => 'document.label.item.sign-level',
                    'target_param_content' => $afterContentArray['sign_level']
                ]);    
            }

            return $returnAfterContentArray;
    }
}
