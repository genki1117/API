<?php
declare(strict_types=1);
namespace App\Accessers\DB\Sample;

use App\Accessers\DB\FluentDatabase;

class User extends FluentDatabase
{
    protected string $table = "user";

    /**
     * @param string $mailAddress
     * @return \stdClass|null
     */
    public function getUser(string $mailAddress): ?\stdClass
    {
        return $this->builder()
            ->select([
                "mail_address",
                "password"
            ])
            ->where("mail_address", $mailAddress)
            ->where("is_deleted", self::DELETED_FLAG_OFF)
            ->first();
    }
}
