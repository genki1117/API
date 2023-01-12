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

class DocumentDownloadCsvService
{
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
     * CSVダウンロード処理
     *
     * @param integer $mUserId
     * @param integer $mUserCompanyId
     * @param integer $mUserTypeId
     * @param integer $categoryId
     * @param string  $fileName
     * @return bool
     */
    public function downloadCsv(int $mUserId, int $mUserCompanyId, int $mUserTypeId, int $categoryId, string $fileName): ?bool
    {
        try {
            // パス取得
            $csvStoragePath = 'Storage/UploadCsvFile/'; //TODO:定義する場所確認
            $useCsvStoragePath = $csvStoragePath . $mUserCompanyId . '/' . $mUserId . '/' . $fileName;

            // csvヘッダー定義

            // ダウンロード処理

            return true;
        } catch (Exception $e) {
            throw $e;
            return false;
        }
        


    }
}
