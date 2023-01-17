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
    /** @var */
    private const DownloadExtension     = '.zip';

    /** @var */
    // 一時保存ファイル
    private const DownloadTmpFilePath   = '/var/www/html/storage/zipTmp';

    /** @var DownloadFileServiceInterface */
    private DownloadFileServiceInterface $documentRepository;

    /**
     * @param DownloadFileServiceInterface $documentRepository
     */
    public function __construct(DownloadFileServiceInterface $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

    /**
     * ダウンロード処理
     *
     * @param string $token
     * @param string $nowDate
     * @return boolean
     */
    public function getFile(string $token, string $nowDate): ?bool
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

            // zipファイル処理
            $zip = new ZipArchive();

            // ダウンロードするファイルの名前
            $zipFileName   = $getDlFileResult->getData()->dl_file_name . Self::DownloadExtension;

            // ダウンロードする為の一時保存ディレクトリ
            $zipFilePath   = Self::DownloadTmpFilePath;

            // zipファイルオープン
            $result        = $zip->open($zipFilePath.$zipFileName, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);

            // falseの場合エラー
            if ($result === false) {
                throw new Exception('common.messate.permission');
            }

            // データベースから取得したファイルパスで画像を取得
            $accept_data   = file_get_contents($getDlFileResult->getData()->dl_file_path);
            
            // ダウンロードするファイル名を取得
            $filename      = $getDlFileResult->getData()->dl_file_name;

            // zipファイルに格納
            $zip->addFromString($filename , $accept_data);

            // zipファイルを閉じる
            $zip->close();

            // ヘッダー定義      
            header('Content-Type: application/zip; name="' . $zipFileName . '"');
            header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
            header('Content-Length: '.filesize($zipFilePath.$zipFileName));

            // zipファイルをダウンロード
            $resutl = readfile($zipFilePath.$zipFileName);
            
            // 一時フォルダのファイルを削除
            unlink($zipFilePath.$zipFileName);
            
            return true;
        } catch (Exception $e) {
            throw $e;
            return false;
        }
    }
}
