<?php
declare(strict_types=1);
namespace App\Domain\Consts;

/**
 * 定数の設定
 */
class UserConst
{
    // ユーザータイプマスタ
    /** @var int */
    const USER_TYPE_HOST = 0;

    /** @var string */
    const USER_TYPE_HOST_NAME = 'ホストユーザー';

    /** @var int */
    const USER_TYPE_GUEST = 1;

    /** @var string */
    const USER_TYPE_GUEST_NAME = 'ゲストユーザー';

    /** @var int */
    const USER_TYPE_SYSTEMMANAGER = 99;

    /** @var string */
    const USER_TYPE_SYSTEMMANAGER_NAME = 'システム管理者ユーザー';

    const USER_TYPE_LIST = [
        self::USER_TYPE_HOST => self::USER_TYPE_HOST_NAME,
        self::USER_TYPE_GUEST => self::USER_TYPE_GUEST_NAME,
        self::USER_TYPE_SYSTEMMANAGER => self::USER_TYPE_SYSTEMMANAGER_NAME
    ];
}