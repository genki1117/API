<?php
declare(strict_types=1);
namespace App\Domain\Services\Document;

use App\Domain\Consts\CsvDlConst;
use App\Domain\Consts\UserConst as UserConstain;
use ZipArchive;
use Exception;

class DocumentDownloadCsvService
{

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
    public function downloadCsv(?int $mUserId, ?int $mUserCompanyId, ?int $mUserTypeId, ?int $categoryId, ?string $fileName): ?bool
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

            // ダウンロードされるzipファイルの名称
            $zipFileName   = $fileName . CsvDlConst::DOWNLOAD_EXTENSION; //"test.csv.zip"

            $zipFileResult = $zipArchive->open(CsvDlConst::DOWNLOAD_FILE_PATH.$zipFileName, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE); // "/var/www/html/storage/uploadCsvFile/"

            if ($zipFileResult === false) {
                throw new Exception('common.message.permission');
            }

            $zipArchive->addFile(CsvDlConst::DOWNLOAD_FILE_PATH . $mUserCompanyId . '/' . $mUserId . '/'. $fileName, $fileName);

            $zipArchive->close();
            
            
            header('Content-Type: application/zip; name="' . $zipFileName . '"');
            header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
            header('Content-Length: '.filesize(CsvDlConst::DOWNLOAD_FILE_PATH.$zipFileName));

            ob_end_clean();
            // zipファイルをダウンロード
            
            readfile(CsvDlConst::DOWNLOAD_FILE_PATH.$zipFileName);
            
            // 一時フォルダのファイルを削除
            unlink(CsvDlConst::DOWNLOAD_FILE_PATH.$zipFileName);
            
            return true;
            
        } catch (Exception $e) {
            throw $e;
            return false;
        }
    }
}
