<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Document;

use App\Accessers\DB\Log\System\LogSystemAccess;
use App\Accessers\DB\Log\System\LogDocAccess;
use App\Accessers\DB\Log\System\LogDocOperation;
use App\Accessers\DB\Document\DocumentWorkFlow;
use App\Accessers\DB\Document\DocumentPermissionArchive;
use App\Accessers\DB\Document\DocumentArchive;
use App\Accessers\DB\Document\DocumentPermissionInternal;
use App\Accessers\DB\Document\DocumentInternal;
use App\Accessers\DB\Document\DocumentPermissionTransaction;
use App\Accessers\DB\Document\DocumentDeal;
use App\Accessers\DB\Document\DocumentPermissionContract;
use App\Accessers\DB\Document\DocumentContract;
use App\Accessers\DB\Document\DocumentStorageContract;
use App\Accessers\DB\Document\DocumentStorageTransaction;
use App\Accessers\DB\Document\DocumentStorageInternal;
use App\Accessers\DB\Document\DocumentStorageArchive;
use App\Domain\Entities\Document\DocumentDetail as DocumentEntity;
use App\Domain\Entities\Document\DocumentDelete as DocumentDeleteEntity;
use App\Domain\Repositories\Interface\Document\DocumentListRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class DocumentListRepository implements DocumentListRepositoryInterface
{
    /** @var int */
    protected const DOC_CONTRACT_TYPE = 0;
    /** @var int */
    protected const DOC_DEAL_TYPE = 1;
    /** @var int */
    protected const DOC_INTERNAL_TYPE = 2;
    /** @var int */
    protected const DOC_ARCHIVE_TYPE = 3;

    /**
     * @var Document
     */
    private DocumentDeal $docDeal;
    private DocumentArchive $docArchive;
    private DocumentContract $docContract;
    private DocumentInternal $docInternal;
    private DocumentPermissionInternal $docPermissionInternal;
    private DocumentPermissionContract $docPermissionContract;
    private DocumentPermissionArchive $docPermissionArchive;
    private DocumentPermissionTransaction $docPermissionTransaction;
    private DocumentWorkFlow $docWorkFlow;
    private LogDocAccess $logDocAccess;
    private LogDocOperation $logDocOperation;
    private LogSystemAccess $logSystemAccess;
    private DocumentStorageContract $docStorageContract;
    private DocumentStorageTransaction $docStorageTransaction;
    private DocumentStorageInternal $docStorageInternal;
    private DocumentStorageArchive $docStorageArchive;

    /**
     * @param DocumentDeal $docDeal
     * @param DocumentArchive $docArchive
     * @param DocumentContract $docContract
     * @param DocumentInternal $docInternal
     * @param DocumentPermissionArchive $docPermissionArchive
     * @param DocumentPermissionInternal $docPermissionContract
     * @param DocumentPermissionContract $document
     * @param DocumentPermissionTransaction $docPermissionTransaction
     * @param DocumentWorkFlow $docWorkFlow
     * @param LogDocAccess $logDocAccess
     * @param LogDocOperation $logDocOperation
     * @param LogSystemAccess $logSystemAccess
     * @param DocumentStorageContract $docStorageContract
     * @param DocumentStorageTransaction $docStorageTransaction
     * @param DocumentStorageInternal $docStorageInternal
     * @param DocumentStorageArchive $docStorageArchive
     */
    public function __construct(
        DocumentDeal $docDeal,
        DocumentArchive $docArchive,
        DocumentContract $docContract,
        DocumentInternal $docInternal,
        DocumentPermissionArchive $docPermissionArchive,
        DocumentPermissionInternal $docPermissionInternal,
        DocumentPermissionContract $docPermissionContract,
        DocumentPermissionTransaction $docPermissionTransaction,
        DocumentWorkFlow $docWorkFlow,
        LogDocAccess $logDocAccess,
        LogDocOperation $logDocOperation,
        LogSystemAccess $logSystemAccess,
        DocumentStorageContract $docStorageContract,
        DocumentStorageTransaction $docStorageTransaction,
        DocumentStorageInternal $docStorageInternal,
        DocumentStorageArchive $docStorageArchive
    ) {
        $this->docDeal = $docDeal;
        $this->docArchive = $docArchive;
        $this->docContract = $docContract;
        $this->docInternal = $docInternal;
        $this->docPermissionArchive = $docPermissionArchive;
        $this->docPermissionInternal = $docPermissionInternal;
        $this->docPermissionContract = $docPermissionContract;
        $this->docPermissionTransaction = $docPermissionTransaction;
        $this->docWorkFlow = $docWorkFlow;
        $this->logDocAccess = $logDocAccess;
        $this->logDocOperation = $logDocOperation;
        $this->logSystemAccess = $logSystemAccess;
        $this->docStorageContract = $docStorageContract;
        $this->docStorageTransaction = $docStorageTransaction;
        $this->docStorageInternal = $docStorageInternal;
        $this->docStorageArchive = $docStorageArchive;
    }

    /**
     * @param int|null $companyId
     * @param int|null $categoryId
     * @param int|null $userId
     * @param int|null $userType
     * @param string|null $ipAddress
     * @param int|null $documentId
     * @param string|null $accessContent
     * @param JsonResponse|null $beforeContent
     * @param JsonResponse|null $afterContet
     * @return bool
     */
    public function getDeleteLog(
        int $companyId = null,
        int $categoryId = null,
        int $userId = null,
        int $userType = null,
        string $ipAddress = null,
        int $documentId = null,
        string $accessContent = null,
        JsonResponse $beforeContent = null,
        JsonResponse $afterContet = null
    ): bool
    {
        // アクセスログを出力する（登録処理）
        $blAccess = $this->logDocAccess->insert($companyId, $categoryId, $documentId, $userId, $userType, $ipAddress, $accessContent);
        // 操作ログを出力する（登録処理）
        $blOperation = $this->logDocOperation->insert($companyId, $categoryId, $documentId, $userId, $beforeContent, $afterContet, $ipAddress);

        if (!$blAccess || !$blOperation) {
            throw new Exception("ログが出力できません。");
        }

        return true;
    }

    /**
     * @param int $categoryId
     * @param int $documentId
     * @return DocumentDeleteEntity
     */
    public function getBeforOrAfterDeleteArchive(int $companyId, int $documentId): DocumentDeleteEntity
    {
        $archive = $this->docArchive->getBeforeOrAfterData($companyId, $documentId);
        $perArchive = $this->docPermissionArchive->getBeforeOrAfterData($companyId, $documentId);
        $stoArchive = $this->docStorageArchive->getBeforeOrAfterData($companyId, $documentId);
        
        if (empty($archive) && empty($perArchive) && empty($stoArchive)) {
            return new DocumentDeleteEntity();
        }

        return new DocumentDeleteEntity($archive, $perArchive, $stoArchive);
    }

    /**
     * @param int $categoryId
     * @param int $documentId
     * @return DocumentDeleteEntity
     */
    public function getBeforOrAfterDeleteInternal(int $companyId, int $documentId): DocumentDeleteEntity
    {
        $internal = $this->docInternal->getBeforeOrAfterData($companyId, $documentId);
        $perInternal = $this->docPermissionInternal->getBeforeOrAfterData($companyId, $documentId);
        $stoInternal = $this->docStorageInternal->getBeforeOrAfterData($companyId, $documentId);
        
        if (empty($internal) && empty($perInternal) && empty($stoInternal)) {
            return new DocumentDeleteEntity();
        }

        return new DocumentDeleteEntity($internal, $perInternal, $stoInternal);
    }

    /**
     * @param int $categoryId
     * @param int $documentId
     * @return DocumentDeleteEntity
     */
    public function getBeforOrAfterDeleteDeal(int $companyId, int $documentId): DocumentDeleteEntity
    {
        $deal = $this->docDeal->getBeforeOrAfterData($companyId, $documentId);
        $perDeal = $this->docPermissionTransaction->getBeforeOrAfterData($companyId, $documentId);
        $stoDeal = $this->docStorageTransaction->getBeforeOrAfterData($companyId, $documentId);
        
        if (empty($deal) && empty($perDeal) && empty($stoDeal)) {
            return new DocumentDeleteEntity();
        }

        return new DocumentDeleteEntity($deal, $perDeal, $stoDeal);
    }

    /**
     * @param int $categoryId
     * @param int $documentId
     * @return DocumentDeleteEntity
     */
    public function getBeforOrAfterDeleteContract(int $companyId, int $documentId): DocumentDeleteEntity
    {
        $contract = $this->docContract->getBeforeOrAfterData($companyId, $documentId);
        $perContract = $this->docPermissionContract->getBeforeOrAfterData($companyId, $documentId);
        $stoContract = $this->docStorageContract->getBeforeOrAfterData($companyId, $documentId);
        
        if (empty($contract) && empty($perContract) && empty($stoContract)) {
            return new DocumentDeleteEntity();
        }

        return new DocumentDeleteEntity($contract, $perContract, $stoContract);
    }

    /**
     * @param int $userId
     * @param int $companyId
     * @param int $documentId
     * @param int $updateDatetime
     * @return bool
     */
    public function getDeleteArchive(int $userId, int $companyId, int $documentId, int $updateDatetime): bool
    {
        $blArchive = $this->docArchive->getDelete($userId, $companyId, $documentId, $updateDatetime);
        $blPerArchive = $this->docPermissionArchive->getDelete($userId, $companyId, $documentId);
        $blStoArchive = $this->docStorageArchive->getDelete($userId, $companyId, $documentId, $updateDatetime);

        if (!$blArchive || !$blPerArchive || !$blStoArchive) {
            throw new Exception('登録書類テーブルおよび登録書類閲覧権限および登録書類容量を削除できません。');
        }

        return true;
    }

    /**
     * @param int $userId
     * @param int $companyId
     * @param int $documentId
     * @param int $updateDatetime
     * @return bool
     */
    public function getDeleteInternal(int $userId, int $companyId, int $documentId, int $updateDatetime): bool
    {
        $blInternal = $this->docInternal->getDelete($userId, $companyId, $documentId, $updateDatetime);
        $blPerInternal = $this->docPermissionInternal->getDelete($userId, $companyId, $documentId);
        $blStoInternal = $this->docStorageInternal->getDelete($userId, $companyId, $documentId, $updateDatetime);
        
        if (!$blInternal || !$blPerInternal || !$blStoInternal) {
            throw new Exception('社内書類テーブルおよび社内書類閲覧権限および社内書類容量を削除できません。');
        }

        return true;
    }

    /**
     * @param int $userId
     * @param int $companyId
     * @param int $documentId
     * @param int $updateDatetime
     * @return bool
     */
    public function getDeleteDeal(int $userId, int $companyId, int $documentId, int $updateDatetime): bool
    {
        $blDeal = $this->docDeal->getDelete($userId, $companyId, $documentId, $updateDatetime);
        $blPerTransaction = $this->docPermissionTransaction->getDelete($userId, $companyId, $documentId);
        $blStoTransaction = $this->docStorageTransaction->getDelete($userId, $companyId, $documentId, $updateDatetime);
        
        if (!$blDeal || !$blPerTransaction || !$blStoTransaction) {
            throw new Exception('取引書類テーブルおよび取引書類閲覧権限および取引書類容量を削除できません。');
        }

        return true;
    }

    /**
     * @param int $userId
     * @param int $companyId
     * @param int $documentId
     * @param int $updateDatetime
     * @return bool
     */
    public function getDeleteContract(int $userId, int $companyId, int $documentId, int $updateDatetime): bool
    {
        $blContract = $this->docContract->getDelete($userId, $companyId, $documentId, $updateDatetime);
        $blPerContract = $this->docPermissionContract->getDelete($userId, $companyId, $documentId);
        $blStoContract = $this->docStorageContract->getDelete($userId, $companyId, $documentId, $updateDatetime);
        
        if (!$blContract || !$blPerContract || !$blStoContract) {
            throw new Exception('契約書類テーブルおよび契約書類閲覧権限および契約書類容量を削除できません。');
        }

        return true;
    }

    /**
     * @param int $categoryId
     * @param int $documentId
     * @param int $companyId
     * @param int $userId
     * @return DocumentEntity
     */
    public function getDetail(int $categoryId, int $documentId, int $companyId, int $userId): DocumentEntity
    {
        $docDetailList = $this->getDocumentList($categoryId, $documentId, $companyId, $userId);
        $logList = $this->getLogList($documentId, $categoryId, $companyId);
        $workFlowList = $this->getWorkFlowList($documentId, $categoryId, $companyId);
        return new DocumentEntity($docDetailList["docList"], $docDetailList["docPermissionList"], $workFlowList, $logList["logAccessList"], $logList["logOperationList"]);
    }

    /**
     * @param int $categoryId
     * @param int $documentId
     * @param int $companyId
     * @param int $userId
     * @return array|null
     */
    public function getDocumentList(int $categoryId, int $documentId, int $companyId, int $userId): ?array
    {
        switch($categoryId) {
            case Self::DOC_CONTRACT_TYPE:
                // 書類カテゴリが契約書類で設定されていた場合、データ抽出
                $docList = $this->docContract->getList($documentId, $companyId, $userId);
                $docPermissionList = $this->docPermissionContract->getList($documentId, $companyId, $userId);
                break;
            case Self::DOC_DEAL_TYPE:
                // 書類カテゴリが取引書類で設定されていた場合、データ抽出
                $docList = $this->docDeal->getList($documentId, $companyId, $userId);
                $docPermissionList = $this->docPermissionTransaction->getList($documentId, $companyId, $userId);
                break;
            case Self::DOC_INTERNAL_TYPE:
                // 書類カテゴリが社内書類で設定されていた場合、データ抽出
                $docList = $this->docInternal->getList($documentId, $companyId, $userId);
                $docPermissionList = $this->docPermissionInternal->getList($documentId, $companyId, $userId);
                break;
            case Self::DOC_ARCHIVE_TYPE:
                // 書類カテゴリが登録書類で設定されていた場合、データ抽出
                $docList = $this->docArchive->getList($documentId, $companyId, $userId);
                $docPermissionList = $this->docPermissionArchive->getList($documentId, $companyId, $userId);
                break;
        }
        return ["docList" => $docList, "docPermissionList" => $docPermissionList];
    }

    /**
     * @param int $documentId
     * @param int $categoryId
     * @param int $companyId
     * @return array|null
     */
    public function getLogList(int $documentId, int $categoryId, int $companyId): ?array
    {
        // 履歴情報・操作ログ情報を取得する
        $logOperationList = $this->logDocOperation->getList($documentId, $categoryId, $companyId);
        // アクセスログ情報を取得する
        $logAccessList = $this->logDocAccess->getList($documentId, $categoryId, $companyId);
        return ["logAccessList" => $logAccessList, "logOperationList" => $logOperationList];
    }

    /**
     * @param int $documentId
     * @param int $categoryId
     * @param int $companyId
     * @return \stdClass|null
     */
    public function getWorkFlowList(int $documentId, int $categoryId, int $companyId): ?\stdClass
    {
        // ワークフォーロー
        $docWorkflowList = $this->docWorkFlow->getList($documentId, $categoryId, $companyId);
        return $docWorkflowList;
    }

    /**
     * @param array $importLogData
     * @return bool
     */
    public function getInsLog(array $importLogData): ?bool
    {
        $companyId = $importLogData['company_id'];
        $categoryId = $importLogData['category_id'];
        $documentId = $importLogData['document_id'];
        $userId = $importLogData['user_id'];
        $fullName = $importLogData['user_name'];
        $userType = $importLogData['user_type'];
        $ipAddress = $importLogData['ip_address'];
        $accessContent = $importLogData['access_content'];
        $beforeContent = $importLogData['before_content'];
        $afterContet = $importLogData['after_contet'];
        $accessFuncName = $importLogData['access_func_name'];
        $action = $importLogData['action'];

        // アクセスログを出力する（登録処理）
        $this->logDocAccess->insert($companyId, $categoryId, $documentId, $userId, $userType, $ipAddress, $accessContent);
        // 操作ログを出力する（登録処理）
        $this->logDocOperation->insert($companyId, $categoryId, $documentId, $userId, $beforeContent, $afterContet, $ipAddress);
        // システムアクセスログを出力する（登録処理）
        $this->logSystemAccess->insert($companyId, $userId, $fullName, $ipAddress, $accessFuncName, $action);

        return true;
    }
}
