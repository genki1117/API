<?php
declare(strict_types=1);
namespace App\Accessers\DB\Document;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use App\Accessers\DB\FluentDatabase;

class DocumentInternal extends FluentDatabase
{
    protected string $table = 't_document_internal';

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
                't_document_internal.document_id',
                't_document_internal.company_id',
                't_document_internal.category_id',
                't_document_internal.doc_type_id',
                't_document_internal.status_id',
                't_document_internal.doc_create_date',
                't_document_internal.sign_finish_date',
                't_document_internal.doc_no',
                't_document_internal.ref_doc_no',
                't_document_internal.product_name',
                't_document_internal.title',
                't_document_internal.amount',
                't_document_internal.currency_id',
                't_document_internal.counter_party_id',
                't_document_internal.content',
                't_document_internal.remarks',
                't_document_internal.doc_info',
                't_document_internal.sign_level',
                DB::raw('UNIX_TIMESTAMP(t_document_internal.update_datetime) as update_datetime'),
                't_doc_storage_internal.file_path',
                't_doc_storage_internal.total_pages',
                'm_company_counter_party.counter_party_name'
            ])
            ->join('t_doc_storage_internal', function ($query) {
                return $query->on('t_doc_storage_internal.document_id', 't_document_internal.document_id')
                    ->where('t_doc_storage_internal.company_id', 't_document_internal.company_id')
                    ->where('t_doc_storage_internal.delete_datetime', null);
            })
            ->leftjoin('m_company_counter_party', function ($query) {
                return $query->on('t_document_internal.company_id', 'm_company_counter_party.company_id')
                    ->where('t_document_internal.counter_party_id', 'm_company_counter_party.counter_party_id')
                    ->where('m_company_counter_party.effe_start_date', '<=', DB::raw('CURRENT_DATE'))
                    ->where('m_company_counter_party.effe_end_date', '>=', DB::raw('CURRENT_DATE'))
                    ->where('m_company_counter_party.delete_datetime', null);
            })
            ->where('t_document_internal.delete_datetime', null)
            ->where('t_document_internal.document_id', $documentId)
            ->where('t_document_internal.company_id', $companyId)
            ->whereExists(function ($query) use ($userId) {
                return $query->from('t_document_workflow as tdw')
                    ->select(DB::raw(1))
                    ->where('tdw.company_id', '=', 't_document_internal.company_id')
                    ->where('tdw.document_id', '=', 't_document_internal.document_id')
                    ->where('tdw.category_id', '=', 't_document_internal.category_id')
                    ->where('tdw.delete_datetime', null)
                    ->where('tdw.app_user_id', $userId)
                    ->where(function ($join) {
                        return $join->where('tdw.wf_sort', 0)
                            ->orWhere('tdw.app_status', 6);
                    })
                    ->union(
                        DB::table('t_doc_permission_internal as tdpi')
                            ->select(DB::raw(1))
                            ->where('tdpi.company_id', '=', 't_document_internal.company_id')
                            ->where('tdpi.document_id', '=', 't_document_internal.document_id')
                            ->where('tdpi.delete_datetime', null)
                            ->where('tdpi.user_id', $userId)
                    )
                    ->union(
                        DB::table('m_user as mu')
                            ->select(DB::raw(1))
                            ->join('m_user_role as mur', function ($join) {
                                return $join->on('mur.company_id', '=', 'mu.company_id')
                                    ->where('mur.user_id', '=', 'mu.user_id')
                                    ->where('mur.delete_datetime', null);
                            })
                            ->where('mu.company_id', '=', 't_document_internal.company_id')
                            ->where('mu.delete_datetime', null)
                            ->where('mu.user_id', $userId)
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
    /**
     * 社内書類一覧情報を取得
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
                't_document_internal.document_id',
                't_document_internal.category_id',
                't_document_internal.doc_create_date as transaction_date',
                't_document_internal.doc_type_id',
                't_document_internal.title',
                't_document_internal.amount',
                't_document_internal.currency_id',
                't_document_internal.status_id',
                DB::raw('UNIX_TIMESTAMP(t_document_internal.update_datetime) as update_datetime'),
                't_document_workflow.app_status',
                'm_user.full_name as create_user',
                DB::raw('CONCAT(m_company_counter_party.company_id, " ", m_company_counter_party.counter_party_name) as counter_party_name')
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
            ->when(!empty($condition['search_input']), function ($query) use ($condition) {
                return $query->where('t_document_internal.title', 'like', '%'.$condition['search_input'].'%');
            })
            ->when(!empty($condition['status_id']), function ($query) use ($condition) {
                return $query->whereIn('t_document_internal.status_id', [$condition['status_id']]);
            })
            ->when(!empty($condition['category_id']), function ($query) use ($condition) {
                return $query->where('t_document_internal.category_id', '=', $condition['category_id']);
            })
            ->when(!empty($condition['register_type_id']), function ($query) use ($condition) {
                return $query->where('t_document_internal.doc_type_id', '=', $condition['register_type_id']);
            })
            ->when(!empty($condition['title']), function ($query) use ($condition) {
                return $query->where('t_document_internal.title', 'like', '%'.$condition['title'].'%');
            })
            ->when(!empty($condition['amount']['from']), function ($query) use ($condition) {
                return $query->where('t_document_internal.amount', '<=', $condition['amount']['from']);
            })
            ->when(!empty($condition['amount']['to']), function ($query) use ($condition) {
                return $query->where('t_document_internal.amount', '>=', $condition['amount']['to']);
            })
            ->when(!empty($condition['currency_id']), function ($query) use ($condition) {
                return $query->whereIn('t_document_internal.currency_id', [$condition['currency_id']]);
            })
            ->when(!empty($condition['product_name']), function ($query) use ($condition) {
                return $query->where('t_document_internal.product_name', 'like', '%'.$condition['product_name'].'%');
            })
            ->when(!empty($condition['document_id']), function ($query) use ($condition) {
                return $query->where('t_document_internal.document_id', 'like', '%'.$condition['document_id'].'%');
            })
            ->when(!empty($condition['doc_no']), function ($query) use ($condition) {
                return $query->where('t_document_internal.doc_no', 'like', '%'.$condition['doc_no'].'%');
            })
            ->when(!empty($condition['ref_doc_no']), function ($query) use ($condition) {
                return $query->where('t_document_internal.ref_doc_no', 'like', '%'.$condition['ref_doc_no'].'%');
            })
            ->when(!empty($condition['content']), function ($query) use ($condition) {
                return $query->where('t_document_internal.content', 'like', '%'.$condition['content'].'%');
            })
            ->when(!empty($condition['doc_info']['title']), function ($query) use ($condition) {
                return $query->whereRaw('JSON_CONTAINS(t_document_internal.doc_info->"$.title", \'["'.$condition['doc_info']['title'].'"]\')');
            })
            ->when(!empty($condition['doc_info']['content']), function ($query) use ($condition) {
                return $query->whereRaw('JSON_CONTAINS(t_document_internal.doc_info->"$.content", \'["'.$condition['doc_info']['content'].'"]\')');
            })
            ->when(!empty($condition['create_datetime']['from']), function ($query) use ($condition) {
                return $query->where('t_document_internal.create_datetime', '>=', $condition['create_datetime']['from']);
            })
            ->when(!empty($condition['create_datetime']['to']), function ($query) use ($condition) {
                return $query->where('t_document_internal.create_datetime', '<=', $condition['create_datetime']['to']);
            })
            ->when(!empty($condition['doc_create_date']['from']), function ($query) use ($condition) {
                return $query->where('t_document_internal.doc_create_date', '>=', $condition['doc_create_date']['from']);
            })
            ->when(!empty($condition['doc_create_date']['to']), function ($query) use ($condition) {
                return $query->where('t_document_internal.doc_create_date', '<=', $condition['doc_create_date']['to']);
            })
            ->when(!empty($condition['sign_finish_date']['from']), function ($query) use ($condition) {
                return $query->where('t_document_internal.sign_finish_date', '>=', $condition['sign_finish_date']['from']);
            })
            ->when(!empty($condition['sign_finish_date']['to']), function ($query) use ($condition) {
                return $query->where('t_document_internal.sign_finish_date', '<=', $condition['sign_finish_date']['to']);
            })
            // ログインユーザがワークフローに関連するか閲覧者か
            // 管理者権限をもっているユーザーのレコードの取得
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
                        return $jQuery->where('tdw.wf_sort', '=', 0)    // 起票者かどうか判定
                            ->orWhere('tdw.app_status', '=', 6);        // 自身が未署名かどうかの判定
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
            // 署名者の絞り込み
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
                            ->where('mu.user_type_id', '=', 1);
                    })
                    ->join('t_document_internal', function ($jQuery) {
                        return $jQuery->on('tdw.company_id', '=', 't_document_internal.company_id')
                            ->on('tdw.document_id', '=', 't_document_internal.document_id')
                            ->on('tdw.category_id', '=', 't_document_internal.category_id');
                    })
                    ->whereNull('tdw.delete_datetime')
                    ->when(!empty($condition['app_user_id']), function ($jQuery) use ($condition) {
                        return $jQuery->where('tdw.app_user_id', '=', $condition['app_user_id_guest']);
                    });
            })
            ->whereExists(function ($query) use ($condition) {
                return $query->from('t_doc_permission_internal as tdpi')
                    ->select(DB::raw(1))
                    ->join('t_document_internal', function ($jQuery) {
                        return $jQuery->on('tdpi.company_id', '=', 't_document_internal.company_id')
                        ->on('tdpi.document_id', '=', 't_document_internal.document_id');
                    })
                    ->whereNull('tdpi.delete_datetime')
                    ->when(!empty($condition['view_permission_user_id']), function ($jQuery) use ($condition) {
                        return $jQuery->where('tdpi.user_id', '=', $condition['view_permission_user_id']);
                    });
            })
            ->when(!empty($condition['counter_party_name']), function ($query) use ($condition) {
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
            ->get()
            ->all();
    }

    /**
     * 社内書類の件数を条件より取得
     * @param array $mUser
     * @param array $condition
     * @param array $sort
     * @return int|null
     */
    public function getDocumentListCount(array $mUser, array $condition, array $sort): ?int
    {
        return $this->builder()
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
            ->when(!empty($condition['search_input']), function ($query) use ($condition) {
                return $query->where('t_document_internal.title', 'like', '%'.$condition['search_input'].'%');
            })
            ->when(!empty($condition['status_id']), function ($query) use ($condition) {
                return $query->whereIn('t_document_internal.status_id', [$condition['status_id']]);
            })
            ->when(!empty($condition['category_id']), function ($query) use ($condition) {
                return $query->where('t_document_internal.category_id', '=', $condition['category_id']);
            })
            ->when(!empty($condition['register_type_id']), function ($query) use ($condition) {
                return $query->where('t_document_internal.doc_type_id', '=', $condition['register_type_id']);
            })
            ->when(!empty($condition['title']), function ($query) use ($condition) {
                return $query->where('t_document_internal.title', 'like', '%'.$condition['title'].'%');
            })
            ->when(!empty($condition['amount']['from']), function ($query) use ($condition) {
                return $query->where('t_document_internal.amount', '<=', $condition['amount']['from']);
            })
            ->when(!empty($condition['amount']['to']), function ($query) use ($condition) {
                return $query->where('t_document_internal.amount', '>=', $condition['amount']['to']);
            })
            ->when(!empty($condition['currency_id']), function ($query) use ($condition) {
                return $query->whereIn('t_document_internal.currency_id', [$condition['currency_id']]);
            })
            ->when(!empty($condition['product_name']), function ($query) use ($condition) {
                return $query->where('t_document_internal.product_name', 'like', '%'.$condition['product_name'].'%');
            })
            ->when(!empty($condition['document_id']), function ($query) use ($condition) {
                return $query->where('t_document_internal.document_id', 'like', '%'.$condition['document_id'].'%');
            })
            ->when(!empty($condition['doc_no']), function ($query) use ($condition) {
                return $query->where('t_document_internal.doc_no', 'like', '%'.$condition['doc_no'].'%');
            })
            ->when(!empty($condition['ref_doc_no']), function ($query) use ($condition) {
                return $query->where('t_document_internal.ref_doc_no', 'like', '%'.$condition['ref_doc_no'].'%');
            })
            ->when(!empty($condition['content']), function ($query) use ($condition) {
                return $query->where('t_document_internal.content', 'like', '%'.$condition['content'].'%');
            })
            ->when(!empty($condition['doc_info']['title']), function ($query) use ($condition) {
                return $query->whereRaw('JSON_CONTAINS(t_document_internal.doc_info->"$.title", \'["'.$condition['doc_info']['title'].'"]\')');
            })
            ->when(!empty($condition['doc_info']['content']), function ($query) use ($condition) {
                return $query->whereRaw('JSON_CONTAINS(t_document_internal.doc_info->"$.content", \'["'.$condition['doc_info']['content'].'"]\')');
            })
            ->when(!empty($condition['create_datetime']['from']), function ($query) use ($condition) {
                return $query->where('t_document_internal.create_datetime', '>=', $condition['create_datetime']['from']);
            })
            ->when(!empty($condition['create_datetime']['to']), function ($query) use ($condition) {
                return $query->where('t_document_internal.create_datetime', '<=', $condition['create_datetime']['to']);
            })
            ->when(!empty($condition['doc_create_date']['from']), function ($query) use ($condition) {
                return $query->where('t_document_internal.doc_create_date', '>=', $condition['doc_create_date']['from']);
            })
            ->when(!empty($condition['doc_create_date']['to']), function ($query) use ($condition) {
                return $query->where('t_document_internal.doc_create_date', '<=', $condition['doc_create_date']['to']);
            })
            ->when(!empty($condition['sign_finish_date']['from']), function ($query) use ($condition) {
                return $query->where('t_document_internal.sign_finish_date', '>=', $condition['sign_finish_date']['from']);
            })
            ->when(!empty($condition['sign_finish_date']['to']), function ($query) use ($condition) {
                return $query->where('t_document_internal.sign_finish_date', '<=', $condition['sign_finish_date']['to']);
            })
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
                    ->when(!empty($condition['app_user_id']), function ($jQuery) use ($condition) {
                        return $jQuery->where('tdw.app_user_id', '=', $condition['app_user_id']);
                    });
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
                    ->when(!empty($condition['app_user_id']), function ($jQuery) use ($condition) {
                        return $jQuery->where('tdw.app_user_id', '=', $condition['app_user_id_guest']);
                    });
            })
            ->whereExists(function ($query) use ($condition) {
                return $query->from('t_doc_permission_internal as tdpi')
                    ->select(DB::raw(1))
                    ->join('t_document_internal', function ($jQuery) {
                        return $jQuery->on('tdpi.company_id', '=', 't_document_internal.company_id')
                        ->on('tdpi.document_id', '=', 't_document_internal.document_id');
                    })
                    ->whereNull('tdpi.delete_datetime')
                    ->when(!empty($condition['view_permission_user_id']), function ($jQuery) use ($condition) {
                        return $jQuery->where('tdpi.user_id', '=', $condition['view_permission_user_id']);
                    });
            })
            ->when(!empty($condition['counter_party_name']), function ($query) use ($condition) {
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
     * 社内書類マスタの削除前、削除後のデータを取得
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
}
