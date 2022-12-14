<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Document;

use App\Accessers\DB\Document\DocumentArchive;
use App\Accessers\DB\Document\DocumentContract;
use App\Accessers\DB\Document\DocumentDeal;
use App\Accessers\DB\Document\DocumentInternal;
use App\Accessers\DB\Document\DocumentPermissionArchive;
use App\Accessers\DB\Document\DocumentPermissionContract;
use App\Accessers\DB\Document\DocumentPermissionInternal;
use App\Accessers\DB\Document\DocumentPermissionTransaction;
use App\Accessers\DB\Document\DocumentWorkFlow;
use App\Accessers\DB\Log\System\LogDocAccess;
use App\Accessers\DB\Log\System\LogDocOperation;
use App\Domain\Constant;
use App\Domain\Entities\Document\Document;
use App\Domain\Entities\Organization\User\AccessUser;
use App\Domain\Entities\Organization\User\OperationUser;
use App\Domain\Entities\Organization\User\SignedUser;
use App\Domain\Entities\Organization\User\TimestampUser;
use App\Domain\Repositories\Interface\Document\DocumentDetailRepositoryInterface;

/**
 * 書類詳細リポジトリ
 */
class DocumentDetailRepository implements DocumentDetailRepositoryInterface
{
    /** @var DocumentDeal */
    private DocumentDeal $docDeal;
    /** @var DocumentArchive */
    private DocumentArchive $docArchive;
    /** @var DocumentContract */
    private DocumentContract $docContract;
    /** @var DocumentInternal */
    private DocumentInternal $docInternal;
    /** @var DocumentPermissionInternal */
    private DocumentPermissionInternal $docPermissionInternal;
    /** @var DocumentPermissionContract */
    private DocumentPermissionContract $docPermissionContract;
    /** @var DocumentPermissionArchive */
    private DocumentPermissionArchive $docPermissionArchive;
    /** @var DocumentPermissionTransaction */
    private DocumentPermissionTransaction $docPermissionTransaction;
    /** @var LogDocAccess */
    private LogDocAccess $logDocAccess;
    /** @var LogDocOperation */
    private LogDocOperation $logDocOperation;
    /** @var DocumentWorkFlow */
    private DocumentWorkFlow $documentWorkFlow;

    /**
     * @param DocumentDeal $docDeal
     * @param DocumentArchive $docArchive
     * @param DocumentContract $docContract
     * @param DocumentInternal $docInternal
     * @param DocumentPermissionArchive $docPermissionArchive
     * @param DocumentPermissionInternal $docPermissionInternal
     * @param DocumentPermissionContract $docPermissionContract
     * @param DocumentPermissionTransaction $docPermissionTransaction
     * @param LogDocAccess $logDocAccess
     * @param LogDocOperation $logDocOperation
     * @param DocumentWorkFlow $documentWorkFlow
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
        LogDocAccess $logDocAccess,
        LogDocOperation $logDocOperation,
        DocumentWorkFlow $documentWorkFlow
    ) {
        $this->docDeal     = $docDeal;
        $this->docArchive  = $docArchive;
        $this->docContract = $docContract;
        $this->docInternal = $docInternal;
        $this->docPermissionArchive = $docPermissionArchive;
        $this->docPermissionInternal = $docPermissionInternal;
        $this->docPermissionContract = $docPermissionContract;
        $this->docPermissionTransaction = $docPermissionTransaction;
        $this->logDocAccess = $logDocAccess;
        $this->logDocOperation = $logDocOperation;
        $this->documentWorkFlow = $documentWorkFlow;
    }

    /**
     * 書類詳細を取得
     * @param int $categoryId
     * @param int $documentId
     * @param int $companyId
     * @param int $userId
     * @return Document
     */
    public function getDetail(int $categoryId, int $documentId, int $companyId, int $userId): Document
    {
        $documentData = $this->getDocumentData($categoryId, $documentId, $companyId, $userId);

        if (empty($docDetailList['documentData']) && empty($docDetailList['permissionData'])) {
            return new Document();
        }

        $documentDetail = $documentData['documentData'];
        $permission     = $documentData['permissionData'];

        return new Document([
            'documentId' => $documentDetail->document_id ?? null,
            'categoryId' => $documentDetail->category_id ?? null,
            'statusId'   => $documentDetail->status_id ?? null,
            'docTypeId'  => $documentDetail->doc_type_id ?? null,
            'title' => $documentDetail->title ?? null,
            'amount' => $documentDetail->amount ?? null,
            'currencyId' => $documentDetail->currency_id ?? null,
            'contStartDate' => $documentDetail->cont_start_date ?? null,
            'contEndDate' => $documentDetail->cont_end_date ?? null,
            'concDate' => $documentDetail->conc_date ?? null,
            'effectiveDate' => $documentDetail->effective_date ?? null,
            'docNo' => $documentDetail->doc_no ?? null,
            'refDocNo' => $documentDetail->ref_doc_no ?? null,
            'counterPartyId' => $documentDetail->counter_party_id ?? null,
            'counterPartyName' => $documentDetail->counter_party_name ?? null,
            'remarks' => $documentDetail->remarks ?? null,
            'docInfo' => $documentDetail->doc_info ? json_decode($documentDetail->doc_info) : null, //TODO
            'signLevel' => $documentDetail->sign_level ?? null,
            'productName' => $documentDetail->product_name ?? null,
            'filePath' => $documentDetail->file_path ?? null,
            'pdf' => null,  // TODO 別途実装対応
            'sign_position' => null,// TODO 別途実装対応
            'totalPages' => $documentDetail->total_pages ?? null,
            'appUser' => new SignedUser($permission->family_name ?? null, $permission->first_name ?? null),
            'timestampUser' => $documentDetail->category_id === Constant::DOCUMENT_TYPE_ARCHIVE ?
                new TimestampUser($permission->family_name ?? null, $permission->first_name ?? null):
                null,
            'updateDatetime' => $documentDetail->update_time ?? null
        ]);
    }

