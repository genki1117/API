<?php
declare(strict_types=1);
namespace App\Accessers\DB\Document;

use Illuminate\Http\Request;
use Carbon\CarbonImmutable;
use App\Accessers\DB\FluentDatabase;
use Illuminate\Support\Facades\DB;

class DocumentPermissionContract extends FluentDatabase
{
    protected string $table = "t_doc_permission_contract";

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

    public function insert(array $requestContent)
    {
        $LastdocumentId = DB::table('t_document_contract')->select(["document_id"])
        ->orderByDesc('document_id')->limit(1)->first();
        $data = [
            "document_id"     => $LastdocumentId->document_id,
            "company_id"      => $requestContent['company_id'],
            "user_id"         => $requestContent['m_user_id'],
            "create_user"     => $requestContent['m_user_id'],
            "create_datetime" => $requestContent['create_datetime'],
            "update_user"     => $requestContent['m_user_id'],
            "update_datetime" => $requestContent['update_datetime'],
            "delete_user"     => null,
            "delete_datetime" => null
        ];
        return $this->builder($this->table)->insert($data);
    }

    public function update($requestContent)
    {
        return $this->builder($this->table)
            ->where('document_id', $requestContent['document_id'])
            ->where('company_id', $requestContent['company_id'])
            ->update([
                "document_id"     => $requestContent['company_id'],
                "company_id"      => $requestContent['company_id'],
                "user_id"         => $requestContent['m_user_id'],
                "update_user"     => $requestContent['m_user_id'],
                "update_datetime" => $requestContent['update_datetime'],
                "delete_user"     => null,
                "delete_datetime" => null
            ]);
    }
}
