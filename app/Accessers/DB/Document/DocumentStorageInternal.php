<?php
declare(strict_types=1);
namespace App\Accessers\DB\Document;

use App\Accessers\DB\FluentDatabase;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class DocumentStorageInternal extends FluentDatabase
{
    protected string $table = "t_doc_storage_internal";

    /**
     * ---------------------------------------------
     * 契約書類容量情報を保存する
     * ---------------------------------------------
     * @param Request $request
     * @return
     */
    
     public function save($request): bool
     {
        // return $request->upload_pdf['file_size'];
        $login_user = 1; //$request->m_user->user_id;
        $company_id = 1; //$request->m_user->company_id;
        $document_id = DB::table('t_document_internal')->select(["document_id"])
        ->orderByDesc('document_id')->limit(1)->first();
        $data = [
            'document_id' => $document_id->document_id, // 変更する
            'company_id' => $company_id, // ログインユーザ会社ID
            'template_id' => $request->template_id,
            'doc_type_id' => $request->doc_type_id,
            'file_name' => $request->upload_pdf['file_name'],
            'file_size' => $request->upload_pdf['file_size'],
            'file_path' => $request->upload_pdf['file_path'] ?? null,
            // 'file_hash' => $request->upload_pdf['file_hash'],
            // 'file_prot_flg' => $request->upload_pdf['file_prot_flg'],
            // 'file_prot_pw_flg' => $request->upload_pdf['file_prot_pw_flg'],
            // 'file_timestamp_flg' => $request->upload_pdf['file_timestamp_flg'],
            // 'file_sign' => $request->upload_pdf['file_sign'],
            // 'width' => $request->upload_file['width'],
            // 'height' => $request->upload_file['height'],
            // 'dpi' => $request->upload_file['dpi'],
            // 'color_depth' => $request->upload_pdf['color_depth'],
            // 'pdf_type' => $request->upload_pdf['pdf_type'],
            // 'pdf_version' => $request->upload_pdf['pdf_version'],
            // 'sign_position' => $request->upload_pdf['sign_position'],
            // 'total_pages' => $request->upload_pdf['total_pages'],
            'create_user' => $login_user,
            'create_datetime' => CarbonImmutable::now()->format('Y/m/d H:i:s'),
            'update_user' => $login_user,
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
            'document_id' => $request->document_id, // 変更する
            'company_id' => $company_id, // ログインユーザ会社ID
            'template_id' => $request->template_id,
            'doc_type_id' => $request->doc_type_id,
            'file_name' => $request->upload_pdf['file_name'],
            'file_size' => $request->upload_pdf['file_size'],
            'file_path' => $request->upload_pdf['file_path'] ?? null,
            // 'file_hash' => $request->upload_pdf['file_hash'],
            // 'file_prot_flg' => $request->upload_pdf['file_prot_flg'],
            // 'file_prot_pw_flg' => $request->upload_pdf['file_prot_pw_flg'],
            // 'file_timestamp_flg' => $request->upload_pdf['file_timestamp_flg'],
            // 'file_sign' => $request->upload_pdf['file_sign'],
            // 'width' => $request->upload_file['width'],
            // 'height' => $request->upload_file['height'],
            // 'dpi' => $request->upload_file['dpi'],
            // 'color_depth' => $request->upload_pdf['color_depth'],
            // 'pdf_type' => $request->upload_pdf['pdf_type'],
            // 'pdf_version' => $request->upload_pdf['pdf_version'],
            // 'sign_position' => $request->upload_pdf['sign_position'],
            // 'total_pages' => $request->upload_pdf['total_pages'],
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
            ->update($data);
    }
    
    /*
     * 更新項目（社内書類容量）
     * ---------------------------------------------
     * @param int $userId
     * @param int $companyId
     * @param int $documentId
     * @return bool
     */
    public function getDelete(int $userId, int $companyId, int $documentId)
    {
        return $this->builder()
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
