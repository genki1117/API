<?php
declare(strict_types=1);
namespace App\Accessers\DB\Log\System;

use Carbon\CarbonImmutable;
use App\Accessers\DB\FluentDatabase;

class LogDocOperation extends FluentDatabase
{
    /** @var string */
    protected string $table = "t_log_doc_operation";
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
                "t_log_doc_operation.log_id",
                "t_log_doc_operation.create_datetime",
                "t_log_doc_operation.create_user",
                "t_log_doc_operation.before_content",
                "t_log_doc_operation.after_contet",
                "m_user.family_name",
                "m_user.first_name"
            ])
            ->leftjoin("m_user", function ($query) {
                return $query->on("m_user.company_id", "=", "t_log_doc_operation.company_id")
                    ->where("m_user.delete_datetime", "=", null);
            })
            ->where("t_log_doc_operation.delete_datetime", "=", null)
            ->where("t_log_doc_operation.document_id", "=", $documentId)
            ->where("t_log_doc_operation.category_id", "=", $categoryId)
            ->where("t_log_doc_operation.company_id", "=", $companyId)
            ->orderBy("t_log_doc_operation.log_id", "desc")
            ->first();
    }

    /**
     * ---------------------------------------------
     * 操作ログ情報を登録する
     * ---------------------------------------------
     * @param int $companyId
     * @param int $documentId
     * @param int $userId
     * @param array|null $beforeContent
     * @param array|null $afterContet
     * @param string|null @ipAddress
     * @return bool
     */
    public function insert(int $companyId, int $categoryId, int $documentId, int $userId, array $beforeContent = null, array $afterContet = null, string $ipAddress = Self::STR_EMPTY): bool
    {
        $data = [
            "company_id" => $companyId,
            "category_id" => $categoryId,
            "document_id" => $documentId,
            "operation_user_id" => $userId,
            "before_content" => $beforeContent,
            "after_contet" => $afterContet,
            "ip_address" => $ipAddress,
            "create_user" => $userId,
            "create_datetime" => CarbonImmutable::now(),
            "delete_user" => null,
            "delete_datetime" => null,
        ];
        return $this->builder()->insert($data);
    }
}
