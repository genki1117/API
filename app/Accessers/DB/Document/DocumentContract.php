<?php
declare(strict_types=1);
namespace App\Accessers\DB\Document;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use App\Accessers\DB\FluentDatabase;

class DocumentContract extends FluentDatabase
{
    protected string $table = 't_document_contract';

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
                't_document_contract.document_id',
                't_document_contract.company_id',
                't_document_contract.category_id',
                't_document_contract.doc_type_id',
                't_document_contract.status_id',
                't_document_contract.cont_start_date',
                't_document_contract.cont_end_date',
                't_document_contract.conc_date',
                't_document_contract.effective_date',
                't_document_contract.doc_no',
                't_document_contract.ref_doc_no',
                't_document_contract.title',
                't_document_contract.amount',
                't_document_contract.currency_id',
                't_document_contract.counter_party_id',
                't_document_contract.remarks',
                't_document_contract.doc_info',
                't_document_contract.sign_level',
                't_document_contract.product_name',
                DB::raw('UNIX_TIMESTAMP(t_document_contract.update_datetime) as update_datetime'),
                't_doc_storage_contract.file_path',
                't_doc_storage_contract.total_pages',
                'm_company_counter_party.counter_party_name'
            ])
            ->join('t_doc_storage_contract', function ($query) {
                return $query->on('t_doc_storage_contract.document_id', 't_document_contract.document_id')
                    ->where('t_doc_storage_contract.company_id', 't_document_contract.company_id')
                    ->where('t_doc_storage_contract.delete_datetime', null);
            })
            ->leftjoin('m_company_counter_party', function ($query) {
                return $query->on('t_document_contract.company_id', 'm_company_counter_party.company_id')
                    ->where('t_document_contract.counter_party_id', 'm_company_counter_party.counter_party_id')
                    ->where('m_company_counter_party.effe_start_date', '<=', DB::raw('CURRENT_DATE'))
                    ->where('m_company_counter_party.effe_end_date', '>=', DB::raw('CURRENT_DATE'))
                    ->where('m_company_counter_party.delete_datetime', null);
            })
            ->where('t_document_contract.delete_datetime', null)
            ->where('t_document_contract.document_id', $documentId)
            ->where('t_document_contract.company_id', $companyId)
            ->whereExists(function ($query) use ($userId) {
                return $query->from('t_document_workflow as tdw')
                    ->select(DB::raw(1))
                    ->join($this->table, function ($join) {
                        return $join->on('tdw.company_id', 't_document_contract.company_id')
                            ->on('tdw.document_id', 't_document_contract.document_id')
                            ->on('tdw.category_id', 't_document_contract.category_id');
                    })
                    ->where('tdw.delete_datetime', null)
                    ->where('tdw.app_user_id', $userId)
                    ->where(function ($jQuery) {
                        return $jQuery->where('tdw.wf_sort', 0)
                            ->orWhere('tdw.app_status', 6);
                    })
                    ->union(
                        DB::table('t_doc_permission_contract as tdpc')
                            ->select(DB::raw(1))
                            ->join($this->table, function ($join) {
                                return $join->on('tdpc.company_id', 't_document_contract.company_id')
                                    ->on('tdpc.document_id', 't_document_contract.document_id');
                            })
                            ->where('tdpc.delete_datetime', null)
                            ->where('tdpc.user_id', $userId)
                    )
                    ->union(
                        DB::table('m_user as mu')
                        ->select(DB::raw(1))
                        ->join('m_user_role as mur', function ($join) {
                            return $join->on('mur.company_id', 'mu.company_id')
                                ->where('mur.user_id', 'mu.user_id')
                                ->where('mur.delete_datetime', null);
                        })
                        ->join($this->table, function ($join) {
                            return $join->on('mu.company_id', 't_document_contract.company_id');
                        })
                        ->where('mu.delete_datetime', null)
                        ->where('mu.user_id', $userId)
                    );
            })
            ->first();
    }

    /**
     * 契約書類一覧情報を取得
     * @param array $mUser
     * @param array $condition
     * @param array $sort
     * @param array $page
     * @return array|null
     */
    public function getDocumentList(array $mUser, array $condition, array $sort, array $page): ?array
    {
        return $this->builder()
            ->select([
                't_document_contract.document_id',
                't_document_contract.category_id',
                't_document_contract.conc_date as transaction_date',
                't_document_contract.doc_type_id',
                't_document_contract.title',
                't_document_contract.amount',
                't_document_contract.currency_id',
                't_document_contract.status_id',
                DB::raw('UNIX_TIMESTAMP(t_document_contract.update_datetime) as update_datetime'),
                't_document_workflow.app_status',
                'm_user.full_name as create_user',
                DB::raw('CONCAT(m_company_counter_party.company_id, " ", m_company_counter_party.counter_party_name) as counter_party_name')
            ])
            ->join('m_user', function ($query) {
                return $query->on('m_user.user_id', '=', 't_document_contract.create_user')
                ->on('m_user.company_id', '=', 't_document_contract.company_id')
                ->whereNull('m_user.delete_datetime');
            })
            ->leftJoin('t_document_workflow', function ($query) use ($mUser) {
                return $query->on('t_document_contract.document_id', '=', 't_document_workflow.document_id')
                ->on('t_document_contract.category_id', '=', 't_document_workflow.category_id')
                ->where('t_document_workflow.company_id', '=', $mUser['company_id'])
                ->where('t_document_workflow.app_user_id', '=', $mUser['user_id'])
                ->whereNull('t_document_workflow.delete_datetime');
            })
            ->join('m_company_counter_party', function ($query) {
                return $query->on('m_company_counter_party.company_id', '=', 't_document_contract.company_id')
                ->on('m_company_counter_party.counter_party_id', '=', 't_document_contract.counter_party_id')
                ->where('m_company_counter_party.effe_start_date', '<=', DB::raw('CURRENT_DATE'))
                ->where('m_company_counter_party.effe_end_date', '>=', DB::raw('CURRENT_DATE'))
                ->whereNull('m_company_counter_party.delete_datetime');
            })
            ->whereNull('t_document_contract.delete_datetime')
            ->where('t_document_contract.company_id', '=', $mUser['company_id'])
            ->when(!empty($condition['search_input']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.title', 'like', '%'.$condition['search_input'].'%');
            })
            ->when(!empty($condition['status_id']), function ($jQuery) use ($condition) {
                return $jQuery->whereIn('t_document_contract.status_id', [$condition['status_id']]);
            })
            ->when(!empty($condition['category_id']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.category_id', '=', $condition['category_id']);
            })
            ->when(!empty($condition['register_type_id']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.doc_type_id', '=', $condition['register_type_id']);
            })
            ->when(!empty($condition['title']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.title', 'like', '%'.$condition['title'].'%');
            })
            ->when(!empty(['amount']['from']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.amount', '<=', $condition['amount']['from']);
            })
            ->when(!empty($condition['amount']['to']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.amount', '>=', $condition['amount']['to']);
            })
            ->when(!empty($condition['currency_id']), function ($jQuery) use ($condition) {
                return $jQuery->whereIn('t_document_contract.currency_id', [$condition['currency_id']]);
            })
            ->when(!empty($condition['product_name']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.product_name', 'like', '%'.$condition['product_name'].'%');
            })
            ->when(!empty($condition['document_id']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.document_id', 'like', '%'.$condition['document_id'].'%');
            })
            ->when(!empty($condition['doc_no']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.doc_no', 'like', '%'.$condition['doc_no'].'%');
            })
            ->when(!empty($condition['ref_doc_no']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.ref_doc_no', 'like', '%'.$condition['ref_doc_no'].'%');
            })
            ->when(!empty($condition['doc_info']['title']), function ($jQuery) use ($condition) {
                return $jQuery->whereRaw('JSON_CONTAINS(t_document_contract.doc_info->"$.title", \'["'.$condition['doc_info']['title'].'"]\')');
            })
            ->when(!empty($condition['doc_info']['content']), function ($jQuery) use ($condition) {
                return $jQuery->whereRaw('JSON_CONTAINS(t_document_contract.doc_info->"$.content", \'["'.$condition['doc_info']['content'].'"]\')');
            })
            ->when(!empty($condition['create_datetime']['from']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.create_datetime', '>=', $condition['create_datetime']['from']);
            })
            ->when(!empty($condition['create_datetime']['to']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.create_datetime', '<=', $condition['create_datetime']['to']);
            })
            ->when(!empty($condition['contract_start_date']['from']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.cont_start_date', '>=', $condition['contract_start_date']['from']);
            })
            ->when(!empty($condition['contract_start_date']['to']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.cont_start_date', '<=', $condition['contract_start_date']['to']);
            })
            ->when(!empty($condition['contract_end_date']['from']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.cont_end_date', '>=', $condition['contract_end_date']['from']);
            })
            ->when(!empty($condition['contract_end_date']['to']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.cont_end_date', '<=', $condition['contract_end_date']['to']);
            })
            ->when(!empty($condition['conc_date']['from']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.conc_date', '>=', $condition['conc_date']['from']);
            })
            ->when(!empty($condition['conc_date']['to']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.conc_date', '<=', $condition['conc_date']['to']);
            })
            ->when(!empty($condition['effective_date']['from']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.effective_date', '>=', $condition['effective_date']['from']);
            })
            ->when(!empty($condition['effective_date']['to']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.effective_date', '<=', $condition['effective_date']['to']);
            })
            ->when(!empty($condition['remarks']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.remarks', 'like', '%'.$condition['remarks'].'%');
            })
            // ログインユーザがワークフローに関連するか閲覧者か
            // 管理者権限をもっているユーザーのレコードの取得
            ->whereExists(function ($query) use ($mUser) {
                return $query->from('t_document_workflow as tdw')
                    ->select(DB::raw(1))
                    ->join($this->table, function ($join) {
                        return $join->on('tdw.company_id', 't_document_contract.company_id')
                            ->on('tdw.document_id', 't_document_contract.document_id')
                            ->on('tdw.category_id', 't_document_contract.category_id');
                    })
                    ->whereNull('tdw.delete_datetime')
                    ->where('tdw.app_user_id', '=', $mUser['user_id'])
                    ->where(function ($jQuery) {
                        return $jQuery->where('tdw.wf_sort', '=', 0)    // 起票者かどうか判定
                            ->orWhere('tdw.app_status', '=', 6);        // 自身が未署名かどうかの判定
                    })
                    ->union(
                        DB::table('t_doc_permission_contract as tdpc')
                            ->select(DB::raw(1))
                            ->join($this->table, function ($join) {
                                return $join->on('tdpc.company_id', 't_document_contract.company_id')
                                    ->on('tdpc.document_id', 't_document_contract.document_id');
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
                            return $join->on('mu.company_id', 't_document_contract.company_id');
                        })
                        ->whereNull('mu.delete_datetime')
                        ->where('mu.user_id', '=', $mUser['user_id'])
                    );
            })
            // 署名者の絞り込み
            ->whereExists(function ($query) use ($condition) {
                return $query->from('t_document_workflow as tdw')
                    ->select(DB::raw(1))
                    ->join('m_user as mu', function ($join) {
                        return $join->on('mu.company_id', '=', 'tdw.company_id')
                            ->on('mu.user_id', '=', 'tdw.app_user_id')
                            ->where('mu.user_type_id', '=', 0);
                    })
                    ->join('t_document_contract', function ($jQuery) {
                        return $jQuery->on('tdw.company_id', '=', 't_document_contract.company_id')
                            ->on('tdw.document_id', '=', 't_document_contract.document_id')
                            ->on('tdw.category_id', '=', 't_document_contract.category_id');
                    })
                    ->whereNull('tdw.delete_datetime')
                    ->when(!empty($condition['app_user_id']), function ($jQuery) use ($condition) {
                        return $jQuery->where('tdw.app_user_id', '=', $condition['app_user_id']);
                    });
            })
            // ゲスト署名者の絞り込み
            ->whereExists(function ($query) use ($condition) {
                return $query->from('t_document_workflow as tdw')
                    ->select(DB::raw(1))
                    ->join('m_user as mu', function ($join) {
                        return $join->on('mu.company_id', '=', 'tdw.company_id')
                            ->on('mu.user_id', '=', 'tdw.app_user_id')
                            ->where('mu.user_type_id', '=', 0);
                    })
                    ->join('t_document_contract', function ($jQuery) {
                        return $jQuery->on('tdw.company_id', '=', 't_document_contract.company_id')
                            ->on('tdw.document_id', '=', 't_document_contract.document_id')
                            ->on('tdw.category_id', '=', 't_document_contract.category_id');
                    })
                    ->whereNull('tdw.delete_datetime')
                    ->when(!empty($condition['app_user_id_guest']), function ($jQuery) use ($condition) {
                        return $jQuery->where('tdw.app_user_id', '=', $condition['app_user_id_guest']);
                    });
            })
            // 閲覧者の絞り込み
            ->whereExists(function ($query) use ($condition) {
                return $query->from('t_doc_permission_contract as tdpc')
                    ->select(DB::raw(1))
                    ->join('t_document_contract', function ($jQuery) {
                        return $jQuery->on('tdpc.company_id', '=', 't_document_contract.company_id')
                        ->on('tdpc.document_id', '=', 't_document_contract.document_id');
                    })
                    ->whereNull('tdpc.delete_datetime')
                    ->when(!empty($condition['view_permission_user_id']), function ($jQuery) use ($condition) {
                        return $jQuery->where('tdpc.user_id', '=', $condition['view_permission_user_id']);
                    });
            })
            ->when(!empty($condition['counter_party_name']), function ($query) use ($condition) {
                return $query->where('m_company_counter_party.counter_party_name', 'like', '%'.$condition['counter_party_name'].'%')
                    ->orWhere('m_company_counter_party.counter_party_name_kana', 'like', '%'.$condition['counter_party_name'].'%');
            })
            ->when(empty($sort), function ($query) {
                return $query->orderBy('t_document_contract.document_id', 'ASC')
                    ->orderBy('t_document_contract.category_id', 'DESC');
            })
            ->when(!empty($sort), function ($query) use ($sort) {
                return $query->orderBy('t_document_contract.'.$sort['column_name'], $sort['sort_type']);
            })
            ->limit($page['disp_count'])
            ->offset($page['disp_page'])
            ->get()
            ->all();
    }

    /**
     * 契約書類の件数を条件より取得
     * @param array $mUser
     * @param array $condition
     * @param array $sort
     * @return int|null
     */
    public function getDocumentListCount(array $mUser, array $condition, array $sort): ?int
    {
        return $this->builder()
            ->join('m_user', function ($query) {
                return $query->on('m_user.user_id', '=', 't_document_contract.create_user')
                ->on('m_user.company_id', '=', 't_document_contract.company_id')
                ->whereNull('m_user.delete_datetime');
            })
            ->leftJoin('t_document_workflow', function ($query) use ($mUser) {
                return $query->on('t_document_contract.document_id', '=', 't_document_workflow.document_id')
                ->on('t_document_contract.category_id', '=', 't_document_workflow.category_id')
                ->where('t_document_workflow.company_id', '=', $mUser['company_id'])
                ->where('t_document_workflow.app_user_id', '=', $mUser['user_id'])
                ->whereNull('t_document_workflow.delete_datetime');
            })
            ->join('m_company_counter_party', function ($query) {
                return $query->on('m_company_counter_party.company_id', '=', 't_document_contract.company_id')
                ->on('m_company_counter_party.counter_party_id', '=', 't_document_contract.counter_party_id')
                ->where('m_company_counter_party.effe_start_date', '<=', DB::raw('CURRENT_DATE'))
                ->where('m_company_counter_party.effe_end_date', '>=', DB::raw('CURRENT_DATE'))
                ->whereNull('m_company_counter_party.delete_datetime');
            })
            ->whereNull('t_document_contract.delete_datetime')
            ->where('t_document_contract.company_id', '=', $mUser['company_id'])
            ->when(!empty($condition['search_input']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.title', 'like', '%'.$condition['search_input'].'%');
            })
            ->when(!empty($condition['status_id']), function ($jQuery) use ($condition) {
                return $jQuery->whereIn('t_document_contract.status_id', [$condition['status_id']]);
            })
            ->when(!empty($condition['category_id']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.category_id', '=', $condition['category_id']);
            })
            ->when(!empty($condition['register_type_id']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.doc_type_id', '=', $condition['register_type_id']);
            })
            ->when(!empty($condition['title']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.title', 'like', '%'.$condition['title'].'%');
            })
            ->when(!empty(['amount']['from']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.amount', '<=', $condition['amount']['from']);
            })
            ->when(!empty($condition['amount']['to']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.amount', '>=', $condition['amount']['to']);
            })
            ->when(!empty($condition['currency_id']), function ($jQuery) use ($condition) {
                return $jQuery->whereIn('t_document_contract.currency_id', [$condition['currency_id']]);
            })
            ->when(!empty($condition['product_name']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.product_name', 'like', '%'.$condition['product_name'].'%');
            })
            ->when(!empty($condition['document_id']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.document_id', 'like', '%'.$condition['document_id'].'%');
            })
            ->when(!empty($condition['doc_no']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.doc_no', 'like', '%'.$condition['doc_no'].'%');
            })
            ->when(!empty($condition['ref_doc_no']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.ref_doc_no', 'like', '%'.$condition['ref_doc_no'].'%');
            })
            ->when(!empty($condition['doc_info']['title']), function ($jQuery) use ($condition) {
                return $jQuery->whereRaw('JSON_CONTAINS(t_document_contract.doc_info->"$.title", \'["'.$condition['doc_info']['title'].'"]\')');
            })
            ->when(!empty($condition['doc_info']['content']), function ($jQuery) use ($condition) {
                return $jQuery->whereRaw('JSON_CONTAINS(t_document_contract.doc_info->"$.content", \'["'.$condition['doc_info']['content'].'"]\')');
            })
            ->when(!empty($condition['create_datetime']['from']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.create_datetime', '>=', $condition['create_datetime']['from']);
            })
            ->when(!empty($condition['create_datetime']['to']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.create_datetime', '<=', $condition['create_datetime']['to']);
            })
            ->when(!empty($condition['contract_start_date']['from']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.cont_start_date', '>=', $condition['contract_start_date']['from']);
            })
            ->when(!empty($condition['contract_start_date']['to']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.cont_start_date', '<=', $condition['contract_start_date']['to']);
            })
            ->when(!empty($condition['contract_end_date']['from']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.cont_end_date', '>=', $condition['contract_end_date']['from']);
            })
            ->when(!empty($condition['contract_end_date']['to']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.cont_end_date', '<=', $condition['contract_end_date']['to']);
            })
            ->when(!empty($condition['conc_date']['from']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.conc_date', '>=', $condition['conc_date']['from']);
            })
            ->when(!empty($condition['conc_date']['to']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.conc_date', '<=', $condition['conc_date']['to']);
            })
            ->when(!empty($condition['effective_date']['from']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.effective_date', '>=', $condition['effective_date']['from']);
            })
            ->when(!empty($condition['effective_date']['to']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.effective_date', '<=', $condition['effective_date']['to']);
            })
            ->when(!empty($condition['remarks']), function ($jQuery) use ($condition) {
                return $jQuery->where('t_document_contract.remarks', 'like', '%'.$condition['remarks'].'%');
            })
            // ログインユーザがワークフローに関連するか閲覧者か
            // 管理者権限をもっているユーザーのレコードの取得
            ->whereExists(function ($query) use ($mUser) {
                return $query->from('t_document_workflow as tdw')
                    ->select(DB::raw(1))
                    ->join($this->table, function ($join) {
                        return $join->on('tdw.company_id', 't_document_contract.company_id')
                            ->on('tdw.document_id', 't_document_contract.document_id')
                            ->on('tdw.category_id', 't_document_contract.category_id');
                    })
                    ->whereNull('tdw.delete_datetime')
                    ->where('tdw.app_user_id', '=', $mUser['user_id'])
                    ->where(function ($jQuery) {
                        return $jQuery->where('tdw.wf_sort', '=', 0)    // 起票者かどうか判定
                            ->orWhere('tdw.app_status', '=', 6);        // 自身が未署名かどうかの判定
                    })
                    ->union(
                        DB::table('t_doc_permission_contract as tdpc')
                            ->select(DB::raw(1))
                            ->join($this->table, function ($join) {
                                return $join->on('tdpc.company_id', 't_document_contract.company_id')
                                    ->on('tdpc.document_id', 't_document_contract.document_id');
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
                            return $join->on('mu.company_id', 't_document_contract.company_id');
                        })
                        ->whereNull('mu.delete_datetime')
                        ->where('mu.user_id', '=', $mUser['user_id'])
                    );
            })
            // 署名者の絞り込み
            ->whereExists(function ($query) use ($condition) {
                return $query->from('t_document_workflow as tdw')
                    ->select(DB::raw(1))
                    ->join('m_user as mu', function ($join) {
                        return $join->on('mu.company_id', '=', 'tdw.company_id')
                            ->on('mu.user_id', '=', 'tdw.app_user_id')
                            ->where('mu.user_type_id', '=', 0);
                    })
                    ->join('t_document_contract', function ($jQuery) {
                        return $jQuery->on('tdw.company_id', '=', 't_document_contract.company_id')
                            ->on('tdw.document_id', '=', 't_document_contract.document_id')
                            ->on('tdw.category_id', '=', 't_document_contract.category_id');
                    })
                    ->whereNull('tdw.delete_datetime')
                    ->when(!empty($condition['app_user_id']), function ($jQuery) use ($condition) {
                        return $jQuery->where('tdw.app_user_id', '=', $condition['app_user_id']);
                    });
            })
            // ゲスト署名者の絞り込み
            ->whereExists(function ($query) use ($condition) {
                return $query->from('t_document_workflow as tdw')
                    ->select(DB::raw(1))
                    ->join('m_user as mu', function ($join) {
                        return $join->on('mu.company_id', '=', 'tdw.company_id')
                            ->on('mu.user_id', '=', 'tdw.app_user_id')
                            ->where('mu.user_type_id', '=', 0);
                    })
                    ->join('t_document_contract', function ($jQuery) {
                        return $jQuery->on('tdw.company_id', '=', 't_document_contract.company_id')
                            ->on('tdw.document_id', '=', 't_document_contract.document_id')
                            ->on('tdw.category_id', '=', 't_document_contract.category_id');
                    })
                    ->whereNull('tdw.delete_datetime')
                    ->when(!empty($condition['app_user_id_guest']), function ($jQuery) use ($condition) {
                        return $jQuery->where('tdw.app_user_id', '=', $condition['app_user_id_guest']);
                    });
            })
            // 閲覧者の絞り込み
            ->whereExists(function ($query) use ($condition) {
                return $query->from('t_doc_permission_contract as tdpc')
                    ->select(DB::raw(1))
                    ->join('t_document_contract', function ($jQuery) {
                        return $jQuery->on('tdpc.company_id', '=', 't_document_contract.company_id')
                        ->on('tdpc.document_id', '=', 't_document_contract.document_id');
                    })
                    ->whereNull('tdpc.delete_datetime')
                    ->when(!empty($condition['view_permission_user_id']), function ($jQuery) use ($condition) {
                        return $jQuery->where('tdpc.user_id', '=', $condition['view_permission_user_id']);
                    });
            })
            ->when(!empty($condition['counter_party_name']), function ($query) use ($condition) {
                return $query->where('m_company_counter_party.counter_party_name', 'like', '%'.$condition['counter_party_name'].'%')
                    ->orWhere('m_company_counter_party.counter_party_name_kana', 'like', '%'.$condition['counter_party_name'].'%');
            })
            ->when(empty($sort), function ($query) {
                return $query->orderBy('t_document_contract.document_id', 'ASC')
                    ->orderBy('t_document_contract.category_id', 'DESC');
            })
            ->when(!empty($sort), function ($query) use ($sort) {
                return $query->orderBy('t_document_contract.'.$sort['column_name'], $sort['sort_type']);
            })
            ->count();
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
            ->whereNull('delete_datetime')
            ->where('company_id', '=', $companyId)
            ->where('document_id', '=', $documentId)
            ->where('update_datetime', '=', date('Y-m-d H:i:s', $updateDatetime))
            ->where('status_id', '=', 0)
            ->update([
                'delete_user' => $userId,
                'delete_datetime' => CarbonImmutable::now()
            ]);
    }

    /**
     * 契約書類マスタの削除前、削除後のデータを取得
     * @param int $companyId
     * @param int $documentId
     * @return \stdClass|null
     */
    public function getBeforeOrAfterData(int $companyId, int $documentId): ?\stdClass
    {
        return $this->builder()
            ->select([
                'delete_user',
                'delete_datetime'
            ])
            ->where('company_id', '=', $companyId)
            ->where('document_id', '=', $documentId)
            ->where('status_id', '=', 0)
            ->first();
    }

    public function expiryUpdate($data)
    {
        return $this->builder($this->table)
                    ->where('document_id', $data->document_id)
                    ->where('category_id', $data->category_id)
                    ->where('company_id', $data->company_id)
                    ->update([
                        'status_id' => 13,
                        'update_user' => 99,
                        'update_datetime' => CarbonImmutable::now()
                    ]);
    }
}
