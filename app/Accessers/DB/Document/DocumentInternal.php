<?php
declare(strict_types=1);
namespace App\Accessers\DB\Document;

use Illuminate\Http\Request;
use Carbon\CarbonImmutable;
use App\Accessers\DB\FluentDatabase;
use Illuminate\Support\Facades\DB;

class DocumentInternal extends FluentDatabase
{
    protected string $table = "t_document_internal";

    /**
     * ---------------------------------------------
     * 社内書類情報を取得する
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
                "t_document_internal.document_id",
                "t_document_internal.company_id",
                "t_document_internal.category_id",
                "t_document_internal.doc_type_id",
                "t_document_internal.status_id",
                "t_document_internal.doc_create_date",
                "t_document_internal.sign_finish_date",
                "t_document_internal.doc_no",
                "t_document_internal.ref_doc_no",
                "t_document_internal.product_name",
                "t_document_internal.title",
                "t_document_internal.amount",
                "t_document_internal.currency_id",
                "t_document_internal.counter_party_id",
                "t_document_internal.content",
                "t_document_internal.remarks",
                "t_document_internal.doc_info",
                "t_document_internal.sign_level",
                DB::raw("UNIX_TIMESTAMP(t_document_internal.update_datetime) as update_datetime"),
                "t_doc_storage_internal.file_path",
                "t_doc_storage_internal.total_pages",
                "m_company_counter_party.counter_party_name"
            ])
            ->join("t_doc_storage_internal", function($query) {
                return $query->on("t_doc_storage_internal.document_id","t_document_internal.document_id")
                    ->where("t_doc_storage_internal.company_id","t_document_internal.company_id")
                    ->where("t_doc_storage_internal.delete_datetime",null);
            })
            ->leftjoin("m_company_counter_party", function($query) {
                return $query->on("t_document_internal.company_id","m_company_counter_party.company_id")
                    ->where("t_document_internal.counter_party_id","m_company_counter_party.counter_party_id")
                    ->where("m_company_counter_party.effe_start_date","<=","CURRENT_DATE")
                    ->where("m_company_counter_party.effe_end_date",">=","CURRENT_DATE")
                    ->where("m_company_counter_party.delete_datetime",null);
            })
            ->where("t_document_internal.delete_datetime",null)
            ->where("t_document_internal.document_id",$documentId)
            ->where("t_document_internal.company_id",$companyId)
            ->whereExists(function($query) use($userId) {
                return $query->from("t_document_workflow as tdw")
                    ->select(DB::raw(1))
                    ->where("tdw.company_id","=","t_document_internal.company_id")
                    ->where("tdw.document_id","=","t_document_internal.document_id")
                    ->where("tdw.category_id","=","t_document_internal.category_id")
                    ->where("tdw.delete_datetime",null)
                    ->where("tdw.app_user_id",$userId)
                    ->where(function($join) {
                        return $join->where("tdw.wf_sort",0)
                            ->orWhere("tdw.app_status",6);
                    })
                    ->union(
                        DB::table("t_doc_permission_internal as tdpi")
                            ->select(DB::raw(1))
                            ->where("tdpi.company_id","=","t_document_internal.company_id")
                            ->where("tdpi.document_id","=","t_document_internal.document_id")
                            ->where("tdpi.delete_datetime",null)
                            ->where("tdpi.user_id",$userId)
                    )
                    ->union(
                        DB::table("m_user as mu")
                            ->select(DB::raw(1))
                            ->join("m_user_role as mur",function($join) {
                                return $join->on("mur.company_id","=","mu.company_id")
                                    ->where("mur.user_id","=","mu.user_id")
                                    ->where("mur.delete_datetime",null);
                            })
                            ->where("mu.company_id","=","t_document_internal.company_id")
                            ->where("mu.delete_datetime",null)
                            ->where("mu.user_id",$userId)
                    );
            })
            ->first();
    }
    public function save($request): bool
    {
        $login_user = 1; //$request->m_user->user_id;
        $company_id = 1; //$request->m_user->company_id;

        $data = [
            'company_id' => $company_id ?? null,
            'template_id' => $request->template_id ?? null,
            'category_id' => $request->category_id ?? null,
            'doc_type_id' => $request->doc_type_id ?? null,
            'status_id' => $request->status_id ?? null,
            'doc_create_date' => $request->doc_create_date ?? null,
            'sign_finish_date' => $request->sign_finish_date ?? null,
            'doc_no' => $request->doc_no ?? null,
            'ref_doc_no' => json_encode($request->ref_doc_no, JSON_UNESCAPED_UNICODE) ?? null,
            'product_name' => $request->product_name ?? null,
            'title' => $request->title ?? null,
            'amount' => $request->amount ?? null,
            'currency_id' => $request->currency_id ?? null,
            'counter_party_id' => $request->counter_party_id ?? null,
            'content' => $request->content ?? null,
            'remarks' => $request->remarks ?? null,
            'doc_info' => json_encode($request->doc_info, JSON_UNESCAPED_UNICODE) ?? null,
            'sign_level' => $request->sign_level ?? null,
            'create_user' => $login_user ?? null,
            'create_datetime' => CarbonImmutable::now()->format('Y/m/d H:i:s') ?? null,
            'update_user' => $login_user ?? null,
            'update_datetime' => CarbonImmutable::now()->format('Y/m/d H:i:s') ?? null,
            'delete_user' => null,
            'delete_datetime' => null
        ];
        return $this->builder($this->table)->insert($data);
    }

    public function update($request)
    {
        $login_user = 1; //$request->m_user->user_id;
        $company_id = 1; //$request->m_user->company_id;
        $data = [
            'company_id' => $company_id ?? null,
            'template_id' => $request->template_id ?? null,
            'category_id' => $request->category_id ?? null,
            'doc_type_id' => $request->doc_type_id ?? null,
            'status_id' => $request->status_id ?? null,
            'doc_create_date' => $request->doc_create_date ?? null,
            'sign_finish_date' => $request->sign_finish_date ?? null,
            'doc_no' => $request->doc_no ?? null,
            'ref_doc_no' => json_encode($request->ref_doc_no, JSON_UNESCAPED_UNICODE) ?? null,
            'product_name' => $request->product_name ?? null,
            'title' => $request->title ?? null,
            'amount' => $request->amount ?? null,
            'currency_id' => $request->currency_id ?? null,
            'counter_party_id' => $request->counter_party_id ?? null,
            'content' => $request->content ?? null,
            'remarks' => $request->remarks ?? null,
            'doc_info' => json_encode($request->doc_info, JSON_UNESCAPED_UNICODE) ?? null,
            'sign_level' => $request->sign_level ?? null,
            'create_user' => $login_user,
            'create_datetime' => CarbonImmutable::now()->format('Y/m/d H:i:s'),
            'update_user' => $login_user,
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
