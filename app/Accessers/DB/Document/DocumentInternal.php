<?php
declare(strict_types=1);
namespace App\Accessers\DB\Document;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use App\Accessers\DB\FluentDatabase;

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
            ->join("t_doc_storage_internal", function ($query) {
                return $query->on("t_doc_storage_internal.document_id", "t_document_internal.document_id")
                    ->where("t_doc_storage_internal.company_id", "t_document_internal.company_id")
                    ->where("t_doc_storage_internal.delete_datetime", null);
            })
            ->leftjoin("m_company_counter_party", function ($query) {
                return $query->on("t_document_internal.company_id", "m_company_counter_party.company_id")
                    ->where("t_document_internal.counter_party_id", "m_company_counter_party.counter_party_id")
                    ->where("m_company_counter_party.effe_start_date", "<=", "CURRENT_DATE")
                    ->where("m_company_counter_party.effe_end_date", ">=", "CURRENT_DATE")
                    ->where("m_company_counter_party.delete_datetime", null);
            })
            ->where("t_document_internal.delete_datetime", null)
            ->where("t_document_internal.document_id", $documentId)
            ->where("t_document_internal.company_id", $companyId)
            ->whereExists(function ($query) use ($userId) {
                return $query->from("t_document_workflow as tdw")
                    ->select(DB::raw(1))
                    ->where("tdw.company_id", "=", "t_document_internal.company_id")
                    ->where("tdw.document_id", "=", "t_document_internal.document_id")
                    ->where("tdw.category_id", "=", "t_document_internal.category_id")
                    ->where("tdw.delete_datetime", null)
                    ->where("tdw.app_user_id", $userId)
                    ->where(function ($join) {
                        return $join->where("tdw.wf_sort", 0)
                            ->orWhere("tdw.app_status", 6);
                    })
                    ->union(
                        DB::table("t_doc_permission_internal as tdpi")
                            ->select(DB::raw(1))
                            ->where("tdpi.company_id", "=", "t_document_internal.company_id")
                            ->where("tdpi.document_id", "=", "t_document_internal.document_id")
                            ->where("tdpi.delete_datetime", null)
                            ->where("tdpi.user_id", $userId)
                    )
                    ->union(
                        DB::table("m_user as mu")
                            ->select(DB::raw(1))
                            ->join("m_user_role as mur", function ($join) {
                                return $join->on("mur.company_id", "=", "mu.company_id")
                                    ->where("mur.user_id", "=", "mu.user_id")
                                    ->where("mur.delete_datetime", null);
                            })
                            ->where("mu.company_id", "=", "t_document_internal.company_id")
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
     * @return \Illuminate\Http\Response
     */
    public function getDocumentList(array $mUser, array $condition, array $sort, array $page)
    {
        return $this->builder()
            ->select([
                't_document_internal.document_id',
                't_document_internal.category_id',
                't_document_internal.doc_create_date as transaction_date',
                't_document_internal.doc_type_id',
                't_document_internal.title',
                't_document_internal.amount',
                't_document_internal.currency_id',
                't_document_internal.status_id',
                DB::raw('UNIX_TIMESTAMP(t_document_internal.update_datetime)'),
                't_document_workflow.app_status',
                'm_user.full_name as create_user',
                DB::raw("CONCAT(m_company_counter_party.company_id, ' ', m_company_counter_party.counter_party_name) as counter_party_name")
            ])
            ->join('m_user', function ($query) {
                return $query->on('m_user.user_id', '=', 't_document_internal.create_user')
                ->where('m_user.company_id', '=', 't_document_internal.company_id')
                ->whereNull('m_user.delete_datetime');
            })
            ->leftJoin('t_document_workflow', function ($query) use ($mUser) {
                return $query->on('t_document_internal.document_id', '=', 't_document_workflow.document_id')
                ->where('t_document_internal.category_id', '=', 't_document_workflow.category_id')
                ->where('t_document_workflow.company_id', '=', $mUser['company_id'])
                ->where('t_document_workflow.app_user_id', '=', $mUser['user_id'])
                ->whereNull('t_document_workflow.delete_datetime');
            })
            ->join('m_company_counter_party', function ($query) {
                return $query->on('m_company_counter_party.company_id', '=', 't_document_internal.company_id')
                ->on('m_company_counter_party.counter_party_id', '=', 't_document_internal.counter_party_id')
                ->where('m_company_counter_party.effe_start_date', '<=', DB::raw('CURRENT_DATE'))
                ->where('m_company_counter_party.effe_end_date', '>=', DB::raw('CURRENT_DATE'))
                ->whereNull('m_company_counter_party.delete_datetime');
            })
            ->whereNull('t_document_internal.delete_datetime')
            ->where('t_document_internal.company_id', '=', $mUser['company_id'])
            ->where('t_document_internal.title', 'like', '%'.$condition['search_input'].'%')
            ->whereIn('t_document_internal.status_id', [$condition['status_id']])
            ->where('t_document_internal.category_id', '=', $condition['category_id'])
            ->where('t_document_internal.doc_type_id', '=', $condition['register_type_id'])
            ->where('t_document_internal.title', 'like', '%'.$condition['title'].'%')
            ->where('t_document_internal.amount', '<=', $condition['amount']['from'])
            ->where('t_document_internal.amount', '>=', $condition['amount']['to'])
            ->whereIn('t_document_internal.currency_id', [$condition['currency_id']])
            ->where('t_document_internal.product_name', 'like', '%'.$condition['product_name'].'%')
            ->where('t_document_internal.document_id', 'like', '%'.$condition['document_id'].'%')
            ->where('t_document_internal.doc_no', 'like', '%'.$condition['doc_no'].'%')
            ->where('t_document_internal.ref_doc_no', 'like', '%'.$condition['ref_doc_no'].'%')
            ->where('t_document_internal.content', 'like', '%'.$condition['content'].'%')
            ->whereRaw('JSON_CONTAINS(t_document_internal.doc_info->"$.title", \'["'.$condition['doc_info']['title'].'"]\')')
            ->whereRaw('JSON_CONTAINS(t_document_internal.doc_info->"$.content", \'["'.$condition['doc_info']['content'].'"]\')')
            ->where('t_document_internal.create_datetime', '>=', $condition['create_datetime']['from'])
            ->where('t_document_internal.create_datetime', '<=', $condition['create_datetime']['to'])
            ->where('t_document_internal.doc_create_date', '>=', $condition['doc_create_date']['from'])
            ->where('t_document_internal.doc_create_date', '<=', $condition['doc_create_date']['to'])
            ->where('t_document_internal.sign_finish_date', '>=', $condition['sign_finish_date']['from'])
            ->where('t_document_internal.sign_finish_date', '<=', $condition['sign_finish_date']['to'])
            ->whereExists(function ($query) use ($mUser) {
                return $query->from('t_document_workflow as tdw')
                    ->select(DB::raw(1))
                    ->join($this->table, function ($join) {
                        return $join->on('tdw.company_id', 't_document_internal.company_id')
                            ->on('tdw.document_id', 't_document_internal.document_id')
                            ->on('tdw.category_id', 't_document_internal.category_id');
                    })
                    ->where('tdw.delete_datetime', null)
                    ->where('tdw.app_user_id', '=', $mUser['user_id'])
                    ->where(function ($jQuery) {
                        return $jQuery->where('tdw.wf_sort', '=', 0)
                            ->orWhere('tdw.app_status', '=', 6);
                    })
                    ->union(
                        DB::table('t_doc_permission_contract as tdpc')
                            ->select(DB::raw(1))
                            ->join($this->table, function ($join) {
                                return $join->on('tdpc.company_id', 't_document_internal.company_id')
                                    ->on('tdpc.document_id', 't_document_internal.document_id');
                            })
                            ->whereNull('tdpc.delete_datetime')
                            ->where('tdpc.user_id', '=', $mUser['user_id'])
                    )
                    ->union(
                        DB::table('m_user as mu')
                        ->select(DB::raw(1))
                        ->join('m_user_role as mur', function ($join) {
                            return $join->on('mur.company_id', '=', 'mu.company_id')
                                ->on('mur.user_id', '=', 'mu.user_id')
                                ->where('mur.admin_role', '=', 0)
                                ->whereNull('mur.delete_datetime');
                        })
                        ->join($this->table, function ($join) {
                            return $join->on('mu.company_id', 't_document_internal.company_id');
                        })
                        ->whereNull('mu.delete_datetime')
                        ->where('mu.user_id', '=', $mUser['user_id'])
                    );
            })
            ->whereExists(function ($query) use ($condition) {
                return $query->from('t_document_workflow as tdw')
                    ->select(DB::raw(1))
                    ->join('m_user as mu', function ($join) {
                        return $join->on('mu.company_id', '=', 'tdw.company_id')
                            ->on('mu.user_id', '=', 'tdw.app_user_id')
                            ->where('mu.user_type_id', '=', 0);
                    })
                    ->join('t_document_internal', function ($jQuery) {
                        return $jQuery->on('tdw.company_id', '=', 't_document_internal.company_id')
                            ->on('tdw.document_id', '=', 't_document_internal.document_id')
                            ->on('tdw.category_id', '=', 't_document_internal.category_id');
                    })
                    ->whereNull('tdw.delete_datetime')
                    ->where('tdw.app_user_id', '=', $condition['app_user_id']);
            })
            ->whereExists(function ($query) use ($condition) {
                return $query->from('t_document_workflow as tdw')
                    ->select(DB::raw(1))
                    ->join('m_user as mu', function ($join) {
                        return $join->on('mu.company_id', '=', 'tdw.company_id')
                            ->on('mu.user_id', '=', 'tdw.app_user_id')
                            ->where('mu.user_type_id', '=', 1);
                    })
                    ->join('t_document_internal', function ($jQuery) {
                        return $jQuery->on('tdw.company_id', '=', 't_document_internal.company_id')
                            ->on('tdw.document_id', '=', 't_document_internal.document_id')
                            ->on('tdw.category_id', '=', 't_document_internal.category_id');
                    })
                    ->whereNull('tdw.delete_datetime')
                    ->where('tdw.app_user_id', '=', $condition['app_user_id_guest']);
            })
            ->whereExists(function ($query) use ($condition) {
                return $query->from('t_doc_permission_internal as tdpi')
                    ->select(DB::raw(1))
                    ->join('t_document_internal', function ($jQuery) {
                        return $jQuery->on('tdpi.company_id', '=', 't_document_internal.company_id')
                        ->on('tdpi.document_id', '=', 't_document_internal.document_id');
                    })
                    ->whereNull('tdpi.delete_datetime')
                    ->where('tdpi.user_id', '=', $condition['view_permission_user_id']);
            })
            ->where(function ($query) use ($condition) {
                return $query->where('m_company_counter_party.counter_party_name', 'like', '%'.$condition['counter_party_name'].'%')
                    ->orWhere('m_company_counter_party.counter_party_name_kana', 'like', '%'.$condition['counter_party_name'].'%');
            })
            ->when(empty($sort), function ($query) {
                return $query->orderBy('t_document_internal.document_id', 'ASC')
                    ->orderBy('t_document_internal.category_id', 'DESC');
            })
            ->when(!empty($sort), function ($query) use ($sort) {
                return $query->orderBy('t_document_internal.'.$sort['column_name'], $sort['sort_type']);
            })
            ->limit($page['disp_count'])
            ->offset($page['disp_page'])
            ->get();
    }

    /**
     * @param array $mUser
     * @param array $condition
     * @param array $sort
     * @return int|null
     */
    public function getDocumentListCount(array $mUser, array $condition, array $sort): ?int
    {
        return $this->builder()
            ->select([
                DB::raw('COUNT(*) AS count')
            ])
            ->join('m_user', function ($query) {
                return $query->on('m_user.user_id', '=', 't_document_internal.create_user')
                ->where('m_user.company_id', '=', 't_document_internal.company_id')
                ->whereNull('m_user.delete_datetime');
            })
            ->leftJoin('t_document_workflow', function ($query) use ($mUser) {
                return $query->on('t_document_internal.document_id', '=', 't_document_workflow.document_id')
                ->where('t_document_internal.category_id', '=', 't_document_workflow.category_id')
                ->where('t_document_workflow.company_id', '=', $mUser['company_id'])
                ->where('t_document_workflow.app_user_id', '=', $mUser['user_id'])
                ->whereNull('t_document_workflow.delete_datetime');
            })
            ->join('m_company_counter_party', function ($query) {
                return $query->on('m_company_counter_party.company_id', '=', 't_document_internal.company_id')
                ->on('m_company_counter_party.counter_party_id', '=', 't_document_internal.counter_party_id')
                ->where('m_company_counter_party.effe_start_date', '<=', DB::raw('CURRENT_DATE'))
                ->where('m_company_counter_party.effe_end_date', '>=', DB::raw('CURRENT_DATE'))
                ->whereNull('m_company_counter_party.delete_datetime');
            })
            ->whereNull('t_document_internal.delete_datetime')
            ->where('t_document_internal.company_id', '=', $mUser['company_id'])
            ->where('t_document_internal.title', 'like', '%'.$condition['search_input'].'%')
            ->whereIn('t_document_internal.status_id', [$condition['status_id']])
            ->where('t_document_internal.category_id', '=', $condition['category_id'])
            ->where('t_document_internal.doc_type_id', '=', $condition['register_type_id'])
            ->where('t_document_internal.title', 'like', '%'.$condition['title'].'%')
            ->where('t_document_internal.amount', '<=', $condition['amount']['from'])
            ->where('t_document_internal.amount', '>=', $condition['amount']['to'])
            ->whereIn('t_document_internal.currency_id', [$condition['currency_id']])
            ->where('t_document_internal.product_name', 'like', '%'.$condition['product_name'].'%')
            ->where('t_document_internal.document_id', 'like', '%'.$condition['document_id'].'%')
            ->where('t_document_internal.doc_no', 'like', '%'.$condition['doc_no'].'%')
            ->where('t_document_internal.ref_doc_no', 'like', '%'.$condition['ref_doc_no'].'%')
            ->where('t_document_internal.content', 'like', '%'.$condition['content'].'%')
            ->whereRaw('JSON_CONTAINS(t_document_internal.doc_info->"$.title", \'["'.$condition['doc_info']['title'].'"]\')')
            ->whereRaw('JSON_CONTAINS(t_document_internal.doc_info->"$.content", \'["'.$condition['doc_info']['content'].'"]\')')
            ->where('t_document_internal.create_datetime', '>=', $condition['create_datetime']['from'])
            ->where('t_document_internal.create_datetime', '<=', $condition['create_datetime']['to'])
            ->where('t_document_internal.doc_create_date', '>=', $condition['doc_create_date']['from'])
            ->where('t_document_internal.doc_create_date', '<=', $condition['doc_create_date']['to'])
            ->where('t_document_internal.sign_finish_date', '>=', $condition['sign_finish_date']['from'])
            ->where('t_document_internal.sign_finish_date', '<=', $condition['sign_finish_date']['to'])
            ->whereExists(function ($query) use ($mUser) {
                return $query->from('t_document_workflow as tdw')
                    ->select(DB::raw(1))
                    ->join($this->table, function ($join) {
                        return $join->on('tdw.company_id', 't_document_internal.company_id')
                            ->on('tdw.document_id', 't_document_internal.document_id')
                            ->on('tdw.category_id', 't_document_internal.category_id');
                    })
                    ->where('tdw.delete_datetime', null)
                    ->where('tdw.app_user_id', '=', $mUser['user_id'])
                    ->where(function ($jQuery) {
                        return $jQuery->where('tdw.wf_sort', '=', 0)
                            ->orWhere('tdw.app_status', '=', 6);
                    })
                    ->union(
                        DB::table('t_doc_permission_contract as tdpc')
                            ->select(DB::raw(1))
                            ->join($this->table, function ($join) {
                                return $join->on('tdpc.company_id', 't_document_internal.company_id')
                                    ->on('tdpc.document_id', 't_document_internal.document_id');
                            })
                            ->whereNull('tdpc.delete_datetime')
                            ->where('tdpc.user_id', '=', $mUser['user_id'])
                    )
                    ->union(
                        DB::table('m_user as mu')
                        ->select(DB::raw(1))
                        ->join('m_user_role as mur', function ($join) {
                            return $join->on('mur.company_id', '=', 'mu.company_id')
                                ->on('mur.user_id', '=', 'mu.user_id')
                                ->where('mur.admin_role', '=', 0)
                                ->whereNull('mur.delete_datetime');
                        })
                        ->join($this->table, function ($join) {
                            return $join->on('mu.company_id', 't_document_internal.company_id');
                        })
                        ->whereNull('mu.delete_datetime')
                        ->where('mu.user_id', '=', $mUser['user_id'])
                    );
            })
            ->whereExists(function ($query) use ($condition) {
                return $query->from('t_document_workflow as tdw')
                    ->select(DB::raw(1))
                    ->join('m_user as mu', function ($join) {
                        return $join->on('mu.company_id', '=', 'tdw.company_id')
                            ->on('mu.user_id', '=', 'tdw.app_user_id')
                            ->where('mu.user_type_id', '=', 0);
                    })
                    ->join('t_document_internal', function ($jQuery) {
                        return $jQuery->on('tdw.company_id', '=', 't_document_internal.company_id')
                            ->on('tdw.document_id', '=', 't_document_internal.document_id')
                            ->on('tdw.category_id', '=', 't_document_internal.category_id');
                    })
                    ->whereNull('tdw.delete_datetime')
                    ->where('tdw.app_user_id', '=', $condition['app_user_id']);
            })
            ->whereExists(function ($query) use ($condition) {
                return $query->from('t_document_workflow as tdw')
                    ->select(DB::raw(1))
                    ->join('m_user as mu', function ($join) {
                        return $join->on('mu.company_id', '=', 'tdw.company_id')
                            ->on('mu.user_id', '=', 'tdw.app_user_id')
                            ->where('mu.user_type_id', '=', 1);
                    })
                    ->join('t_document_internal', function ($jQuery) {
                        return $jQuery->on('tdw.company_id', '=', 't_document_internal.company_id')
                            ->on('tdw.document_id', '=', 't_document_internal.document_id')
                            ->on('tdw.category_id', '=', 't_document_internal.category_id');
                    })
                    ->whereNull('tdw.delete_datetime')
                    ->where('tdw.app_user_id', '=', $condition['app_user_id_guest']);
            })
            ->whereExists(function ($query) use ($condition) {
                return $query->from('t_doc_permission_internal as tdpi')
                    ->select(DB::raw(1))
                    ->join('t_document_internal', function ($jQuery) {
                        return $jQuery->on('tdpi.company_id', '=', 't_document_internal.company_id')
                        ->on('tdpi.document_id', '=', 't_document_internal.document_id');
                    })
                    ->whereNull('tdpi.delete_datetime')
                    ->where('tdpi.user_id', '=', $condition['view_permission_user_id']);
            })
            ->where(function ($query) use ($condition) {
                return $query->where('m_company_counter_party.counter_party_name', 'like', '%'.$condition['counter_party_name'].'%')
                    ->orWhere('m_company_counter_party.counter_party_name_kana', 'like', '%'.$condition['counter_party_name'].'%');
            })
            ->when(empty($sort), function ($query) {
                return $query->orderBy('t_document_internal.document_id', 'ASC')
                    ->orderBy('t_document_internal.category_id', 'DESC');
            })
            ->when(!empty($sort), function ($query) use ($sort) {
                return $query->orderBy('t_document_internal.'.$sort['column_name'], $sort['sort_type']);
            })
            ->limit(1)
            ->count();
    }

    /**
     * ---------------------------------------------
     * 更新項目（社内書類）
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
