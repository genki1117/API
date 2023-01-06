<?php
declare(strict_types=1);
namespace App\Accessers\DB\Document;

use App\Accessers\DB\FluentDatabase;
use Illuminate\Support\Facades\DB;

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
     * @return array
     */
    public function getList(int $documentId, int $categoryId, int $companyId): array
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
            ->leftjoin("m_user", function ($query) {
                return $query->on("m_user.company_id", "t_document_workflow.company_id")
                    ->where("m_user.delete_datetime", null);
            })
            ->where("t_document_workflow.delete_datetime", null)
            ->where("t_document_workflow.document_id", $documentId)
            ->where("t_document_workflow.category_id", $categoryId)
            ->where("t_document_workflow.company_id", $companyId)
            ->orderBy("t_document_workflow.wf_sort", "DESC")
            ->get()
            ->all();
    }

    /**
     * 次の署名者の情報を取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $loginUserWorkFlowSort
     * @return \stdClass|null
     */
    public function getContractNextSignUserInfomation(int $documentId, int $categoryId, int $loginUserWorkFlowSort): ?\stdClass
    { 
        return $this->builder($this->table)
            ->select([
                "t_document_contract.title",
                "t_document_contract.document_id",
                "t_document_contract.category_id",
                "m_user.email",
                "m_user.full_name",
                "m_user.company_id",
                "m_user.user_id",
                "m_user.user_type_id",
                "t_document_workflow.wf_sort",
                "t_doc_storage_contract.file_prot_flg",
                "m_company_counter_party.counter_party_name",
                "m_company_counter_party.counter_party_id"
            ])
            ->join("m_user", function ($query) {
                return $query->on("m_user.user_id", "t_document_workflow.app_user_id")
                    ->where("m_user.delete_datetime", null);
            })
            ->join("t_document_contract", function ($query) {
                return $query->on("t_document_contract.document_id", "t_document_workflow.document_id")
                    ->where("m_user.delete_datetime", null);
            })
            ->join("t_doc_storage_contract", function ($query) {
                return $query->on("t_doc_storage_contract.document_id", "t_document_workflow.document_id")
                    ->where("m_user.delete_datetime", null);
            })
            ->leftjoin("m_company_counter_party", function ($query) {
                return $query->on("m_company_counter_party.company_id", "t_document_workflow.company_id")
                    ->where("m_user.delete_datetime", null);
            })
            ->where("t_document_workflow.wf_sort", ">", $loginUserWorkFlowSort)
            ->where("t_document_contract.document_id", $documentId)
            ->where("t_document_contract.category_id", $categoryId)
            ->orderBy("t_document_workflow.wf_sort", "ASC")
            ->first();
    }
}
