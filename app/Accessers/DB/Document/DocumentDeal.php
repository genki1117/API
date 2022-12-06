<?php
declare(strict_types=1);
namespace App\Accessers\DB\Document;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use App\Accessers\DB\FluentDatabase;
use Illuminate\Contracts\Support\Arrayable;

class DocumentDeal extends FluentDatabase
{
    protected string $table = "t_document_deal";

    /**
     * ---------------------------------------------
     * 取引書類情報を取得する
     * ---------------------------------------------
     * @param int $documentId
     * @param int $companyId
     * @param int $userId
     * @return \stdClass|null
     */
    public function getList(int $documentId, int $companyId, int $userId): ?\stdClass
    {
        return $this->builder($this->table)
            ->select([
                "t_document_deal.document_id",
                "t_document_deal.company_id",
                "t_document_deal.category_id",
                "t_document_deal.doc_type_id",
                "t_document_deal.status_id",
                "t_document_deal.issue_date",
                "t_document_deal.expiry_date",
                "t_document_deal.payment_date",
                "t_document_deal.transaction_date",
                "t_document_deal.download_date",
                "t_document_deal.doc_no",
                "t_document_deal.ref_doc_no",
                "t_document_deal.product_name",
                "t_document_deal.title",
                "t_document_deal.amount",
                "t_document_deal.currency_id",
                "t_document_deal.counter_party_id",
                "t_document_deal.remarks",
                "t_document_deal.doc_info",
                "t_document_deal.sign_level",
                DB::raw("UNIX_TIMESTAMP(t_document_deal.update_datetime) as update_datetime"),
                "t_doc_storage_transaction.file_path",
                "t_doc_storage_transaction.total_pages",
                "m_company_counter_party.counter_party_name"
            ])
            ->join("t_doc_storage_transaction", function ($query) {
                return $query->on("t_doc_storage_transaction.document_id", "t_document_deal.document_id")
                    ->where("t_doc_storage_transaction.company_id", "t_document_deal.company_id")
                    ->where("t_doc_storage_transaction.delete_datetime", null);
            })
            ->leftjoin("m_company_counter_party", function ($query) {
                return $query->on("t_document_deal.company_id", "m_company_counter_party.company_id")
                    ->where("t_document_deal.counter_party_id", "m_company_counter_party.counter_party_id")
                    ->where("m_company_counter_party.effe_start_date", "<=", "CURRENT_DATE")
                    ->where("m_company_counter_party.effe_end_date", ">=", "CURRENT_DATE")
                    ->where("m_company_counter_party.delete_datetime", null);
            })
            ->where("t_document_deal.delete_datetime", null)
            ->where("t_document_deal.document_id", $documentId)
            ->where("t_document_deal.company_id", $companyId)
            ->whereExists(function ($query) use ($userId) {
                return $query->from("t_document_workflow as tdw")
                    ->select(DB::raw(1))
                    ->where("tdw.company_id", "=", "t_document_deal.company_id")
                    ->where("tdw.document_id", "=", "t_document_deal.document_id")
                    ->where("tdw.category_id", "=", "t_document_deal.category_id")
                    ->where("tdw.delete_datetime", null)
                    ->where("tdw.app_user_id", $userId)
                    ->where(function ($join) {
                        return $join->where("tdw.wf_sort", 0)
                            ->orWhere("tdw.app_status", 6);
                    })
                    ->union(
                        DB::table("t_doc_permission_transaction as tdpt")
                        ->select(DB::raw(1))
                        ->where("tdpt.company_id", "=", "t_document_deal.company_id")
                        ->where("tdpt.document_id", "=", "t_document_deal.document_id")
                        ->where("tdpt.delete_datetime", null)
                        ->where("tdpt.user_id", $userId)
                    )
                    ->union(
                        DB::table("m_user as mu")
                        ->select(DB::raw(1))
                        ->join("m_user_role as mur", function ($join) {
                            return $join->on("mur.company_id", "mu.company_id")
                                ->where("mur.user_id", "mu.user_id")
                                ->where("mur.delete_datetime", null);
                        })
                        ->where("mu.company_id", "=", "t_document_deal.company_id")
                        ->where("mu.delete_datetime", null)
                        ->where("mu.user_id", $userId)
                    );
            })
            ->first();
    }

