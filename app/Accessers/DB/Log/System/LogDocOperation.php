<?php
declare(strict_types=1);
namespace App\Accessers\DB\Log\System;

use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
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
     * @return array
     */
    public function getList(int $documentId, int $categoryId, int $companyId): array
    {
        return $this->builder($this->table)
            ->select([
                "t_log_doc_operation.log_id",
                "t_log_doc_operation.create_datetime",
                "t_log_doc_operation.create_user",
                "t_log_doc_operation.before_content",
                "t_log_doc_operation.after_content",
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
            ->get()
            ->all();
    }

    /**
     * ---------------------------------------------
     * 操作ログ情報を登録する
     * ---------------------------------------------
     * @param int $companyId
     * @param int $categoryId
     * @param int $documentId
     * @param int $userId
     * @param array|null $beforeContent
     * @param array|null $afterContet
     * @param string|null @ipAddress
     * @return bool
     */
    public function insert(int $companyId, int $categoryId, int $documentId, int $userId, $beforeContent, $afterContet, ?string $ipAddress): bool
    {
        $data = [
            "company_id" => $companyId,
            "category_id" => $categoryId,
            "document_id" => $documentId,
            "operation_user_id" => $userId,
            "before_content" => json_encode($beforeContent, JSON_UNESCAPED_UNICODE),
            "after_contet" => json_encode($afterContet, JSON_UNESCAPED_UNICODE),
            "ip_address" => $ipAddress,
            "create_user" => $userId,
            // "create_datetime" => CarbonImmutable::now()
        ];
        return $this->builder()->insert($data);
    }
}
