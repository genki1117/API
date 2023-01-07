<?php
declare(strict_types=1);
namespace App\Accessers\DB\Master;

use App\Accessers\DB\FluentDatabase;

class MUser extends FluentDatabase
{
    protected string $table = "m_user";

    /**
     * @param string $compnay_id
     * @param string $user_id
     * @return \stdClass|null
     */
    public function getUser(string $compnay_id, string $user_id): ?\stdClass
    {
        return $this->builder("m_user as mu")
            ->select([
                "mu.*",
            ])
            ->where("mu.company_id", '=', $compnay_id)
            ->where("mu.user_id", '=', $user_id)
            ->whereNull("mu.delete_datetime")
            ->first();
    }

    /**
     * ------------------------------------
     * ログインユーザーのワークフローを取得
     * ------------------------------------
     *
     * @param integer $mUserId
     * @param integer $mUserCompanyId
     * @return \stdClass|null
     */
    public function getLoginUserWorkflow (int $mUserId, int $mUserCompanyId): ?\stdClass
    {
        return $this->builder($this->table)
                    ->join('t_document_workflow', function ($join) {
                        $join->on('user_id', '=', 't_document_workflow.app_user_id')
                             ->whereNull('t_document_workflow.delete_datetime');
                    })
                    ->where('m_user.company_id', '=', $mUserCompanyId)
                    ->where('m_user.user_id', '=', $mUserId)
                    ->select([
                        't_document_workflow.wf_sort',
                        'm_user.full_name'
                    ])
                    ->first();
    }
}