    /** 
     * @param array $mUser
     * @param array $condition
     * @param array $sort
     * @param array $page
     */
    public function getDocList(array $mUser, array $condition, array $sort, array $page)
    {
        return $this->builder()
            ->select([
                "t_document_deal.document_id",
                "t_document_deal.category_id",
                "t_document_deal.transaction_date as transaction_date",
                "t_document_deal.doc_type_id",
                "t_document_deal.title",
                "t_document_deal.amount",
                "t_document_deal.currency_id",
                "t_document_deal.status_id",
                DB::raw("UNIX_TIMESTAMP(t_document_deal.update_datetime)"),
                "t_document_workflow.app_status",
                "m_user.full_name as create_user",
                DB::raw("CONCAT(m_company_counter_party.company_id ,' ',m_company_counter_party.counter_party_name)as counter_party_name")
            ])
            ->join("m_user", function($query) {
                return $query->on("m_user.user_id", "=", "t_document_deal.create_user")
                ->where("m_user.company_id", "=", "t_document_deal.company_id")
                ->whereNull("m_user.delete_datetime");
            })
            ->leftJoin("t_document_workflow", function($query) use($mUser) {
                return $query->on("t_document_deal.document_id", "=", "t_document_workflow.document_id")
                ->where("t_document_deal.category_id", "=", "t_document_workflow.category_id")
                ->where("t_document_workflow.company_id", "=", $mUser["company_id"])
                ->where("t_document_workflow.app_user_id", "=", $mUser["user_id"])
                ->whereNull("t_document_workflow.delete_datetime");
            })
            ->join("m_company_counter_party", function($query) {
                return $query->on("m_company_counter_party.company_id", "=", "t_document_deal.company_id")
                ->where("m_company_counter_party.counter_party_id", "=", "t_document_deal.cou_party_id")
                ->where("m_company_counter_party.effe_start_date", "<=", "CURRENT_DATE")
                ->where("m_company_counter_party.effe_end_date", ">=", "CURRENT_DATE")
                ->whereNull("m_company_counter_party.delete_datetime");
            })
            ->whereNull("t_document_deal.delete_datetime")
            ->where("t_document_deal.company_id", "=", $mUser["company_id"])
            ->where("t_document_deal.title", "like", '%'.$condition["search_input"].'%')
            ->whereIn("t_document_deal.status_id", [$condition["status_id"]])
            ->where("t_document_deal.category_id", "=", $condition["category_id"])
            ->where("t_document_deal.doc_type_id", "=", $condition["register_type_id"])
            ->where("t_document_deal.title", "like", '%'.$condition["contract_name"].'%')
            ->where("t_document_deal.doc_no", "like", '%'.$condition["doc_no"].'%')
            ->where("t_document_deal.ref_doc_no", "like", '%'.$condition["ref_doc_no"].'%')
            ->where("t_document_deal.amount", "<=", $condition["amount"]["from"])
            ->where("t_document_deal.amount", ">=", $condition["amount"]["to"])
            ->whereIn("t_document_deal.currency_id", [$condition["currency_id"]])
            ->where("t_document_deal.document_id", "like", '%'.$condition["document_id"].'%')
            ->where("t_document_deal.product_name", "like", '%'.$condition["productname"].'%')
            ->where("t_document_deal.remarks", "like", '%'.$condition["remarks"].'%')
            ->whereRaw("JSON_CONTAINS(doc_info, ".$condition['doc_info']['title'].", '$.title')")
            ->whereRaw("JSON_CONTAINS(doc_info, ".$condition['doc_info']['content'].", '$.content')")
            ->where("t_document_deal.create_datetime", ">=", $condition["create_datetime"]["from"])
            ->where("t_document_deal.create_datetime", "<=", $condition["create_datetime"]["to"])
            ->where("t_document_deal.issue_date", ">=", $condition["issue_date"]["from"])
            ->where("t_document_deal.issue_date", "<=", $condition["issue_date"]["to"])
            ->where("t_document_deal.expiry_date", ">=", $condition["expiry_date"]["from"])
            ->where("t_document_deal.expiry_date", "<=", $condition["expiry_date"]["to"])
            ->where("t_document_deal.payment_date", ">=", $condition["payment_date"]["from"])
            ->where("t_document_deal.payment_date", "<=", $condition["payment_date"]["to"])
            ->where("t_document_deal.transaction_date", ">=", $condition["transaction_date"]["from"])
            ->where("t_document_deal.transaction_date", "<=", $condition["transaction_date"]["to"])
            ->where("t_document_deal.download_date", ">=", $condition["download_date"]["from"])
            ->where("t_document_deal.download_date", "<=", $condition["download_date"]["to"])
            ->whereExists(function($query) use($mUser) {
                return $query->from("t_document_workflow as tdw")
                    ->select(DB::raw(1))
                    ->where("tdw.company_id", "=", "t_document_deal.company_id")
                    ->where("tdw.document_id", "=", "t_document_deal.document_id")
                    ->where("tdw.category_id", "=", "t_document_deal.category_id")
                    ->whereNull("tdw.delete_datetime")
                    ->where("tdw.app_user_id", "=", $mUser["user_id"])
                    ->where(function($jQuery) {
                        return $jQuery->where("tdw.wf_sort", "=", 0)
                            ->orWhere("tdw.app_status", "=", 6);
                    })
                    ->union(
                        DB::table("t_doc_permission_contract as tdpc")
                            ->select(DB::raw(1))
                            ->where("tdpc.company_id", "=", "t_document_deal.company_id")
                            ->where("tdpc.document_id", "=", "t_document_deal.document_id")
                            ->whereNull("tdpc.delete_datetime")
                            ->where("tdpc.user_id", "=", $mUser["user_id"])
                    )
                    ->union(
                            DB::table("m_user as mu")
                            ->select(DB::raw(1))
                            ->join("m_user_role as mur", function($join) {
                                return $join->on("mur.company_id", "=", "mu.company_id")
                                    ->where("mur.user_id", "=", "mu.user_id")
                                    ->where("mur.admin_role", "=", 0)
                                    ->whereNull("mur.delete_datetime");
                            })
                            ->where("mu.company_id", "=", "t_document_deal.company_id")
                            ->where("mu.delete_datetime",null)
                            ->where("mu.user_id", "=", $mUser["user_id"])
                    );
            })
            ->whereExists(function($query) use($condition) {
                return $query->from("t_document_workflow as tdw")
                    ->select(DB::raw(1))
                    ->join("m_user as mu", function($join) {
                        return $join->on("mu.company_id", "=", "tdw.company_id")
                            ->where("mu.user_id", "=", "tdw.app_user_id")
                            ->where("mu.user_type_id", "=", 0);
                    })
                    ->where("tdw.company_id", "=", "t_document_deal.company_id")
                    ->where("tdw.document_id", "=", "t_document_deal.document_id")
                    ->where("tdw.category_id", "=", "t_document_deal.category_id")
                    ->whereNull("tdw.delete_datetime")
                    ->where("tdw.app_user_id", "=", $condition["app_user_id"]);
            })
            ->whereExists(function($query) use($condition) {
                return $query->from("t_doc_permission_transaction as tdpt")
                    ->select(DB::raw(1))
                    ->where("tdpt.company_id", "=", "t_document_deal.company_id")
                    ->where("tdpt.document_id", "=", "t_document_deal.document_id")
                    ->whereNull("tdpt.delete_datetime")
                    ->where("tdpt.user_id", "=", $condition["view_permission_user_id"]);
            })
            ->where(function($query) use($condition) {
                return $query->where("m_company_counter_party.counter_party_name", "like",  '%'.$condition["counter_party_name"].'%')
                    ->orWhere("m_company_counter_party.counter_party_name_kana", "like",  '%'.$condition["counter_party_name"].'%');
            })
            ->when(empty($sort), function($query) {
                return $query->orderBy("t_document_deal.document_id", "ASC")
                    ->orderBy("t_document_deal.category_id", "DESC");
            })
            ->when(!empty($sort), function($query) use($sort) {
                return $query->orderBy("t_document_deal.".$sort["column_name"], $sort["sort_type"]);
            })
            ->limit($page["disp_count"])
            ->offset($page["disp_page"])
            ->get();
    }

