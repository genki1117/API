<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Document;

use App\Http\Responses\Document\DocumentGetDetailResponse;
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

use App\Domain\Entities\Document\DocumentDetail as DocumentEntity;
use App\Domain\Repositories\Interface\Document\DocumentListRepositoryInterface;

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
     */
    public function __construct(DocumentDeal $docDeal,
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
                                LogSystemAccess $logSystemAccess)
    {
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
        $workFlowList = $DocumentEntity->getWorkFlowList($documentId, $categoryId, $companyId);
        return new DocumentEntity($docDetailList["docList"],$docDetailList["docPermissionList"],$workFlowList,$logList["logAccessList"],$logList["logOperationList"]);
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
