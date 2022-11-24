<?php
declare(strict_types=1);
namespace App\Accessers\DB\Master;

use App\Accessers\DB\FluentDatabase;

class MUserRole extends FluentDatabase
{
    protected string $table = "m_user_role";

    /**
     * @param string $compnay_id
     * @param string $user_id
     * @return \stdClass|null
     */
    public function getUserRole(string $compnay_id, string $user_id): ?\stdClass
    {
        return $this->builder("m_user_role as mur")
            ->select([
                "mur.*",
            ])
            ->where("mur.company_id", '=', $compnay_id)
            ->where("mur.user_id", '=', $user_id)
            ->whereNull("mur.delete_datetime")
            ->first();
    }
}
