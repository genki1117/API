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
     * ------------------------------------
     * 次の署名者を取得（契約書類）
     * ------------------------------------
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $loginUserWorkFlowSort
     * @return \stdClass|null
     */
    public function getContractNextSignUser(int $documentId, int $categoryId, int $mUserId): ?\stdClass
    {
        return $this->builder($this->table)
            ->select([
                "m_user.user_id",
                "m_user.full_name",
                "m_user.email",
                "m_user.user_type_id",
                "t_document_workflow.wf_sort",
                "t_document_workflow.category_id",
                "m_company_counter_party.counter_party_id",
                "m_company_counter_party.counter_party_name"
            ])
            ->join("m_user", function ($query) {
                return $query->on("m_user.user_id", "t_document_workflow.app_user_id")
                             ->on("m_user.company_id", "t_document_workflow.company_id")
                             ->where("m_user.delete_datetime", null);
            })
            ->leftjoin("m_company_counter_party", function ($query) {
                return $query->on("m_company_counter_party.company_id", "t_document_workflow.company_id")
                             ->whereNull("m_company_counter_party.delete_datetime");
            })
            ->where("t_document_workflow.app_user_id", ">", $mUserId)
            ->where("t_document_workflow.document_id", "=", $documentId)
            ->where("t_document_workflow.category_id", "=", $categoryId)
            ->orderBy("t_document_workflow.wf_sort", "ASC")
            ->first();
    }

    /**
     * ------------------------------------
     * 起票者の取得（契約書類）
     * ------------------------------------
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @return \stdClass|null
     */
    public function getContractIssueUser(int $documentId, int $categoryId): ?\stdClass
    {
        return $this->builder($this->table)
            ->select([
                "m_user.full_name",
                "m_user.family_name",
                "m_user.first_name",
                "t_document_workflow.wf_sort",
                "t_document_workflow.category_id",
            ])
            ->join("m_user", function ($query) {
                return $query->on("m_user.user_id", "t_document_workflow.app_user_id")
                             ->where("m_user.delete_datetime", null);
            })
            ->where("t_document_workflow.wf_sort", "=", 0)
            ->where("t_document_workflow.document_id", "=", $documentId)
            ->where("t_document_workflow.category_id", "=", $categoryId)
            ->orderBy("t_document_workflow.wf_sort", "ASC")
            ->first();
    }


    /**
     * ------------------------------------
     * 次の署名者を取得（取引書類）
     * ------------------------------------
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $loginUserWorkFlowSort
     * @return \stdClass|null
     */
    public function getDealNextSignUser(int $documentId, int $categoryId, int $mUserId): ?\stdClass
    {
        return $this->builder($this->table)
            ->select([
                "m_user.user_id",
                "m_user.full_name",
                "m_user.email",
                "m_user.user_type_id",
                "t_document_workflow.wf_sort",
                "t_document_workflow.category_id",
                "m_company_counter_party.counter_party_id",
                "m_company_counter_party.counter_party_name"
            ])
            ->join("m_user", function ($query) {
                return $query->on("m_user.user_id", "t_document_workflow.app_user_id")
                             ->on("m_user.company_id", "t_document_workflow.company_id")
                             ->where("m_user.delete_datetime", null);
            })
            ->leftjoin("m_company_counter_party", function ($query) {
                return $query->on("m_company_counter_party.company_id", "t_document_workflow.company_id")
                             ->whereNull("m_company_counter_party.delete_datetime");
            })
            ->where("t_document_workflow.app_user_id", ">", $mUserId)
            ->where("t_document_workflow.document_id", "=", $documentId)
            ->where("t_document_workflow.category_id", "=", $categoryId)
            ->orderBy("t_document_workflow.wf_sort", "ASC")
            ->first();
    }

     /**
      * ------------------------------------
     * 起票者の取得（取引書類）
    　* ------------------------------------
     *
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @return \stdClass|null
     */
    public function getDealIssueUser(int $documentId, int $categoryId): ?\stdClass
    {
        return $this->builder($this->table)
            ->select([
                "m_user.full_name",
                "m_user.family_name",
                "m_user.first_name",
                "t_document_workflow.wf_sort",
                "t_document_workflow.category_id",
            ])
            ->join("m_user", function ($query) {
                return $query->on("m_user.user_id", "t_document_workflow.app_user_id")
                             ->where("m_user.delete_datetime", null);
            })
            ->where("t_document_workflow.wf_sort", "=", 0)
            ->where("t_document_workflow.document_id", "=", $documentId)
            ->where("t_document_workflow.category_id", "=", $categoryId)
            ->orderBy("t_document_workflow.wf_sort", "ASC")
            ->first();
    }


    /**
     * ------------------------------------
     * 署名者全員取得（社内書類）
     * ------------------------------------
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $mUserCompanyId
     * @return void
     */
    public function getInternalSignUserList(int $documentId, int $categoryId, int $mUserCompanyId): ?array
    {
        return $this->builder($this->table)
            ->select([
                "m_user.full_name",
                "m_user.family_name",
                "m_user.first_name",
                "m_user.email",
                "t_document_workflow.wf_sort",
                "t_document_workflow.category_id",
            ])
            ->join("m_user", function ($query) {
                return $query->on("m_user.user_id", "t_document_workflow.app_user_id")
                             ->where("m_user.delete_datetime", null);
            })
            ->where("t_document_workflow.wf_sort", "<>", 0)
            ->where("t_document_workflow.document_id", "=", $documentId)
            ->where("t_document_workflow.category_id", "=", $categoryId)
            ->where("t_document_workflow.company_id", "=", $mUserCompanyId)
            ->orderBy("t_document_workflow.wf_sort", "ASC")
            ->get()
            ->all();
    }

    /**
     * ------------------------------------
     * 起票者を取得（社内書類）
     * ------------------------------------
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $mUserCompanyId
     * @return \stdClass|null
     */
    public function getInternalIssueUser(int $documentId, int $categoryId, int $mUserCompanyId): ?\stdClass
    {
        return $this->builder($this->table)
            ->select([
                "m_user.full_name",
                "m_user.family_name",
                "m_user.first_name",
                "t_document_workflow.wf_sort",
                "t_document_workflow.category_id",
            ])
            ->join("m_user", function ($query) {
                return $query->on("m_user.user_id", "t_document_workflow.app_user_id")
                             ->where("m_user.delete_datetime", null);
            })
            ->where("t_document_workflow.wf_sort", "=", 0)
            ->where("t_document_workflow.document_id", "=", $documentId)
            ->where("t_document_workflow.category_id", "=", $categoryId)
            ->where("t_document_workflow.company_id", "=", $mUserCompanyId)
            ->orderBy("t_document_workflow.wf_sort", "ASC")
            ->first();
    }


    /**
     * ------------------------------------
     * 署名者を取得（登録書類）
     * ------------------------------------
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $mUserCompanyId
     * @return \stdClass|null
     */
    public function getArchiveNextSignUser(int $documentId, int $categoryId, int $mUserCompanyId): ?\stdClass
    {
        return $this->builder($this->table)
            ->select([
                "m_user.full_name",
                "m_user.family_name",
                "m_user.first_name",
                "m_user.email",
                "t_document_workflow.wf_sort",
                "t_document_workflow.category_id",
            ])
            ->join("m_user", function ($query) {
                return $query->on("m_user.user_id", "t_document_workflow.app_user_id")
                             ->where("m_user.delete_datetime", null);
            })
            ->where("t_document_workflow.wf_sort", "<>", 0)
            ->where("t_document_workflow.document_id", "=", $documentId)
            ->where("t_document_workflow.category_id", "=", $categoryId)
            ->where("t_document_workflow.company_id", "=", $mUserCompanyId)
            ->orderBy("t_document_workflow.wf_sort", "ASC")
            ->first();
    }


    /**
     * ------------------------------------
     * 起票者を取得（登録書類）
     * ------------------------------------
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $mUserCompanyId
     * @return \stdClass|null
     */
    public function getArchiveIssueUser(int $documentId, int $categoryId, int $mUserCompanyId): ?\stdClass
    {
        return $this->builder($this->table)
            ->select([
                "m_user.full_name",
                "m_user.family_name",
                "m_user.first_name",
                "t_document_workflow.wf_sort",
                "t_document_workflow.category_id",
            ])
            ->join("m_user", function ($query) {
                return $query->on("m_user.user_id", "t_document_workflow.app_user_id")
                             ->where("m_user.delete_datetime", null);
            })
            ->where("t_document_workflow.wf_sort", "=", 0)
            ->where("t_document_workflow.document_id", "=", $documentId)
            ->where("t_document_workflow.category_id", "=", $categoryId)
            ->where("t_document_workflow.company_id", "=", $mUserCompanyId)
            ->orderBy("t_document_workflow.wf_sort", "ASC")
            ->first();
    }
}
