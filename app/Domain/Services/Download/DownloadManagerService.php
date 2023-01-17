<?php
declare(strict_types=1);
namespace App\Domain\Services\Download;

use ZipArchive;
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

    
    public function getFile(string $token, string $nowDate)
    {
        try {
            // トークンデータオブジェクト取得
            $getTokenResult = $this->documentRepository->getToken(token: $token);
            // return var_export($getTokenResult);
            // トークンデータのuser_idを取得
            $mUserId = json_decode($getTokenResult->getData()->data)->user_id;

            // トークンデータのcompany_idを取得
            $mUserCompanyId = json_decode($getTokenResult->getData()->data)->company_id;

            // トークンデータのdl_file_idを取得
            $getTokenDlFileId = json_decode($getTokenResult->getData()->data)->dl_file_id;
            
            // 取得したトークンの有効期限のチェック
            if ($getTokenResult > CarbonImmutable::now()) {
                throw new Exception('common.messate.permission');
            }

            // ダウンロードパスオブジェクト取得
            $getDlFileResult = $this->documentRepository->getDlFilePath(mUserId: $mUserId, mUserCompanyId: $mUserCompanyId, getTokenDlFileId: $getTokenDlFileId);
            // return var_export($getDlFileResult->getData());

            if (empty($getDlFileResult)) {
                throw new Exception('common.message.not-found');
            }

            $zip = new ZipArchive();

            $zipFileName   = $getDlFileResult->getData()->dl_file_name . '.zip';

            $zipFilePath   = '/var/www/html/testImageFile/testZip';

            $result        = $zip->open($zipFilePath.$zipFileName, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);

            $accept_data   = file_get_contents($getDlFileResult->getData()->dl_file_path);
            
            $filename      = $getDlFileResult->getData()->dl_file_name;

            $zip->addFromString($filename , $accept_data);

            $zip->close();


            // ヘッダー定義      
            header('Content-Type: application/zip; name="' . $zipFileName . '"');
            header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
            header('Content-Length: '.filesize($zipFilePath.$zipFileName));
            echo file_get_contents($zipFilePath.$zipFileName);


            unlink($zipFilePath.$zipFileName);
            
            return true;
        } catch (Exception $e) {
            throw $e;
            return false;
        }
    }
}
