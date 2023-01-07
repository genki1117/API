<?php
declare(strict_types=1);
namespace App\Domain\Consts;

/**
 * 定数の設定
 */
class UserTypeConst
{
    /** @var int */
    const USER_TYPE_HOST_NO = 0;

    /** @var int */
    const USER_TYPE_GUEST_NO = 1;

    /** @var string */
    const USER_TYPE_HOST_NAME = 'ホスト';

    /** @var string */
    const USER_TYPE_GUEST_NAME = 'ゲスト';
}
