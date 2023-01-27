<?php
declare(strict_types=1);
namespace App\Domain\Consts;

/**
 * 定数の設定
 */
class CsvDlConst
{
    // ダウンロードするファイルの拡張子
    /** @var string */
    const DOWNLOAD_EXTENSION = '.zip';
    
    //　ダウンロードパス
    /** @var string */
    const DOWNLOAD_FILE_PATH = '/var/www/html/storage/uploadCsvFile/'; // TODO: テスト様パス


    
}
