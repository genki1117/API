<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Interface\Document;



interface DocumentGetDocumentRepositoryInterface
{
    /**
     * ログインユーザ取得
     *
     * @param integer $mUserId
     * @param integer $mUserCompanyId
     * @param integer $mUsertypeId
     * @return void
     */
    public function getLoginUser(int $mUserId, int $mUserCompanyId);

    /**
     * 次の署名者の取得（ワークフロー）
     *
     * @param integer $documentId
     * @param integer $doctypeId
     * @param integer $categoryId
     * @return void
     */
    public function getContractSignUser(int $documentId, int $doctypeId, int $categoryId);
}
