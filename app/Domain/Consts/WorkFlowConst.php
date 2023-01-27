<?php
declare(strict_types=1);
namespace App\Domain\Consts;

/**
 * 定数の設定
 */
class WorkFlowConst
{
    // 作業ステータスマスタ
    /** @var int */
    const WORKFLOW_STATUS_UNSIGNED = 0;

    /** @var string */
    const WORKFLOW_STATUS_UNSIGNED_NAME = '未署名';

    /** @var int */
    const WORKFLOW_STATUS_SIGNED = 1;

    /** @var string */
    const WORKFLOW_STATUS_SIGNED_NAME = '署名済';

    /** @var int */
    const WORKFLOW_STATUS_FORWARDED = 2;

    /** @var string */
    const WORKFLOW_STATUS_FORWARDED_NAME = '転送済';

    /** @var int */
    const WORKFLOW_STATUS_RETURNED = 3;

    /** @var string */
    const WORKFLOW_STATUS_RETURNED_NAME = '差戻済';

    /** @var int */
    const WORKFLOW_STATUS_RESEND = 4;

    /** @var string */
    const WORKFLOW_STATUS_RESEND_NAME = '要再送';

    /** @var int */
    const WORKFLOW_STATUS_REQUESTED = 5;

    /** @var string */
    const WORKFLOW_STATUS_REQUESTED_NAME = '依頼済';

    /** @var int */
    const WORKFLOW_STATUS_COMPLETED = 6;

    /** @var string */
    const WORKFLOW_STATUS_COMPLETED_NAME = '完了';

    /** @var int */
    const WORKFLOW_STATUS_NOTALLOWED = 7;

    /** @var string */
    const WORKFLOW_STATUS_NOTALLOWED_NAME = '署名不可';


    const WORKFLOW_STATUS_LIST = [
        self::WORKFLOW_STATUS_UNSIGNED => self::WORKFLOW_STATUS_UNSIGNED_NAME,
        self::WORKFLOW_STATUS_SIGNED => self::WORKFLOW_STATUS_SIGNED_NAME,
        self::WORKFLOW_STATUS_FORWARDED => self::WORKFLOW_STATUS_FORWARDED_NAME,
        self::WORKFLOW_STATUS_RETURNED => self::WORKFLOW_STATUS_RETURNED_NAME,
        self::WORKFLOW_STATUS_RESEND => self::WORKFLOW_STATUS_RESEND_NAME,
        self::WORKFLOW_STATUS_REQUESTED => self::WORKFLOW_STATUS_REQUESTED_NAME,
        self::WORKFLOW_STATUS_COMPLETED => self::WORKFLOW_STATUS_COMPLETED_NAME,
        self::WORKFLOW_STATUS_NOTALLOWED => self::WORKFLOW_STATUS_NOTALLOWED_NAME
    ];
}
