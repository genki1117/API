<?php
declare(strict_types=1);
namespace App\Accessers\DB\Document;

use Carbon\CarbonImmutable;
use App\Accessers\DB\FluentDatabase;

class DocumentPermissionTransaction extends FluentDatabase
{
    protected string $table = "t_doc_permission_transaction";

    /**
     * ---------------------------------------------
     * 取引書類閲覧権限情報を取得する
     * ---------------------------------------------
     * @param int $documentId
     * @param int $companyId
     * @return \stdClass|null
     */
    public function getList(int $documentId, int $companyId): ?\stdClass
    {
        return $this->builder($this->table)
            ->select([
                "t_doc_permission_transaction.user_id",
                "m_user.family_name",
                "m_user.first_name",
                "m_user.email",
                "m_user.group_array"
            ])
            ->leftjoin("m_user", function ($query) {
                return $query->on("m_user.user_id", "t_doc_permission_transaction.user_id")
                    ->where("m_user.company_id", "t_doc_permission_transaction.company_id")
                    ->where("m_user.delete_datetime", null);
            })
            ->where("t_doc_permission_transaction.delete_datetime", null)
            ->where("t_doc_permission_transaction.document_id", $documentId)
            ->where("t_doc_permission_transaction.company_id", $companyId)
            ->orderBy("t_doc_permission_transaction.user_id")
            ->first();
    }

    /**
     * ---------------------------------------------
     * 更新項目（取引書類閲覧権限）
     * ---------------------------------------------
     * @param int $userId
     * @param int $companyId
     * @param int $documentId
     * @return bool
     */
    public function getDelete(int $userId, int $companyId, int $documentId)
    {
        return $this->builder($this->table)
            ->whereNull("delete_datetime")
            ->where("company_id", "=", $companyId)
            ->where("document_id", "=", $documentId)
            ->update([
                "delete_user" => $userId,
                "delete_datetime" => CarbonImmutable::now()
            ]);
    }

    /**
     * @param int $companyId
     * @param int $documentId
     * @return \stdClass|null
     */
    public function getBeforeOrAfterData(int $companyId, int $documentId): ?\stdClass
    {
        return $this->builder()
            ->select([
                "delete_user",
                "delete_datetime"
            ])
            ->where("company_id", "=", $companyId)
            ->where("document_id", "=", $documentId)
            ->first();
    }
}
