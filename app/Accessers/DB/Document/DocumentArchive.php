<?php
declare(strict_types=1);
namespace App\Accessers\DB\Document;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use App\Accessers\DB\FluentDatabase;

class DocumentArchive extends FluentDatabase
{
    protected string $table = "t_document_archive";

    /**
     * ---------------------------------------------
     * 登録書類情報を取得する
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
                "t_document_archive.document_id",
                "t_document_archive.company_id",
                "t_document_archive.category_id",
                "t_document_archive.doc_type_id",
                "t_document_archive.scan_doc_flg",
                "t_document_archive.status_id",
                "t_document_archive.issue_date",
                "t_document_archive.expiry_date",
                "t_document_archive.transaction_date",
                "t_document_archive.doc_no",
                "t_document_archive.ref_doc_no",
                "t_document_archive.title",
                "t_document_archive.product_name",
                "t_document_archive.amount",
                "t_document_archive.currency_id",
                "t_document_archive.counter_party_id",
                "t_document_archive.remarks",
                "t_document_archive.doc_info",
                "t_document_archive.sign_level",
                "t_document_archive.timestamp_user",
                DB::raw("UNIX_TIMESTAMP(t_document_archive.update_datetime) as update_datetime"),
                "t_doc_storage_archive.file_path",
                "t_doc_storage_archive.total_pages",
                "m_company_counter_party.counter_party_name",
                "m_user.family_name",
                "m_user.first_name"
            ])
            ->join("t_doc_storage_archive", function ($query) {
                return $query->on("t_doc_storage_archive.document_id", "t_document_archive.document_id")
                    ->where("t_doc_storage_archive.company_id", "t_document_archive.company_id")
                    ->where("t_doc_storage_archive.delete_datetime", null);
            })
            ->leftjoin("m_company_counter_party", function ($query) {
                return $query->on("t_document_archive.company_id", "m_company_counter_party.company_id")
                    ->where("t_document_archive.counter_party_id", "m_company_counter_party.counter_party_id")
                    ->where("m_company_counter_party.effe_start_date", "<=", "CURRENT_DATE")
                    ->where("m_company_counter_party.effe_end_date", ">=", "CURRENT_DATE")
                    ->where("m_company_counter_party.delete_datetime", null);
            })
            ->leftjoin("m_user", function ($query) {
                return $query->on("m_user.user_id", "t_document_archive.timestamp_user")
                    ->where("m_user.company_id", "t_document_archive.company_id")
                    ->where("m_user.delete_datetime", null);
            })
            ->where("t_document_archive.delete_datetime", null)
            ->where("t_document_archive.document_id", $documentId)
            ->where("t_document_archive.company_id", $companyId)
            ->whereExists(function ($query) use ($userId) {
                $query->from("t_document_workflow as tdw")
                    ->select(DB::raw(1))
                    ->where("tdw.company_id", "t_document_archive.company_id")
                    ->where("tdw.company_id", "t_document_archive.document_id")
                    ->where("tdw.company_id", "t_document_archive.category_id")
                    ->where("tdw.delete_datetime", null)
                    ->where("tdw.app_user_id", $userId)
                    ->where(function ($join) {
                        return $join->where("tdw.wf_sort", 0)
                            ->orWhere("tdw.app_status", 6);
                    })
                    ->union(
                        DB::table("t_doc_permission_archive as tdpa")
                        ->select(DB::raw(1))
                        ->where("tdpa.company_id", "t_document_archive.company_id")
                        ->where("tdpa.company_id", "t_document_archive.document_id")
                        ->where("tdpa.company_id", "t_document_archive.category_id")
                        ->where("tdpa.delete_datetime", null)
                    )
                    ->union(
                        DB::table("m_user as mu")
                        ->select(DB::raw(1))
                        ->join("m_user_role as mur", function ($join) {
                            return $join->on("mur.company_id", "mu.company_id")
                                ->where("mur.user_id", "mu.user_id")
                                ->where("mur.delete_datetime", null);
                        })
                        ->where("mu.company_id", "t_document_archive.company_id")
                        ->where("mu.delete_datetime", null)
                    );
            })
            ->first();
    }

    /**
     * ---------------------------------------------
     * 更新項目（登録書類）
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
