<?php
declare(strict_types=1);
namespace App\Accessers\DB\Document;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use App\Accessers\DB\FluentDatabase;

class DocumentContract extends FluentDatabase
{
    protected string $table = "t_document_contract";

    /**
     * ---------------------------------------------
     * 契約書類情報を取得する
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
                "t_document_contract.document_id",
                "t_document_contract.company_id",
                "t_document_contract.category_id",
                "t_document_contract.doc_type_id",
                "t_document_contract.status_id",
                "t_document_contract.cont_start_date",
                "t_document_contract.cont_end_date",
                "t_document_contract.conc_date",
                "t_document_contract.effective_date",
                "t_document_contract.doc_no",
                "t_document_contract.ref_doc_no",
                "t_document_contract.title",
                "t_document_contract.amount",
                "t_document_contract.currency_id",
                "t_document_contract.counter_party_id",
                "t_document_contract.remarks",
                "t_document_contract.doc_info",
                "t_document_contract.sign_level",
                "t_document_contract.product_name",
                DB::raw("UNIX_TIMESTAMP(t_document_contract.update_datetime) as update_datetime"),
                "t_doc_storage_contract.file_path",
                "t_doc_storage_contract.total_pages",
                "m_company_counter_party.counter_party_name"
            ])
            ->join("t_doc_storage_contract", function ($query) {
                return $query->on("t_doc_storage_contract.document_id", "t_document_contract.document_id")
                    ->where("t_doc_storage_contract.company_id", "t_document_contract.company_id")
                    ->where("t_doc_storage_contract.delete_datetime", null);
            })
            ->leftjoin("m_company_counter_party", function ($query) {
                return $query->on("t_document_contract.company_id", "m_company_counter_party.company_id")
                    ->where("t_document_contract.counter_party_id", "m_company_counter_party.counter_party_id")
                    ->where("m_company_counter_party.effe_start_date", "<=", "CURRENT_DATE")
                    ->where("m_company_counter_party.effe_end_date", ">=", "CURRENT_DATE")
                    ->where("m_company_counter_party.delete_datetime", null);
            })
            ->where("t_document_contract.delete_datetime", null)
            ->where("t_document_contract.document_id", $documentId)
            ->where("t_document_contract.company_id", $companyId)
            ->whereExists(function ($query) use ($userId) {
                return $query->from("t_document_workflow as tdw")
                    ->select(DB::raw(1))
                    ->join($this->table, function ($join) {
                        return $join->on("tdw.company_id", "t_document_contract.company_id")
                            ->on("tdw.document_id", "t_document_contract.document_id")
                            ->on("tdw.category_id", "t_document_contract.category_id");
                    })
                    ->where("tdw.delete_datetime", null)
                    ->where("tdw.app_user_id", $userId)
                    ->where(function ($jQuery) {
                        return $jQuery->where("tdw.wf_sort", 0)
                            ->orWhere("tdw.app_status", 6);
                    })
                    ->union(
                        DB::table("t_doc_permission_contract as tdpc")
                            ->select(DB::raw(1))
                            ->join($this->table, function ($join) {
                                return $join->on("tdpc.company_id", "t_document_contract.company_id")
                                    ->on("tdpc.document_id", "t_document_contract.document_id");
                            })
                            ->where("tdpc.delete_datetime", null)
                            ->where("tdpc.user_id", $userId)
                    )
                    ->union(
                        DB::table("m_user as mu")
                        ->select(DB::raw(1))
                        ->join("m_user_role as mur", function ($join) {
                            return $join->on("mur.company_id", "mu.company_id")
                                ->where("mur.user_id", "mu.user_id")
                                ->where("mur.delete_datetime", null);
                        })
                        ->join($this->table, function ($join) {
                            return $join->on("mu.company_id", "t_document_contract.company_id");
                        })
                        ->where("mu.delete_datetime", null)
                        ->where("mu.user_id", $userId)
                    );
            })
            ->first();
    }

    /**
     * ---------------------------------------------
     * 更新項目（契約書類）
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
