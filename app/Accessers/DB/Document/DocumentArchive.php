<?php
declare(strict_types=1);
namespace App\Accessers\DB\Document;

use Illuminate\Http\Request;
use Carbon\CarbonImmutable;
use App\Accessers\DB\FluentDatabase;
use Illuminate\Support\Facades\DB;

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
            ->join("t_doc_storage_archive", function($query) {
                return $query->on("t_doc_storage_archive.document_id","t_document_archive.document_id")
                    ->where("t_doc_storage_archive.company_id","t_document_archive.company_id")
                    ->where("t_doc_storage_archive.delete_datetime",null);
            })
            ->leftjoin("m_company_counter_party", function($query) {
                return $query->on("t_document_archive.company_id","m_company_counter_party.company_id")
                    ->where("t_document_archive.counter_party_id","m_company_counter_party.counter_party_id")
                    ->where("m_company_counter_party.effe_start_date","<=","CURRENT_DATE")
                    ->where("m_company_counter_party.effe_end_date",">=","CURRENT_DATE")
                    ->where("m_company_counter_party.delete_datetime",null);
            })
            ->leftjoin("m_user", function($query) {
                return $query->on("m_user.user_id","t_document_archive.timestamp_user")
                    ->where("m_user.company_id","t_document_archive.company_id")
                    ->where("m_user.delete_datetime",null);
            })
            ->where("t_document_archive.delete_datetime",null)
            ->where("t_document_archive.document_id",$documentId)
            ->where("t_document_archive.company_id",$companyId)
            ->whereExists(function($query) use($userId) {
                $query->from("t_document_workflow as tdw")
                    ->select(DB::raw(1))
                    ->where("tdw.company_id","t_document_archive.company_id")
                    ->where("tdw.company_id","t_document_archive.document_id")
                    ->where("tdw.company_id","t_document_archive.category_id")
                    ->where("tdw.delete_datetime",null)
                    ->where("tdw.app_user_id",$userId)
                    ->where(function($join) {
                        return $join->where("tdw.wf_sort",0)
                            ->orWhere("tdw.app_status",6);
                    })
                    ->union(
                        DB::table("t_doc_permission_archive as tdpa")
                        ->select(DB::raw(1))
                        ->where("tdpa.company_id","t_document_archive.company_id")
                        ->where("tdpa.company_id","t_document_archive.document_id")
                        ->where("tdpa.company_id","t_document_archive.category_id")
                        ->where("tdpa.delete_datetime",null)
                    )
                    ->union(
                        DB::table("m_user as mu")
                        ->select(DB::raw(1))
                        ->join("m_user_role as mur", function($join) {
                            return $join->on("mur.company_id","mu.company_id")
                                ->where("mur.user_id","mu.user_id")
                                ->where("mur.delete_datetime",null);
                        })
                        ->where("mu.company_id","t_document_archive.company_id")
                        ->where("mu.delete_datetime",null)
                    );
            })
            ->first();
    }

    public function save($request): bool
    {
        $login_user = 1; //$request->m_user->user_id;
        $company_id = 1; //$request->m_user->company_id;
        $scan_doc_flg = 1;

        $data = [
            'company_id' => $company_id, // 変更
            'template_id' => $request->template_id ?? null,
            'category_id' => $request->category_id,
            'doc_type_id' => $request->doc_type_id ?? null,
            'scan_doc_flg' => $scan_doc_flg, // 変更
            'status_id' => $request->status_id ?? null,
            'issue_date' => $request->issue_date ?? null,
            'expiry_date' => $request->expiry_date ?? null,
            'transaction_date' => $request->transaction_date ?? null,
            'doc_no' => $request->doc_no ?? null,
            'ref_doc_no' => json_encode($request->ref_doc_no, JSON_UNESCAPED_UNICODE) ?? null,
            'title' => $request->title ?? null,
            'product_name' => $request->product_name ?? null,
            'amount' => $request->amount ?? null,
            'currency_id' => $request->currency_id ?? null,
            'counter_party_id' => $request->counter_party_id ?? null,
            'remarks' => $request->remarks ?? null,
            'doc_info' => json_encode($request->doc_info, JSON_UNESCAPED_UNICODE) ?? null,
            'sign_level' => $request->sign_level ?? null,
            'timestamp_user' => $request->timestamp_user ?? null,
            'create_user' => $login_user, // 変更
            'create_datetime' => CarbonImmutable::now()->format('Y/m/d H:i:s'),
            'update_user' => $login_user, // 変更
            'update_datetime' => CarbonImmutable::now()->format('Y/m/d H:i:s'),
            'delete_user' => null,
            'delete_datetime' => null
        ];
        return $this->builder($this->table)->insert($data);
    }

    public function update($request)
    {
        $login_user = 1; //$request->m_user->user_id;
        $company_id = 1; //$request->m_user->company_id;
        $scan_doc_flg = 1;

        $data = [
            'company_id' => $company_id, // 変更
            'company_id' => $company_id, // 変更
            'template_id' => $request->template_id ?? null,
            'category_id' => $request->category_id,
            'doc_type_id' => $request->doc_type_id ?? nul,
            'scan_doc_flg' => $scan_doc_flg, // 変更
            'status_id' => $request->status_id ?? null,
            'issue_date' => $request->issue_date ?? null,
            'expiry_date' => $request->expiry_date ?? null,
            'transaction_date' => $request->transaction_date ?? null,
            'doc_no' => $request->doc_no ?? null,
            'ref_doc_no' => json_encode($request->ref_doc_no, JSON_UNESCAPED_UNICODE) ?? null,
            'title' => $request->title ?? null,
            'product_name' => $request->product_name ?? null,
            'amount' => $request->amount ?? null,
            'currency_id' => $request->currency_id ?? null,
            'counter_party_id' => $request->counter_party_id ?? null,
            'remarks' => $request->remarks ?? null,
            'doc_info' => json_encode($request->doc_info, JSON_UNESCAPED_UNICODE) ?? null,
            'sign_level' => $request->sign_level ?? null,
            'timestamp_user' => $request->timestamp_user ?? null,
            'create_user' => $login_user, // 変更
            'create_datetime' => CarbonImmutable::now()->format('Y/m/d H:i:s'),
            'update_user' => $login_user, // 変更
            'update_datetime' => CarbonImmutable::now()->format('Y/m/d H:i:s'),
            'delete_user' => null,
            'delete_datetime' => null
        ];
        return $this->builder($this->table)
            ->where('document_id', $request->document_id)
            ->where('company_id', $company_id)
            ->where('category_id', $request->category_id)
            ->update($data);
    }
}
// https://qiita.com/youstr/items/04018908522be2eda8ff
//楽観ロック
