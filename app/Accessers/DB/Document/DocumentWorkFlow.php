<?php
declare(strict_types=1);
namespace App\Accessers\DB\Document;

use App\Accessers\DB\FluentDatabase;
use Illuminate\Support\Facades\DB;

class DocumentWorkFlow extends FluentDatabase
{
    protected string $table = "t_document_workflow";

    /**
     * ---------------------------------------------
     * 書類ワークフロー情報を取得する
     * ---------------------------------------------
     * @param int $documentId
     * @param int $categoryId
     * @param int $companyId
     * @return array
     */
    public function getList(int $documentId, int $categoryId, int $companyId): array
    {
        return $this->builder($this->table)
            ->select([
                "t_document_workflow.app_user_id",
                "t_document_workflow.wf_sort",
                "m_user.family_name",
                "m_user.first_name",
                "m_user.email",
                "m_user.group_array"
            ])
            ->leftjoin("m_user", function ($query) {
                return $query->on("m_user.company_id", "t_document_workflow.company_id")
                    ->where("m_user.delete_datetime", null);
            })
            ->where("t_document_workflow.delete_datetime", null)
            ->where("t_document_workflow.document_id", $documentId)
            ->where("t_document_workflow.category_id", $categoryId)
            ->where("t_document_workflow.company_id", $companyId)
            ->orderBy("t_document_workflow.wf_sort", "DESC")
            ->get()
            ->all();
    }
    /**
     * -------------------------
     * 契約書類ワークフロー登録
     * -------------------------
     *
     * @param integer $companyId
     * @param integer $categoryId
     * @param integer $appUserId
     * @param integer $wfSort
     * @param integer $userId
     * @param string $createDate
     * @return boolean
     */
    public function insertContract(int $companyId, int $categoryId, int $appUserId, int $wfSort, int $userId, string $createDate)
    {
        $LastdocumentId = DB::table('t_document_contract')->select(["document_id"])
        ->orderByDesc('document_id')->limit(1)->first();
        return $this->builder($this->table)->insert([
            'document_id'           => $LastdocumentId->document_id,
            'company_id'            => $companyId,
            'category_id'           => $categoryId,
            'app_user_id'           => $appUserId,
            'wf_sort'             => $wfSort,
            'create_user'           => $userId,
            'create_datetime'       => $createDate,
            'update_user'           => $userId,
            'update_datetime'       => $createDate
            ]);
    }

    /**
     * -------------------------
     * 取引書類ワークフロー登録
     * -------------------------
     *
     * @param integer $companyId
     * @param integer $categoryId
     * @param integer $appUserId
     * @param integer $wfSort
     * @param integer $userId
     * @param string $createDate
     * @return boolean
     */
    public function insertDeal(int $companyId, int $categoryId, int $appUserId, int $wfSort, int $userId, string $createDate)
    {
        $LastdocumentId = DB::table('t_document_deal')->select(["document_id"])
        ->orderByDesc('document_id')->limit(1)->first();
        return $this->builder($this->table)->insert([
            'document_id'           => $LastdocumentId->document_id,
            'company_id'            => $companyId,
            'category_id'           => $categoryId,
            'app_user_id'           => $appUserId,
            'wf_sort'             => $wfSort,
            'create_user'           => $userId,
            'create_datetime'       => $createDate,
            'update_user'           => $userId,
            'update_datetime'       => $createDate
            ]);
    }
}
