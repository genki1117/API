<?php
declare(strict_types=1);
namespace App\Domain\Consts;

/**
 * 定数の設定
 */
class QueueConst
{
    /** @var string */
    const QUEUE_NAME_SENDMAIL = "sendmail";

    /** @var string */
    const QUEUE_NAME_BULKVALIDATION = "bulkvalidation";

    /** @var string */
    const QUEUE_NAME_SIGN = "sign";

    /** @var string */
    const QUEUE_NAME_TIMESTAMP = "timestamp";

    /** @var string */
    const QUEUE_NAME_DOCUMENTSAVE = "documentsave";

    /** @var string */
    const QUEUE_NAME_DOCUMENTDELETE = "documentdelete";

    /** @var string */
    const QUEUE_NAME_DLCSV = "dlcsv";

    /** @var string */
    const QUEUE_NAME_DLPDF = "dlpdf";
}
