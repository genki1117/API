<?php
declare(strict_types=1);
namespace App\Domain\Services\Document;

use App\Domain\Consts\DocumentConst;
use App\Domain\Repositories\Interface\Document\DocumentGetDocumentRepositoryInterface;


class DocumentSignOrderService
{
    /** @var DocumentConst */
    private DocumentConst $docConst;

    /** @var  DocumentGetDocumentRepositoryInterface*/
    private DocumentGetDocumentRepositoryInterface $documentGetDocumentRepositoryInterface;

    public function __construct(
    DocumentConst $docConst,
    DocumentGetDocumentRepositoryInterface $documentGetDocumentRepositoryInterface
    )
    {
        $this->docConst = $docConst;
        $this->documentGetDocumentRepositoryInterface = $documentGetDocumentRepositoryInterface;
    }
    

    public function signOrder(int $mUserId, int $mUserCompanyId, int $mUserTypeId, int $documentId, int $docTypeId, int $categoryId, string $updateDatetime)
    {
        $loginUser = $this->documentGetDocumentRepositoryInterface->getLoginUser($mUserId, $mUserCompanyId, $mUsertypeId);
        switch($categoryId) {
            case $this->docConst::DOCUMENT_CONTRACT:
                // return 'contract';
                // 送信者取得
                return $this->documentGetDocumentRepositoryInterface->getContractSignUser($documentId, $docTypeId, $categoryId);

                // file_prot_pw_flgがtrueの場合、メール送信しない旨のエラーを返却し処理を終了する。
                // 社内書類以外の場合はSQLにLIMITを設けて特定の人だけに送信する。

                // メールアドレスからハッシュコードを作成し、登録する。
                // データ更新シートを参照

                // ユーザ種別により、メール送信を呼び出す。
            break;
            
            case $this->docConst::DOCUMENT_DEAL:
                return 'deal';
            break;

            case $this->docConst::DOCUMENT_INTERNAL:
                return 'internal';
            break;

            case $this->docConst::DOCUMENT_ARCHIVE:
                return 'archive';
            break;

        }
    }

}
