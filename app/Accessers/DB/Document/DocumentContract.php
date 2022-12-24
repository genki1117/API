<?php
declare(strict_types=1);
namespace App\Accessers\DB\Document;

use Illuminate\Http\Request;
use Carbon\CarbonImmutable;
use App\Accessers\DB\FluentDatabase;
use Illuminate\Support\Facades\DB;

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
            ->join("t_doc_storage_contract", function($query) {
                return $query->on("t_doc_storage_contract.document_id","t_document_contract.document_id")
                    ->where("t_doc_storage_contract.company_id","t_document_contract.company_id")
                    ->where("t_doc_storage_contract.delete_datetime",null);
            })
            ->leftjoin("m_company_counter_party", function($query) {
                return $query->on("t_document_contract.company_id","m_company_counter_party.company_id")
                    ->where("t_document_contract.counter_party_id","m_company_counter_party.counter_party_id")
                    ->where("m_company_counter_party.effe_start_date","<=","CURRENT_DATE")
                    ->where("m_company_counter_party.effe_end_date",">=","CURRENT_DATE")
                    ->where("m_company_counter_party.delete_datetime",null);
            })
            ->where("t_document_contract.delete_datetime",null)
            ->where("t_document_contract.document_id",$documentId)
            ->where("t_document_contract.company_id",$companyId)
            ->whereExists(function($query) use($userId) {
                return $query->from("t_document_workflow as tdw")
                    ->select(DB::raw(1))
                    ->join($this->table, function($join) {
                        return $join->on("tdw.company_id","t_document_contract.company_id")
                            ->on("tdw.document_id","t_document_contract.document_id")
                            ->on("tdw.category_id","t_document_contract.category_id");
                    })
                    ->where("tdw.delete_datetime",null)
                    ->where("tdw.app_user_id",$userId)
                    ->where(function($jQuery) {
                        return $jQuery->where("tdw.wf_sort",0)
                            ->orWhere("tdw.app_status",6);
                    })
                    ->union(
                        DB::table("t_doc_permission_contract as tdpc")
                            ->select(DB::raw(1))
                            ->join($this->table, function($join) {
                                return $join->on("tdpc.company_id","t_document_contract.company_id")
                                    ->on("tdpc.document_id","t_document_contract.document_id");
                            })
                            ->where("tdpc.delete_datetime",null)
                            ->where("tdpc.user_id",$userId)
                    )
                    ->union(
                            DB::table("m_user as mu")
                            ->select(DB::raw(1))
                            ->join("m_user_role as mur", function($join) {
                                return $join->on("mur.company_id","mu.company_id")
                                    ->where("mur.user_id","mu.user_id")
                                    ->where("mur.delete_datetime",null);
                            })
                            ->join($this->table, function($join) {
                                return $join->on("mu.company_id","t_document_contract.company_id");
                            })
                            ->where("mu.delete_datetime",null)
                            ->where("mu.user_id",$userId)
                    );
            })
            ->first();
    }

    public function insert(array $requestContent)
    {
        // ***m_user***
        // $requestContent['m_user_id']
        // $requestContent['m_user_company_id']
        // $requestContent['m_user_type_id']
        return $this->builder($this->table)->insert([
            'company_id'       => $requestContent['company_id'],
            'category_id'      => $requestContent['category_id'],
            'template_id'      => $requestContent['template_id'],
            'doc_type_id'      => $requestContent['doc_type_id'],
            'status_id'        => $requestContent['status_id'],
            'cont_start_date'  => $requestContent['cont_start_date'],
            'cont_end_date'    => $requestContent['cont_end_date'],
            'conc_date'        => $requestContent['conc_date'],
            'effective_date'   => $requestContent['effective_date'],
            'cancel_date'      => $requestContent['cancel_date'],
            'expiry_date'      => $requestContent['expiry_date'],
            'doc_no'           => $requestContent['company_id'],
            'ref_doc_no'       => json_encode($requestContent['ref_doc_no'], JSON_UNESCAPED_UNICODE),
            'product_name'     => $requestContent['product_name'],
            'title'            => $requestContent['title'],
            'amount'           => $requestContent['amount'],
            'currency_id'      => $requestContent['currency_id'],
            'counter_party_id' => $requestContent['counter_party_id'],
            'remarks'          => $requestContent['remarks'],
            'doc_info'         => json_encode($requestContent['doc_info'], JSON_UNESCAPED_UNICODE),
            'sign_level'       => $requestContent['sign_level'],
            'create_user'      => $requestContent['create_user'],
            'create_datetime'  => $requestContent['create_datetime'],
            'update_user'      => $requestContent['update_user'],
            'update_datetime'  => $requestContent['update_datetime'],
            'delete_user'      => $requestContent['delete_user'],
            'delete_datetime'  => $requestContent['delete_datetime']
    ]);
    }

    public function update($requestContent)
    {
        return $this->builder($this->table)
            ->where('document_id', $requestContent['document_id'])
            ->where('company_id', $requestContent['company_id'])
            ->where('category_id', $requestContent['category_id'])
            ->update([
                'template_id'      => $requestContent['template_id'],
                'doc_type_id'      => $requestContent['doc_type_id'],
                'status_id'        => $requestContent['status_id'],
                'cont_start_date'  => $requestContent['cont_start_date'],
                'cont_end_date'    => $requestContent['cont_end_date'],
                'conc_date'        => $requestContent['conc_date'],
                'effective_date'   => $requestContent['effective_date'],
                'cancel_date'      => $requestContent['cancel_date'],
                'doc_no'           => $requestContent['doc_no'],
                'ref_doc_no'       => json_encode($requestContent['ref_doc_no'], JSON_UNESCAPED_UNICODE),
                'product_name'     => $requestContent['product_name'],
                'title'            => $requestContent['title'],
                'amount'           => $requestContent['amount'],
                'currency_id'      => $requestContent['currency_id'],
                'counter_party_id' => $requestContent['counter_party_id'],
                'remarks'          => $requestContent['remarks'],
                'doc_info'         => $requestContent['doc_info'],
                'ref_doc_no'       => json_encode($requestContent['ref_doc_no'], JSON_UNESCAPED_UNICODE),
                'sign_level'       => $requestContent['sign_level'],
                'update_user'      => $requestContent['update_user'],
                'update_datetime'  => $requestContent['update_datetime'],
                'delete_user'      => $requestContent['delete_user'],
                'delete_datetime'  => $requestContent['delete_datetime']
        ]);
    }
}