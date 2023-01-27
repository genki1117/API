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

            $zipFileName   = $fileName . CsvDlConst::DOWNLOAD_EXTENSION;

            $zipFilePath   = CsvDlConst::DOWNLOAD_TMP_FILE_PATH;

            $zipFileResult = $zipArchive->open($zipFilePath.$zipFileName, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);

            if ($zipFileResult === false) {
                throw new Exception('common.message.permission');
            }
            
            $accept_data      = file_get_contents(CsvDlConst::DOWNLOAD_TMP_FILE_PATH . $mUserCompanyId . '/' . $mUserId . '/'. $fileName);

            $DownloadFileName = CsvDlConst::DOWNLOAD_FILE_NAME . CsvDlConst::DOWNLOAD_CSV_EXTENSION;

            $zipArchive->addFromString($DownloadFileName, $accept_data);

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
