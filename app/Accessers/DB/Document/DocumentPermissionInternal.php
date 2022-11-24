<?php
declare(strict_types=1);
namespace App\Accessers\DB\Document;

use App\Accessers\DB\FluentDatabase;

class DocumentPermissionInternal extends FluentDatabase
{
    protected string $table = "t_doc_permission_internal";

    /**
     * ---------------------------------------------
     * 社内書類閲覧権限情報を取得する
     * ---------------------------------------------
     * @param int $documentId
     * @param int $companyId
     * @return \stdClass|null
     */
    public function getList(int $documentId, int $companyId): ?\stdClass
    {
        return $this->builder($this->table)
            ->select([
                "t_doc_permission_internal.user_id",
                "m_user.family_name",
                "m_user.first_name",
                "m_user.email",
                "m_user.group_array"
            ])
            ->leftjoin("m_user", function($query) {
                return $query->on("m_user.user_id","t_doc_permission_internal.user_id")
                    ->where("m_user.company_id","t_doc_permission_internal.company_id")
                    ->where("m_user.delete_datetime", null);
            })
            ->where("t_doc_permission_internal.delete_datetime", null)
            ->where("t_doc_permission_internal.document_id",$documentId)
            ->where("t_doc_permission_internal.company_id", $companyId)
            ->orderBy("t_doc_permission_internal.user_id")
            ->first();
    }
}
