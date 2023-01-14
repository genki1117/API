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
    /** @var string */
    private $csvStoragePath;


    public function __construct()
    {
        $this->csvStoragePath = 'Storage/UploadCsvFile/';
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
            // 保存場所
             // ユーザ単位のパス
            $userCsvStoragePath = $this->csvStoragePath . $mUserCompanyId . '/' . $mUserId . '/';

            // csvヘッダー定義      
            header('Content-Type: application/octet-stream');
            header("Content-Disposition: attachment; filename={$fileName}");
            header('Content-Transfer-Encoding: binary');

            // ファイル出力
            $downloadRsult = readfile($userCsvStoragePath . $fileName);

            return true;
            
        } catch (Exception $e) {
            throw new Exception('CSVダウンロードに失敗しました。');
            return false;
        }
    }
}