    /** 
     * @param array $mUser
     * @param array $condition
     * @param array $sort
     * @return int|null
     */
    public function getDocListCount(array $mUser, array $condition, array $sort): ?int
    {
        return $this->builder()
            ->select([
                DB::raw("COUNT(*) AS count")
            ])
            ->join("m_user", function($query) {
                return $query->on("m_user.user_id", "=", "t_document_deal.create_user")
                ->where("m_user.company_id", "=", "t_document_deal.company_id")
                ->whereNull("m_user.delete_datetime");
            })
            ->leftJoin("t_document_workflow", function($query) use($mUser) {
                return $query->on("t_document_deal.document_id", "=", "t_document_workflow.document_id")
                ->where("t_document_deal.category_id", "=", "t_document_workflow.category_id")
                ->where("t_document_workflow.company_id", "=", $mUser["company_id"])
                ->where("t_document_workflow.app_user_id", "=", $mUser["user_id"])
                ->whereNull("t_document_workflow.delete_datetime");
            })
            ->join("m_company_counter_party", function($query) {
                return $query->on("m_company_counter_party.company_id", "=", "t_document_deal.company_id")
                ->where("m_company_counter_party.counter_party_id", "=", "t_document_deal.cou_party_id")
                ->where("m_company_counter_party.effe_start_date", "<=", "CURRENT_DATE")
                ->where("m_company_counter_party.effe_end_date", ">=", "CURRENT_DATE")
                ->whereNull("m_company_counter_party.delete_datetime");
            })
            ->whereNull("t_document_deal.delete_datetime")
            ->where("t_document_deal.company_id", "=", $mUser["company_id"])
            ->where("t_document_deal.title", "like", '%'.$condition["search_input"].'%')
            ->whereIn("t_document_deal.status_id", [$condition["status_id"]])
            ->where("t_document_deal.category_id", "=", $condition["category_id"])
            ->where("t_document_deal.doc_type_id", "=", $condition["register_type_id"])
            ->where("t_document_deal.title", "like", '%'.$condition["contract_name"].'%')
            ->where("t_document_deal.doc_no", "like", '%'.$condition["doc_no"].'%')
            ->where("t_document_deal.ref_doc_no", "like", '%'.$condition["ref_doc_no"].'%')
            ->where("t_document_deal.amount", "<=", $condition["amount"]["from"])
            ->where("t_document_deal.amount", ">=", $condition["amount"]["to"])
            ->whereIn("t_document_deal.currency_id", [$condition["currency_id"]])
            ->where("t_document_deal.document_id", "like", '%'.$condition["document_id"].'%')
            ->where("t_document_deal.product_name", "like", '%'.$condition["productname"].'%')
            ->where("t_document_deal.remarks", "like", '%'.$condition["remarks"].'%')
            ->whereRaw("JSON_CONTAINS(doc_info, ".$condition['doc_info']['title'].", '$.title')")
            ->whereRaw("JSON_CONTAINS(doc_info, ".$condition['doc_info']['content'].", '$.content')")
            ->where("t_document_deal.create_datetime", ">=", $condition["create_datetime"]["from"])
            ->where("t_document_deal.create_datetime", "<=", $condition["create_datetime"]["to"])
            ->where("t_document_deal.issue_date", ">=", $condition["issue_date"]["from"])
            ->where("t_document_deal.issue_date", "<=", $condition["issue_date"]["to"])
            ->where("t_document_deal.expiry_date", ">=", $condition["expiry_date"]["from"])
            ->where("t_document_deal.expiry_date", "<=", $condition["expiry_date"]["to"])
            ->where("t_document_deal.payment_date", ">=", $condition["payment_date"]["from"])
            ->where("t_document_deal.payment_date", "<=", $condition["payment_date"]["to"])
            ->where("t_document_deal.transaction_date", ">=", $condition["transaction_date"]["from"])
            ->where("t_document_deal.transaction_date", "<=", $condition["transaction_date"]["to"])
            ->where("t_document_deal.download_date", ">=", $condition["download_date"]["from"])
            ->where("t_document_deal.download_date", "<=", $condition["download_date"]["to"])
            ->whereExists(function($query) use($mUser) {
                return $query->from("t_document_workflow as tdw")
                    ->select(DB::raw(1))
                    ->where("tdw.company_id", "=", "t_document_deal.company_id")
                    ->where("tdw.document_id", "=", "t_document_deal.document_id")
                    ->where("tdw.category_id", "=", "t_document_deal.category_id")
                    ->whereNull("tdw.delete_datetime")
                    ->where("tdw.app_user_id", "=", $mUser["user_id"])
                    ->where(function($jQuery) {
                        return $jQuery->where("tdw.wf_sort", "=", 0)
                            ->orWhere("tdw.app_status", "=", 6);
                    })
                    ->union(
                        DB::table("t_doc_permission_contract as tdpc")
                            ->select(DB::raw(1))
                            ->where("tdpc.company_id", "=", "t_document_deal.company_id")
                            ->where("tdpc.document_id", "=", "t_document_deal.document_id")
                            ->whereNull("tdpc.delete_datetime")
                            ->where("tdpc.user_id", "=", $mUser["user_id"])
                    )
                    ->union(
                            DB::table("m_user as mu")
                            ->select(DB::raw(1))
                            ->join("m_user_role as mur", function($join) {
                                return $join->on("mur.company_id", "=", "mu.company_id")
                                    ->where("mur.user_id", "=", "mu.user_id")
                                    ->where("mur.admin_role", "=", 0)
                                    ->whereNull("mur.delete_datetime");
                            })
                            ->where("mu.company_id", "=", "t_document_deal.company_id")
                            ->where("mu.delete_datetime",null)
                            ->where("mu.user_id", "=", $mUser["user_id"])
                    );
            })
            ->whereExists(function($query) use($condition) {
                return $query->from("t_document_workflow as tdw")
                    ->select(DB::raw(1))
                    ->join("m_user as mu", function($join) {
                        return $join->on("mu.company_id", "=", "tdw.company_id")
                            ->where("mu.user_id", "=", "tdw.app_user_id")
                            ->where("mu.user_type_id", "=", 0);
                    })
                    ->where("tdw.company_id", "=", "t_document_deal.company_id")
                    ->where("tdw.document_id", "=", "t_document_deal.document_id")
                    ->where("tdw.category_id", "=", "t_document_deal.category_id")
                    ->whereNull("tdw.delete_datetime")
                    ->where("tdw.app_user_id", "=", $condition["app_user_id"]);
            })
            ->whereExists(function($query) use($condition) {
                return $query->from("t_doc_permission_transaction as tdpt")
                    ->select(DB::raw(1))
                    ->where("tdpt.company_id", "=", "t_document_deal.company_id")
                    ->where("tdpt.document_id", "=", "t_document_deal.document_id")
                    ->whereNull("tdpt.delete_datetime")
                    ->where("tdpt.user_id", "=", $condition["view_permission_user_id"]);
            })
            ->where(function($query) use($condition) {
                return $query->where("m_company_counter_party.counter_party_name", "like",  '%'.$condition["counter_party_name"].'%')
                    ->orWhere("m_company_counter_party.counter_party_name_kana", "like",  '%'.$condition["counter_party_name"].'%');
            })
            ->when(empty($sort), function($query) {
                return $query->orderBy("t_document_deal.document_id", "ASC")
                    ->orderBy("t_document_deal.category_id", "DESC");
            })
            ->when(!empty($sort), function($query) use($sort) {
                return $query->orderBy("t_document_deal.".$sort["column_name"], $sort["sort_type"]);
            })
            ->limit(1)
            ->count();
    }
    
    /**
     * ---------------------------------------------
     * 更新項目（取引書類）
     * ---------------------------------------------
     * @param int $userId
     * @param int $companyId
     * @param int $documentId
     * @param int $updateDatetime
     * @return bool
     */
    public function getDelete(int $userId, int $companyId, int $documentId, int $updateDatetime)
    {
        return $this->builder($this->table)
            ->whereNull("delete_datetime")
            ->where("company_id", "=", $companyId)
            ->where("document_id", "=", $documentId)
            ->where("update_datetime", "=", date('Y-m-d H:i:s', $updateDatetime))
            ->where("status_id", "=", 0)
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
            ->where("status_id", "=", 0)
            ->first();
    }
}
