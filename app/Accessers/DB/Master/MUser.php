<?php
declare(strict_types=1);
namespace App\Accessers\DB\Master;

use App\Accessers\DB\FluentDatabase;

class MUser extends FluentDatabase
{
    protected string $table = "m_user";

    /**
     * @param string $compnay_id
     * @param string $user_id
     * @return \stdClass|null
     */
    public function getUser(string $compnay_id, string $user_id): ?\stdClass
    {
        return $this->builder("m_user as mu")
            ->select([
                "mu.*",
            ])
            ->where("mu.company_id", '=', $compnay_id)
            ->where("mu.user_id", '=', $user_id)
            ->whereNull("mu.delete_datetime")
            ->first();
    }

    /**
     * @param string $compnay_id
     * @param string $email
     * @return \stdClass|null
     */
    public function getUserFromEmail(string $compnay_id, string $email): ?\stdClass
    {
        return $this->builder("m_user as mu")
            ->select([
                "mu.*",
            ])
            ->where("mu.company_id", '=', $compnay_id)
            ->where("mu.email", '=', $email)
            ->whereNull("mu.delete_datetime")
            ->first();
    }
}
