<?php
declare(strict_types=1);
namespace App\Accessers\DB\Document;

use Carbon\CarbonImmutable;
use App\Accessers\DB\FluentDatabase;
use Illuminate\Support\Facades\DB;

class DocumentPermissionTransaction extends FluentDatabase
{
    protected string $table = "t_doc_permission_transaction";

    /**
     * ---------------------------------------------
     * 契約書類閲覧権限情報を取得する
     * ---------------------------------------------
     * @param int $documentId
     * @param int $companyId
     * @return \stdClass|null
     */
    public function getList(int $documentId, int $companyId): ?\stdClass
    {
        return $this->builder($this->table)
            ->select([
                "t_doc_permission_contract.user_id",
                "m_user.family_name",
                "m_user.first_name",
                "m_user.email",
                "m_user.group_array"
            ])
            ->leftjoin("m_user", function($query) {
                return $query->on("m_user.user_id","t_doc_permission_contract.user_id")
                    ->where("m_user.company_id","t_doc_permission_contract.company_id")
                    ->where("m_user.delete_datetime", null);
            })
            ->where("t_doc_permission_contract.delete_datetime", null)
            ->where("t_doc_permission_contract.document_id",$documentId)
            ->where("t_doc_permission_contract.company_id", $companyId)
            ->orderBy("t_doc_permission_contract.user_id")
            ->first();
    }

    public function save($request):bool
    {
        $login_user = 1; //$request->m_user->user_id;
        $company_id = 1; //$request->m_user->company_id;
        $document_id = DB::table('t_document_deal')->select(["document_id"])
        ->orderByDesc('document_id')->limit(1)->first();
        $data = [
            "document_id" => $document_id->document_id,
            "company_id" => $company_id,
            "user_id" => $login_user,
            "create_user" => $login_user,
            "create_datetime" => CarbonImmutable::now()->format('Y/m/d H:i:s'),
            "update_user" => $login_user,
            "update_datetime" => CarbonImmutable::now()->format('Y/m/d H:i:s'),
            "delete_user" => null,
            "delete_datetime" => null
        ];
        return $this->builder($this->table)->insert($data);
    }

    public function update($request)
    {
        $login_user = 1; //$request->m_user->user_id;
        $company_id = 1; //$request->m_user->company_id;
        $data = [
            "document_id" => $request->document_id,
            "company_id" => $company_id,
            "user_id" => $login_user,
            "create_user" => $login_user,
            "create_datetime" => CarbonImmutable::now()->format('Y/m/d H:i:s'),
            "update_user" => $login_user,
            "update_datetime" => CarbonImmutable::now()->format('Y/m/d H:i:s'),
            "delete_user" => null,
            "delete_datetime" => null
        ];
        return $this->builder($this->table)
            ->where('document_id', $request->document_id)
            ->where('company_id', $company_id)
            ->update($data)
            ->leftjoin("m_user", function ($query) {
                return $query->on("m_user.user_id", "t_doc_permission_transaction.user_id")
                    ->where("m_user.company_id", "t_doc_permission_transaction.company_id")
                    ->where("m_user.delete_datetime", null);
            })
            ->where("t_doc_permission_transaction.delete_datetime", null)
            ->where("t_doc_permission_transaction.document_id", $documentId)
            ->where("t_doc_permission_transaction.company_id", $companyId)
            ->orderBy("t_doc_permission_transaction.user_id")
            ->first();
    }

    /**
     * ---------------------------------------------
     * 更新項目（取引書類閲覧権限）
     * ---------------------------------------------
     * @param int $userId
     * @param int $companyId
     * @param int $documentId
     * @return bool
     */
    public function getDelete(int $userId, int $companyId, int $documentId)
    {
        return $this->builder($this->table)
            ->whereNull("delete_datetime")
            ->where("company_id", "=", $companyId)
            ->where("document_id", "=", $documentId)
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
            ->first();
    }
}
