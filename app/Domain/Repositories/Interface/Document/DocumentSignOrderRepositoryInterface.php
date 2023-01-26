<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Interface\Document;

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
    public function getContractIsseuAndNextSignUserInfo(int $documentId, int $categoryId, int $mUserId);


    /**
     * 署名する取引書類、次の署名者、起票者の取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $loginUserWorkFlowSort
     * @return DocumentSignOrder|null
     */
    public function getDealIsseuAndNextSignUserInfo(int $documentId, int $categoryId, int $mUserId);


    /**
     * 署名する社内書類、署名者全員、起票者の取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $mUserCompanyId
     * @return DocumentSignOrder|null
     */
    public function getInternalSignUserListInfo(int $documentId, int $categoryId, int $mUserCompanyId);


    /**
     * 署名する登録書類、署名するホスト署名者、起票者の取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $mUserCompanyId
     * @return DocumentSignOrder|null
     */
    public function getArchiveIsseuAndNextSignUserInfo(int $documentId, int $categoryId, int $mUserCompanyId);


    /**
     * トークン登録
     *
     * @param string $token
     * @param array $dataContent
     * @return void
     */
    public function insertToken(string $token, array $dataContent);
}
