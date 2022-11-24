<?php
declare(strict_types=1);
namespace App\Accessers\DB\Document;

use App\Accessers\DB\FluentDatabase;

class DocumentWorkFlow extends FluentDatabase
{
    protected string $table = "t_document_workflow";

    /**
     * ---------------------------------------------
     * 書類ワークフロー情報を取得する
     * ---------------------------------------------
     * @param int $documentId
     * @param int $categoryId
     * @param int $companyId
     * @return \stdClass|null
     */
    public function getList(int $documentId, int $categoryId, int $companyId): ?\stdClass
    {
        return $this->builder($this->table)
            ->select([
                "t_document_workflow.app_user_id",
                "t_document_workflow.wf_sort",
                "m_user.family_name",
                "m_user.first_name",
                "m_user.email",
                "m_user.group_array"
            ])
            ->leftjoin("m_user", function($query) {
                return $query->on("m_user.company_id","t_document_workflow.company_id")
                    ->where("m_user.delete_datetime", null);
            })
            ->where("t_document_workflow.delete_datetime", null)
            ->where("t_document_workflow.document_id",$documentId)
            ->where("t_document_workflow.category_id",$categoryId)
            ->where("t_document_workflow.company_id",$companyId)
            ->orderBy("t_document_workflow.wf_sort","DESC")
            ->first();
    }
}
