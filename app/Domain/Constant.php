<?php
declare(strict_types=1);
namespace App\Domain;

/**
 * 定数の設定
 */
class Constant
{
    /**　@var int 契約書 */
    public const DOCUMENT_TYPE_CONTRACT = 0;
    /**　@var int 取引書類 */
    public const DOCUMENT_TYPE_DEAL     = 1;
    /**　@var int 社内書類 */
    public const DOCUMENT_TYPE_INTERNAL = 2;
    /**　@var int 登録書類 */
    public const DOCUMENT_TYPE_ARCHIVE  = 3;
}
