<?php
declare(strict_types=1);
namespace App\Domain\Services\Document;

use App\Domain\Consts\UserConst as UserConstain;
use ZipArchive;
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

    private const DOWNLOAD_EXTENSION = '.zip';

    private const DOWNLOAD_TMP_FILE_PATH = ''; // TODO:本番環境のパスを設定する。

    private const DOWNLOAD_TMP_FILE_PATH_TEST = '/var/www/html/testCsv/';

    private const DOWNLOAD_FILE_NAME = 'testCsvFilename'; // zipファイルに格納するcsvファイルの名称（上書き）

    private const DOWNLOAD_CSV_EXTENSION = '.csv';


    public function __construct()
    {
        $this->csvStoragePath = '/var/www/html/testCsv/';
        // $this->csvStoragePath = 'Storage/UploadCsvFile/';
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
            
            // ユーザタイプがゲストの場合、エラー
            if ($mUserTypeId === UserConstain::USER_TYPE_GUEST) {
                throw new Exception('common.message.expired');
            }

            // ユーザID、ユーザタイプID、書類カテゴリIDが取得出来ない場合は、エラー
            if ($mUserId === null || $mUserCompanyId === null || $mUserTypeId === null || $categoryId === null || $fileName === null) {
                throw new Exception('common.message.not-found');
            }

            
            $zipArchive    = new ZipArchive();

            $zipFileName   = $fileName . Self::DOWNLOAD_EXTENSION;

            $zipFilePath   = Self::DOWNLOAD_TMP_FILE_PATH_TEST;

            $zipFileResult = $zipArchive->open($zipFilePath.$zipFileName, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);

            if ($zipFileResult === false) {
                throw new Exception('common.message.permission');
            }

            $accept_data   = file_get_contents(Self::DOWNLOAD_TMP_FILE_PATH_TEST . $mUserCompanyId . '/' . $mUserId . '/'. $fileName);

            $filename      = Self::DOWNLOAD_FILE_NAME . Self::DOWNLOAD_CSV_EXTENSION;

            $zipArchive->addFromString($filename , $accept_data);

            $zipArchive->close();

            header('Content-Type: application/zip; name="' . $zipFileName . '"');
            header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
            header('Content-Length: '.filesize($zipFilePath.$zipFileName));
            ob_end_clean();
            
            // zipファイルをダウンロード
            
            readfile($zipFilePath.$zipFileName);

            // 一時フォルダのファイルを削除
            unlink($zipFilePath.$zipFileName);
            
            
        } catch (Exception $e) {
            throw new Exception('common.message.not-found');
            return false;
        }
    }
}
