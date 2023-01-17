<?php
declare(strict_types=1);
namespace App\Domain\Services\Download;

use Carbon\CarbonImmutable;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Domain\Entities\Document\DocumentDetail;
use App\Domain\Entities\Document\DocumentDelete;
use App\Domain\Repositories\Interface\Download\DownloadFileServiceInterface;

class DownloadManagerService
{

    /** @var DownloadFileServiceInterface */
    private DownloadFileServiceInterface $documentRepository;

    /**
     * @param DownloadFileServiceInterface $documentRepository
     */
    public function __construct(DownloadFileServiceInterface $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

    
    public function getFile(int $mUserId, int $mUserCompanyId ,string $token, string $nowDate)
    {
        try {
            // トークンデータオブジェクト取得
            $getTokenResult = $this->documentRepository->getToken(token: $token);
            
            // トークンデータのdl_file_idを取得
            $getTokenDlFileId = json_decode($getTokenResult->getData()->data)->dl_file_id;
            
            // 取得したトークンの有効期限のチェック
            if ($getTokenResult > CarbonImmutable::now()) {
                throw new Exception('有効期限が切れています。');
            }

            // ダウンロードパスオブジェクト取得
            $getDlFileIdResult = $this->documentRepository->getDlFilePath(mUserId: $mUserId, mUserCompanyId: $mUserCompanyId, getTokenDlFileId: $getTokenDlFileId);
            return var_export($getDlFileIdResult->getData()->dl_file_name);

            //ファイル名を取得
            $fileName = $getDlFileIdResult->getData()->dl_file_name;

            //  AzureStorageから書類ファイルを取得 // TODO: 取得パス不明
            

            // ヘッダー定義      
            // header('Content-Type: application/zip');
            // header("Content-Disposition: attachment; filename={$fileName}");
            // header('Content-Transfer-Encoding: binary');

            // ファイル出力
            //  $downloadRsult = readfile($filePath . $fileName);
            
            
        } catch (Exception $e) {
            throw $e;
            return false;
        }
    }
}
