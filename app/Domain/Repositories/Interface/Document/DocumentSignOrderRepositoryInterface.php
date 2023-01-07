<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Interface\Document;



interface DocumentSignOrderRepositoryInterface
{
    /**
     * ログインユーザワークフロー取得
     *
     * @param integer $mUserId
     * @param integer $mUserCompanyId
     * @param integer $mUsertypeId
     * @return stdClass|null
     */
    public function getLoginUserWorkflow(int $mUserId, int $mUserCompanyId);


    /**
     * 署名する契約書類、次の署名者、起票者の取得
     *
     * @param integer $documentId
     * @param integer $doctypeId
     * @param integer $$loginUserWorkFlowSort
     * @return DocumentSaveOrder|null
     */
    public function getContractIsseuAndNextSignUserInfo(int $documentId, int $categoryId, int $loginUserWorkFlowSort);


    /**
     * 署名する取引書類、次の署名者、起票者の取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $loginUserWorkFlowSort
     * @return DocumentSaveOrder|null
     */
    public function getDealIsseuAndNextSignUserInfo(int $documentId, int $categoryId, int $loginUserWorkFlowSort);


    /**
     * 署名する社内書類、署名者全員、起票者の取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $mUserCompanyId
     * @return DocumentSaveOrder|null
     */
    public function getInternalSignUserListInfo(int $documentId, int $categoryId, int $mUserCompanyId);


    /**
     * 署名する登録書類、署名するホスト署名者、起票者の取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $mUserCompanyId
     * @return DocumentSaveOrder|null
     */
    public function getArchiveIsseuAndNextSignUserInfo(int $documentId, int $categoryId, int $mUserCompanyId);


    /**
     * トークン新規登録
     *
     * @param integer $counter_party_id
     * @param integer $category_id
     * @param integer $document_id
     * @param integer $user_id
     * @return bool
     */
    public function insertToken($token, $nextSignUserInfomation);
    
}
