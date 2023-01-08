<?php
declare(strict_types=1);
namespace App\Accessers\DB\Document;

use Carbon\CarbonImmutable;
use App\Accessers\DB\FluentDatabase;
use Illuminate\Support\Facades\DB;
use Exception;

class DocumentPermissionArchive extends FluentDatabase
{
    protected string $table = "t_doc_permission_archive";

    /**
     * ---------------------------------------------
     * 登録書類閲覧権限情報を取得する
     * ---------------------------------------------
     * @param int $documentId
     * @param int $companyId
     * @return \stdClass|null
     */
    public function getList(int $documentId, int $companyId): ?\stdClass
    {
        return $this->builder($this->table)
            ->select([
                "t_doc_permission_archive.user_id",
                "m_user.family_name",
                "m_user.first_name",
                "m_user.email",
                "m_user.group_array"
            ])
            ->leftjoin("m_user", function ($query) {
                return $query->on("m_user.user_id", "t_doc_permission_archive.user_id")
                    ->where("m_user.company_id", "t_doc_permission_archive.company_id")
                    ->where("m_user.delete_datetime", null);
            })
            ->where("t_doc_permission_archive.delete_datetime", null)
            ->where("t_doc_permission_archive.document_id", $documentId)
            ->where("t_doc_permission_archive.company_id", $companyId)
            ->orderBy("t_doc_permission_archive.user_id")
            ->first();
    }

    /**
     * -------------------------
     * 登録書類閲覧権限登録
     * -------------------------
     *
     * @param array $requestContent
     * @return boolean
     */
    public function insert(array $requestContent): bool
    {
        $LastdocumentId = DB::table('t_document_archive')->select(["document_id"])
        ->orderByDesc('document_id')->limit(1)->first();
        $data = [
            "document_id"     => $LastdocumentId->document_id,
            "company_id"      => $requestContent['company_id'],
            "user_id"         => $requestContent['m_user_id'],
            "create_user"     => $requestContent['m_user_id'],
            "create_datetime" => CarbonImmutable::now(),
            "update_user"     => $requestContent['m_user_id'],
            "update_datetime" => CarbonImmutable::now(),
            "delete_user"     => null,
            "delete_datetime" => null
        ];
        return $this->builder($this->table)->insert($data);
    }

    /**
     * -------------------------
     * 登録書類閲覧権限更新
     * -------------------------
     *
     * @param array $requestContent
     * @return boolean|Exception
     */
    public function update(array $requestContent): ?bool
    {
        $deleteResult = DB::table('t_doc_permission_archive')
        ->where('document_id', $requestContent['document_id'])
        ->where('company_id', $requestContent['company_id'])
        ->delete();
        
        if ($deleteResult) {
            $data = [
                "document_id"     => $requestContent['document_id'],
                "company_id"      => $requestContent['company_id'],
                "user_id"         => $requestContent['m_user_id'],
                "create_user"     => $requestContent['m_user_id'],
                "create_datetime" => CarbonImmutable::now(),
                "update_user"     => $requestContent['m_user_id'],
                "update_datetime" => CarbonImmutable::now(),
                "delete_user"     => null,
                "delete_datetime" => null
            ];
            return $this->builder($this->table)->insert($data);
        } else {
            throw new Exception('登録書類テーブルおよび登録書類閲覧権限および登録書類容量を更新出来ません。');
            exit;
        }
    }
    /**
     * ---------------------------------------------
     * 更新項目（登録書類閲覧権限）
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

    /**
     * 登録書類権限の変更前、変更後の情報を取得
     *
     * @param array $requestContent
     * @return \stdClass|null
     */
    public function getBeforeOrAfterUpdateData(array $requestContent): ?\stdClass
    {
        return $this->builder()
            ->select([
                'update_user',
                'update_datetime'
            ])
            ->where('company_id', '=', $requestContent['company_id'])
            ->where('document_id', '=', $requestContent['document_id'])
            ->first();
    }
}
