<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Document;

use App\Accessers\DB\TempToken;
use App\Accessers\DB\Master\MUser;
use App\Accessers\DB\Master\MUserRoleTest;
use App\Accessers\DB\Document\DocumentContract;
use App\Accessers\DB\Document\DocumentDeal;
use App\Accessers\DB\Document\DocumentInternal;
use App\Accessers\DB\Document\DocumentArchive;
use App\Accessers\DB\Document\DocumentWorkFlow;
use App\Domain\Entities\Document\DocumentSaveOrder;
use App\Domain\Repositories\Interface\Document\DocumentSignOrderRepositoryInterface;

class DocumentSaveOrderRepository implements DocumentSignOrderRepositoryInterface
{

    public function __construct(
        TempToken $tempToken,
        MUser $mUser,
        DocumentContract $documentContract,
        DocumentDeal $documentDeal,
        DocumentInternal $documentInternal,
        DocumentArchive $documentArchive,
        DocumentWorkFlow $documentWorkFlow,
        )
    {
        $this->token            = $tempToken;
        $this->mUser            = $mUser;
        $this->documentContract = $documentContract;
        $this->documentDeal     = $documentDeal;
        $this->documentInternal = $documentInternal;
        $this->documentArchive  = $documentArchive;
        $this->documentWorkFlow = $documentWorkFlow;
    }

    /**
     * ログインユーザのワークフローを取得
     *
     * @param integer $mUserId
     * @param integer $mUserCompanyId
     * @return void
     */
    public function getLoginUserWorkflow (int $mUserId, int $mUserCompanyId)
    {
        
        $loginUserWorkflowSort = $this->mUser->getLoginUserWorkflow($mUserId, $mUserCompanyId);
        return $loginUserWorkflowSort->wf_sort  ;
        $test = new DocumentSaveOrder($loginUserWorkflowSort);
        return var_dump($test);
       
    }

    /**
     * ログインユーザの次の署名者の情報を取得
     *
     * @param integer $documentId
     * @param integer $categoryId
     * @param integer $loginUserWorkFlowSort
     * @return void
     */
    public function getContractNextSignUserInfomation(int $documentId, int $categoryId, int $loginUserWorkFlowSort)
    {
        return $this->documentWorkFlow->getContractNextSignUserInfomation($documentId, $categoryId, $loginUserWorkFlowSort);
    }

    /**
     * トークン新規登録
     *
     * @param integer $counter_party_id
     * @param integer $category_id
     * @param integer $document_id
     * @param integer $user_id
     * @return void
     */
    public function insertToken($token, $dataContent)
    {
        return $this->token->insertToken($token, $dataContent);
    }
}
