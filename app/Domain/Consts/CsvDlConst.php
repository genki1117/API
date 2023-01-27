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
    const DOWNLOAD_TMP_FILE_PATH = '/var/www/html/storage/uploadCsvFile/';

    // zipファイルに格納されているファイル名称。
    /** @var string */
    const DOWNLOAD_FILE_NAME = 'testCsvFilename'; // zipファイルに格納するcsvファイルの名称（上書き）

    // zipファイルに格納されているファイルの拡張子。
    /** @var string */
    const DOWNLOAD_CSV_EXTENSION = '.csv';

    
}
