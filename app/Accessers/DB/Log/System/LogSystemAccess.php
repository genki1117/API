<?php
declare(strict_types=1);
namespace App\Accessers\DB\Log\System;

use Carbon\CarbonImmutable;
use App\Accessers\DB\FluentDatabase;

class LogSystemAccess extends FluentDatabase
{
    /** @var string */
    protected string $table = "t_log_system_access";
    /** @var string */
    protected const STR_EMPTY = "";

    /**
     * ---------------------------------------------
     * システムアクセスログ情報を登録する
     * ---------------------------------------------
     * @param int $companyId
     * @param int $userId
     * @param string $fullName
     * @param string $ipAddress
     * @param string $accessFuncName
     * @param array|null $action
     * @return bool
     */
    public function insert(int $companyId, int $userId, string $fullName = Self::STR_EMPTY, string $ipAddress = Self::STR_EMPTY, string $accessFuncName = Self::STR_EMPTY, array $action = null): bool
    {
        $data = [
            "company_id" => $companyId,
            "user_id" => $userId,
            "user_name" => $fullName,
            "ip_address" => $ipAddress,
            "access_datetime" => CarbonImmutable::now(),
            "access_type" => 1,
            "access_func_name" => $accessFuncName,
            "action" => json_encode($action),
            "create_user" => $userId,
            "create_datetime" => CarbonImmutable::now(),
            "delete_user" => null,
            "delete_datetime" => null
        ];
        return $this->builder()->insert($data);
    }
}
