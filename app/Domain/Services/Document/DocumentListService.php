<?php
declare(strict_types=1);
namespace App\Domain\Services\Document;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Domain\Entities\Document\DocumentDetail;
use App\Domain\Entities\Document\DocumentDelete;
use App\Domain\Repositories\Interface\Document\DocumentListRepositoryInterface;

class DocumentListService
{
    /** @var int */
    protected const DOC_CONTRACT_TYPE = 0;
    /** @var int */
    protected const DOC_DEAL_TYPE = 1;
    /** @var int */
    protected const DOC_INTERNAL_TYPE = 2;
    /** @var int */
    protected const DOC_ARCHIVE_TYPE = 3;

    /** @var DocumentListRepositoryInterface */
    private DocumentListRepositoryInterface $documentRepository;

    /**
     * @param DocumentListRepositoryInterface $documentRepository
     */
    public function __construct(DocumentListRepositoryInterface $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

    /**
     * @param int $categoryId
     * @param int $documentId
     * @param int $companyId
     * @return DocumentDetail|null
     */
    public function getDetail(int $categoryId, int $documentId, int $companyId, int $userId): ?DocumentDetail
    {
        $documentDetail= $this->documentRepository->getDetail($categoryId, $documentId, $companyId, $userId);
        if (is_null($documentDetail->getDocumentList()) &&
            is_null($documentDetail->getDocumentPermissionList()) &&
            is_null($documentDetail->getDocumentWorkFlow()) &&
            is_null($documentDetail->getLogDocAccess()) &&
            is_null($documentDetail->getLogDocOperation())) {
            return null;
        }
        return $documentDetail;
    }

    /**
     * @param array $importLogData
     * @return bool
     */
    public function getInsLog(array $importLogData): ?bool
    {
        $blInsLog = $this->documentRepository->getInsLog($importLogData);
        return $blInsLog;
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
    public function getLog(int $companyId = null,
                            int $categoryId = null,
                            int $userId = null,
                            int $userType = null,
                            string $ipAddress = null,
                            int $documentId = null,
                            string $accessContent = null,
                            JsonResponse $beforeContent = null,
                            JsonResponse $afterContet = null): ?bool
    {
        DB::beginTransaction();
        try {
            $this->documentRepository->getDeleteLog($companyId, $categoryId, $userId, $userType, $ipAddress, $documentId, $accessContent, $beforeContent, $afterContet);
            DB::commit();
        }
        catch(Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw $e;
        }
        return true;
    }

    /**
     * @param int $categoryId
     * @param int $userId
     * @param int $companyId
     * @param int $documentId
     * @param int $updateDatetime
     * @return bool
     * */
    public function getDelete(int $categoryId, int $userId, int $companyId, int $documentId, int $updateDatetime): ?bool
    {
        // 【１】～【１２】：詳細設計のデータ更新シートで処理番号
        DB::beginTransaction();
        try {
            switch($categoryId) {
                case self::DOC_CONTRACT_TYPE:
                    // 【１】契約書類テーブルおよび【５】契約書類閲覧権限および【９】契約書類容量を論理削除する
                    $this->documentRepository->getDeleteContract($userId, $companyId, $documentId, $updateDatetime);
                    $this->documentRepository->getDeleteContract($userId, $companyId, $documentId, $updateDatetime);
                    break;
                case self::DOC_DEAL_TYPE:
                    // 【２】契約書類テーブルおよび【６】契約書類閲覧権限および【１０】契約書類容量を論理削除する
                    $this->documentRepository->getDeleteDeal($userId, $companyId, $documentId, $updateDatetime);
                    break;
                case self::DOC_INTERNAL_TYPE:
                    // 【３】契約書類テーブルおよび【７】契約書類閲覧権限および【１１】契約書類容量を論理削除する
                    $this->documentRepository->getDeleteInternal($userId, $companyId, $documentId, $updateDatetime);
                    break;
                case self::DOC_ARCHIVE_TYPE:
                    // 【４】契約書類テーブルおよび【８】契約書類閲覧権限および【１２】契約書類容量を論理削除する
                    $this->documentRepository->getDeleteArchive($userId, $companyId, $documentId, $updateDatetime);
                    break;
                default:
                    return true;
                    break;
            }
            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw $e;
        }
        return true;
    }

    /**
     * @param int $categoryId
     * @param int $companyId
     * @param int $documentId
     * @return DocumentDelete|null
     */
    public function getBeforOrAfterDeleteData(int $categoryId, int $companyId, int $documentId): ?DocumentDelete
    {
        switch($categoryId) {
            case self::DOC_CONTRACT_TYPE:
                $data = $this->documentRepository->getBeforOrAfterDeleteContract($companyId, $documentId);
                break;
            case self::DOC_DEAL_TYPE:
                $data = $this->documentRepository->getBeforOrAfterDeleteDeal($companyId, $documentId);
                break;
            case self::DOC_INTERNAL_TYPE:
                $data = $this->documentRepository->getBeforOrAfterDeleteInternal($companyId, $documentId);
                break;
            case self::DOC_ARCHIVE_TYPE:
                $data = $this->documentRepository->getBeforOrAfterDeleteArchive($companyId, $documentId);
                break;
            default:
                return null;
                break;
        }
        
        if (empty($data->getDeleteDocument()) && empty($data->getDeleteDocPermission()) && empty($data->getDeleteDocStorage())) {
            return null;
        }

        return $data;
    }
}
