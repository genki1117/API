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
     * @return void
     */
    public function getLoginUserWorkflow(int $mUserId, int $mUserCompanyId);


    /**
     * 次の署名者の取得（契約書類）
     *
     * @param integer $documentId
     * @param integer $doctypeId
     * @param integer $$loginUserWorkFlowSort
     * @return void
     */
    public function getContractIsseuAndNextSignUserInfo(int $documentId, int $categoryId, int $loginUserWorkFlowSort);


    /**
     * 次の署名者の取得（取引書類）
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $loginUserWorkFlowSort
     * @return void
     */
    public function getDealIsseuAndNextSignUserInfo(int $documentId, int $categoryId, int $loginUserWorkFlowSort);


    /**
     * トークン新規登録
     *
     * @param integer $counter_party_id
     * @param integer $category_id
     * @param integer $document_id
     * @param integer $user_id
     * @return void
     */
    public function insertToken($token, $nextSignUserInfomation);
    
}
