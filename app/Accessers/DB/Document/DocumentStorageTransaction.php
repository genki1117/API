<?php
declare(strict_types=1);
namespace App\Accessers\DB\Document;

use Carbon\CarbonImmutable;
use App\Accessers\DB\FluentDatabase;
use Illuminate\Support\Facades\DB;

class DocumentStorageTransaction extends FluentDatabase
{
    protected string $table = "t_doc_storage_transaction";

    /**
     * -------------------------
     * 取引書類容量情報新規登録
     * -------------------------
     * @param array $requestContent
     * @return boolean
     */
    
     public function insert(array $requestContent): bool
     {
        $LastdocumentId = DB::table('t_document_deal')->select(["document_id"])
        ->orderByDesc('document_id')->limit(1)->first();
            return $this->builder($this->table)->insert([
            'document_id'           => $LastdocumentId->document_id,
            'company_id'            => $requestContent['m_user_company_id'],
            'template_id'           => $requestContent['template_id'],
            'doc_type_id'           => $requestContent['doc_type_id'],
            'file_name'             => $requestContent['upload_pdf']['file_name'],
            'file_size'             => $requestContent['upload_pdf']['file_size'],
            'file_path'             => $requestContent['upload_pdf']['file_path'],
            'file_hash'             => $requestContent['upload_pdf']['file_hash'],
            'file_prot_flg'         => $requestContent['upload_pdf']['file_prot_flg'],
            'file_prot_pw_flg'      => $requestContent['upload_pdf']['file_prot_pw_flg'],
            'file_timestamp_flg'    => $requestContent['upload_pdf']['file_timestamp'],
            'file_sign'             => $requestContent['upload_pdf']['file_sign'],
            'width'                 => $requestContent['upload_pdf']['width'],
            'height'                => $requestContent['upload_pdf']['height'],
            'dpi'                   => $requestContent['upload_pdf']['dpi'],
            'color_depth'           => $requestContent['upload_pdf']['color_depth'],
            'pdf_type'              => $requestContent['upload_pdf']['pdf_type'],
            'pdf_version'           => $requestContent['upload_pdf']['pdf_version'],
            'sign_position'         => json_encode($requestContent['sign_position']),
            'total_pages'           => $requestContent['total_pages'],
            'create_user'           => $requestContent['m_user_id'],
            'create_datetime'       => $requestContent['create_datetime'],
            'update_user'           => $requestContent['m_user_id'],
            'update_datetime'       => $requestContent['update_datetime'],
            'delete_user'           => null,
            'delete_datetime'       => null
            ]);
     }

     /**
      * -------------------------
      * 契約書類容量更新
      * -------------------------
      *
      * @param array $requestContent
      * @return boolean
      */
      public function update(array $requestContent)
      {
          return $this->builder($this->table)
              ->where('company_id', $requestContent['company_id'])
              ->where('document_id', $requestContent['document_id'])
              ->update([
                  'document_id'           => $requestContent['document_id'],
                  'company_id'            => $requestContent['m_user_company_id'], 
                  'template_id'           => $requestContent['template_id'],
                  'doc_type_id'           => $requestContent['doc_type_id'],
                  'file_name'             => $requestContent['upload_pdf']['file_name'],
                  'file_size'             => $requestContent['upload_pdf']['file_size'],
                  'file_path'             => $requestContent['upload_pdf']['file_path'],
                  'file_hash'             => $requestContent['upload_pdf']['file_hash'],
                  'file_prot_flg'         => $requestContent['upload_pdf']['file_prot_flg'],
                  'file_prot_pw_flg'      => $requestContent['upload_pdf']['file_prot_pw_flg'],
                  'file_timestamp_flg'    => $requestContent['upload_pdf']['file_timestamp'],
                  'file_sign'             => $requestContent['upload_pdf']['file_sign'],
                  'width'                 => $requestContent['upload_pdf']['width'],
                  'height'                => $requestContent['upload_pdf']['height'],
                  'dpi'                   => $requestContent['upload_pdf']['dpi'],
                  'color_depth'           => $requestContent['upload_pdf']['color_depth'],
                  'pdf_type'              => $requestContent['upload_pdf']['pdf_type'],
                  'pdf_version'           => $requestContent['upload_pdf']['pdf_version'],
                  'sign_position'         => json_encode($requestContent['sign_position']),
                  'total_pages'           => $requestContent['total_pages'],
                  'update_user'           => $requestContent['m_user_id'],
                  'update_datetime'       => $requestContent['update_datetime'],
                  'delete_user'           => null,
                  'delete_datetime'       => null
              ]);
      }
    
    /*
     * 更新項目（取引書類容量）
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
