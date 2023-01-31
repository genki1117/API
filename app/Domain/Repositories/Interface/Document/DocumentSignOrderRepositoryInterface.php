<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Interface\Document;

use App\Domain\Entities\Document\DocumentSignOrder;

interface DocumentSignOrderRepositoryInterface
{

    /**
     * 署名する契約書類、次の署名者、起票者の取得
     *
     * @param integer $documentId
     * @param integer $doctypeId
     * @param integer $$loginUserWorkFlowSort
     * @return DocumentSignOrder|null
     */
    public function getContractIssueAndNextSignUserInfo(int $documentId, int $categoryId, int $mUserId): DocumentSignOrder;


    /**
     * 署名する取引書類、次の署名者、起票者の取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $loginUserWorkFlowSort
     * @return DocumentSignOrder|null
     */
    public function getDealIssueAndNextSignUserInfo(int $documentId, int $categoryId, int $mUserId): DocumentSignOrder;


    /**
     * 署名する社内書類、署名者全員、起票者の取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $mUserCompanyId
     * @return DocumentSignOrder
     */
    public function getInternalSignUserListInfo(int $documentId, int $categoryId, int $mUserCompanyId): DocumentSignOrder;


    /**
     * 署名する登録書類、署名するホスト署名者、起票者の取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $mUserCompanyId
     * @return DocumentSignOrder
     */
    public function getArchiveIssueAndNextSignUserInfo(int $documentId, int $categoryId, int $mUserCompanyId): DocumentSignOrder;


    /**
     * トークン登録
     *
     * @param string $token
     * @param array $dataContent
     * @return void
     */
    public function insertToken(string $token, array $dataContent): bool;
}
