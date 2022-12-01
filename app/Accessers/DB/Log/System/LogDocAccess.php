<?php
declare(strict_types=1);
namespace App\Accessers\DB\Log\System;

use Carbon\CarbonImmutable;
use App\Accessers\DB\FluentDatabase;

class LogDocAccess extends FluentDatabase
{
    /** @var string */
    protected string $table = "t_log_doc_access";
    /** @var string */
    protected const STR_EMPTY = "";

    /**
     * ---------------------------------------------
     * 操作ログ情報を取得する
     * ---------------------------------------------
     * @param int $documentId
     * @param int $categoryId
     * @param int $companyId
     * @return \stdClass|null
     */
    public function getList(int $documentId, int $categoryId, int $companyId)
    {
        return $this->builder($this->table)
            ->select([
                "t_log_doc_access.log_id",
                "t_log_doc_access.create_datetime",
                "t_log_doc_access.create_user",
                "m_user.family_name",
                "m_user.first_name"
            ])
            ->leftjoin("m_user", function ($query) {
                return $query->on("m_user.company_id", "=", "t_log_doc_access.company_id")
                    ->where("m_user.delete_datetime", "=", null);
            })
            ->where("t_log_doc_access.delete_datetime", "=", null)
            ->where("t_log_doc_access.document_id", "=", $documentId)
            ->where("t_log_doc_access.category_id", "=", $categoryId)
            ->where("t_log_doc_access.company_id", "=", $companyId)
            ->orderBy("t_log_doc_access.log_id", "desc")
            ->first();
    }

    /**
     * ---------------------------------------------
     * アクセスログ情報を登録する
     * ---------------------------------------------
     * @param int $companyId
     * @param int $categoryId
     * @param int $documentId
     * @param int $userId
     * @param int $userType
     * @param string $ipAddress
     * @param string $accessContent
     * @return bool
     */
    public function insert(int $companyId, int $categoryId, int $documentId, int $userId, int $userType, string $ipAddress, string $accessContent): bool
    {
        $data = [
            "company_id" => $companyId,
            "category_id" => $categoryId,
            "document_id" => $documentId,
            "access_user" => $userId,
            "user_type" => $userType,
            "access_datetime" => CarbonImmutable::now(),
            "ip_address" => $ipAddress,
            "access_content" => $accessContent,
            "create_user" => $userId,
            "create_datetime" => CarbonImmutable::now()
        ];
        return $this->builder()->insert($data);
    }
}
