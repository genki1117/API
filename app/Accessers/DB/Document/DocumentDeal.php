<?php
declare(strict_types=1);
namespace App\Accessers\DB\Document;

use Illuminate\Http\Request;
use Carbon\CarbonImmutable;
use App\Accessers\DB\FluentDatabase;
use Illuminate\Support\Facades\DB;

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
            ->join("t_doc_storage_transaction", function($query) {
                return $query->on("t_doc_storage_transaction.document_id","t_document_deal.document_id")
                    ->where("t_doc_storage_transaction.company_id","t_document_deal.company_id")
                    ->where("t_doc_storage_transaction.delete_datetime",null);
            })
            ->leftjoin("m_company_counter_party", function($query) {
                return $query->on("t_document_deal.company_id","m_company_counter_party.company_id")
                    ->where("t_document_deal.counter_party_id","m_company_counter_party.counter_party_id")
                    ->where("m_company_counter_party.effe_start_date","<=","CURRENT_DATE")
                    ->where("m_company_counter_party.effe_end_date",">=","CURRENT_DATE")
                    ->where("m_company_counter_party.delete_datetime",null);
            })
            ->where("t_document_deal.delete_datetime",null)
            ->where("t_document_deal.document_id",$documentId)
            ->where("t_document_deal.company_id",$companyId)
            ->whereExists(function($query) use($userId) {
                return $query->from("t_document_workflow as tdw")
                    ->select(DB::raw(1))
                    ->where("tdw.company_id","=","t_document_deal.company_id")
                    ->where("tdw.document_id","=","t_document_deal.document_id")
                    ->where("tdw.category_id","=","t_document_deal.category_id")
                    ->where("tdw.delete_datetime",null)
                    ->where("tdw.app_user_id",$userId)
                    ->where(function($join) {
                        return $join->where("tdw.wf_sort",0)
                            ->orWhere("tdw.app_status",6);
                    })
                    ->union(
                            DB::table("t_doc_permission_transaction as tdpt")
                            ->select(DB::raw(1))
                            ->where("tdpt.company_id","=","t_document_deal.company_id")
                            ->where("tdpt.document_id","=","t_document_deal.document_id")
                            ->where("tdpt.delete_datetime",null)
                            ->where("tdpt.user_id",$userId)
                    )
                    ->union(
                            DB::table("m_user as mu")
                            ->select(DB::raw(1))
                            ->join("m_user_role as mur", function($join) {
                                return $join->on("mur.company_id","mu.company_id")
                                    ->where("mur.user_id","mu.user_id")
                                    ->where("mur.delete_datetime",null);
                            })
                            ->where("mu.company_id","=","t_document_deal.company_id")
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
            'company_id' => $company_id,
            'template_id' => $request->template_id ?? null,
            'category_id' => $request->category_id,
            'doc_type_id' => $request->doc_type_id ?? null,
            'status_id' => $request->status_id ?? null,
            'issue_date' => $request->issue_date ?? null,
            'expiry_date'=> $request->expiry_date ?? null,
            'payment_date' => $request->payment_date ?? null,
            'transaction_date' => $request->transaction_date ?? null,
            'download_date' => $request->download_date ?? null,
            'doc_no' => $request->doc_no ?? null,
            'ref_doc_no' => json_encode($request->ref_doc_no, JSON_UNESCAPED_UNICODE) ?? null,
            'product_name' => $request->product_name ?? null,
            'title' => $request->title ?? null,
            'amount' => $request->amount ?? null,
            'currency_id' => $request->currency_id ?? null,
            'counter_party_id' => $request->counter_party_id ?? null,
            'remarks' => $request->remarks ?? null,
            'doc_info' => json_encode($request->doc_info, JSON_UNESCAPED_UNICODE) ?? null,
            'sign_level' => $request->sign_level ?? null,
            'create_user' => $login_user,
            'create_datetime' => CarbonImmutable::now()->format('Y/m/d H:i:s'),
            'update_user' => $login_user ?? null,
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
        $data = [
            'company_id' => $company_id,
            'template_id' => $request->template_id ?? null,
            'category_id' => $request->category_id,
            'doc_type_id' => $request->doc_type_id ?? null,
            'status_id' => $request->status_id ?? null,
            'issue_date' => $request->issue_date ?? null,
            'expiry_date'=> $request->expiry_date ?? null,
            'payment_date' => $request->payment_date ?? null,
            'transaction_date' => $request->transaction_date ?? null,
            'download_date' => $request->download_date ?? null,
            'doc_no' => $request->doc_no ?? null,
            'ref_doc_no' => json_encode($request->ref_doc_no, JSON_UNESCAPED_UNICODE) ?? null,
            'product_name' => $request->product_name ?? null,
            'title' => $request->title ?? null,
            'amount' => $request->amount ?? null,
            'currency_id' => $request->currency_id ?? null,
            'counter_party_id' => $request->counter_party_id ?? null,
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