    /**
     * アクセス履歴情報取得
     * @param int $categoryId
     * @param int $documentId
     * @param int $companyId
     * @return array
     */
    public function getAccessLog(int $categoryId, int $documentId, int $companyId): array
    {
        $logList =  $this->logDocAccess->getList($documentId, $categoryId, $companyId);

        if (empty($logList)) {
            return [];
        }

        $collection = [];
        foreach ($logList as $log) {
            $collection[] = new AccessUser(
                createDatetime: $log->create_datetime ?? null,
                familyName: $log->family_name ?? null,
                firstName: $log->first_name ?? null
            );
        }
        return $collection;
    }

    /**
     * 変更履歴情報取得
     * @param int $categoryId
     * @param int $documentId
     * @param int $companyId
     * @return array
     */
    public function getOperationLog(int $categoryId, int $documentId, int $companyId): array
    {
        $logList = $this->logDocOperation->getList($documentId, $categoryId, $companyId);

        if (empty($logList)) {
            return [];
        }

        $collection = [];
        foreach ($logList as $log) {
            $content = [];
            if (!empty($log->before_content) && !empty($log->after_content)) {
                //TODO json構造を確認して処理変更
                $beforeContent = json_decode($log->before_content);
                $afterContent  = json_decode($log->after_content);
            }
            $collection[] = new OperationUser(
                $log->family_name ?? null,
                $log->first_name ?? null,
                $log->create_datetime ?? null,
                empty($content) ? null : $content
            );
        }
        return $collection;
    }

    /**
     * TODO 別途実装対応
     * 選択署名者（ゲスト）取得
     * @param int $documentId
     * @param int $categoryId
     * @param int $companyId
     * @return array
     */
    public function getSelectSignGuestUsers(int $documentId, int $categoryId, int $companyId): array
    {
//        return $this->documentWorkFlow->getList($documentId, $categoryId, $companyId);
        return [];
    }

    /**
     * TODO 別途実装対応
     * 選択署名者（ホスト）取得
     * @param int $documentId
     * @param int $categoryId
     * @param int $companyId
     * @return array
     */
    public function getSelectSignUser(int $documentId, int $categoryId, int $companyId): array
    {
//        return $this->documentWorkFlow->getList($documentId, $categoryId, $companyId);
        return [];
    }

    /**
     * TODO 別途実装対応
     * 選択署名者（閲覧者）取得
     * @param int $documentId
     * @param int $categoryId
     * @param int $companyId
     * @return array
     */
    public function getSelectViewUser(int $documentId, int $categoryId, int $companyId): array
    {
//        return $this->documentWorkFlow->getList($documentId, $categoryId, $companyId);
        return [];
    }


    /**
     * 書類詳細情報取得
     * @param int $categoryId
     * @param int $documentId
     * @param int $companyId
     * @param int $userId
     * @return array|null
     */
    private function getDocumentData(int $categoryId, int $documentId, int $companyId, int $userId): ?array
    {
        switch($categoryId) {
            case Constant::DOCUMENT_TYPE_CONTRACT:
                // 書類カテゴリが契約書類で設定されていた場合、データ抽出
                $docList = $this->docContract->getList($documentId, $companyId, $userId);
                $docPermissionList = $this->docPermissionContract->getList($documentId, $companyId);
                break;
            case Constant::DOCUMENT_TYPE_DEAL:
                // 書類カテゴリが取引書類で設定されていた場合、データ抽出
                $docList = $this->docDeal->getList($documentId, $companyId, $userId);
                $docPermissionList = $this->docPermissionTransaction->getList($documentId, $companyId);
                break;
            case Constant::DOCUMENT_TYPE_INTERNAL:
                // 書類カテゴリが社内書類で設定されていた場合、データ抽出
                $docList = $this->docInternal->getList($documentId, $companyId, $userId);
                $docPermissionList = $this->docPermissionInternal->getList($documentId, $companyId);
                break;
            case Constant::DOCUMENT_TYPE_ARCHIVE:
                // 書類カテゴリが登録書類で設定されていた場合、データ抽出
                $docList = $this->docArchive->getList($documentId, $companyId, $userId);
                $docPermissionList = $this->docPermissionArchive->getList($documentId, $companyId);
                break;
        }
        return ['documentData' => $docList, "permissionData" => $docPermissionList];
    }
}
