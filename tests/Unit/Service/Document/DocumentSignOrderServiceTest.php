<?php

namespace Tests\Unit\Service\Document;

use App\Domain\Entities\Document\DocumentSaveOrder;
use App\Domain\Entities\Document\Document;
use App\Domain\Repositories\Interface\Document\DocumentSignOrderRepositoryInterface;
use App\Domain\Services\Document\DocumentSignOrderService;
use Exception;
use App\Domain\Consts\UserTypeConst;
use App\Domain\Consts\DocumentConst;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class DocumentSignOrderServiceTest extends TestCase
{
    private MockInterface|LegacyMockInterface $documentRepositoryMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->documentRepositoryMock = \Mockery::mock(DocumentSignOrderRepositoryInterface::class);
        $this->userConst              = new UserTypeConst;
        $this->docConst               = new DocumentConst;
        $this->documentRepository = new DocumentSignOrderService(
            $this->userConst,
            $this->docConst,
            $this->documentRepositoryMock,                             
        );
    }

    /**
     * @test
     *  file_prot_pw_flgがtrueだった場合エラー
     * @return void
     * 
     * 
     */
    public function signOrderContract_file_prot_pw_flg_true_test()
    {
        $this->documentRepositoryMock->shouldReceive('getContractIsseuAndNextSignUserInfo->getSignDoc')
        ->once()
        ->andReturn(
            document_id:1,
            title: 'testtitle',
            file_prot_pw_flg: 1
        );
        $data = [
            'mUserId' => 1,
            'mUserCompanyId' => 1,
            'mUserTypeId' => 0,
            'documentId' => 1,
            'docTypeId' => 1,
            'categoryId' => 0,
        ];

        // int $mUserId, int $mUserCompanyId, int $mUserTypeId, int $documentId, int $docTypeId, int $categoryId, string $updateDatetime)
        $this->expectException(Exception::class);
        $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 0);
    }

    /**
     * 
     * 次の署名者がゲストの場合
     * @return void
     */
    public function signOrderContract_next_sgin_user_guest_test()
    {
        $dataDoc = (object)[
            'signDoc'=>[
                'document_id' => 1,
                'title' => '契約書類テストタイトル',
                'file_prot_pw_flg' => 1
            ],
        ];
        $dataSign =(object)[
            'user_id' => 3,
            'full_name' => '佐藤　次郎',
            'email' => 'testsato@test.jp',
            'user_type_id' => 0,
            'wf_sort' => 2,
            'category_id' => 0,
            'counter_party_id' => NULL,
            'counter_party_name' => NULL,
        ];
        $dataIssue = (object)[
            'full_name' => '大化　テスト',
            'family_name' => '大化',
            'first_name' => 'テスト',
            'wf_sort' => 0,
            'category_id' => 0,
        ];
        
        $this->documentRepositoryMock->shouldReceive('getLoginUserWorkflow')
        ->once()
        ->andReturn((object)['wf_sort' => 0]);

        $this->documentRepositoryMock->shouldReceive('getContractIsseuAndNextSignUserInfo->getSignDoc')
        ->once()
        ->andReturn($dataDoc);

        $this->documentRepositoryMock->shouldReceive('getContractIsseuAndNextSignUserInfo->getNextSignUser')
        ->once()
        ->andReturn($dataSign);

        $this->documentRepositoryMock->shouldReceive('getContractIsseuAndNextSignUserInfo->getIssueUser')
        ->once()
        ->andReturn($dataIssue);
        

        $this->documentRepositoryMock->shouldReceive('insertToken')
        ->once()
        ->andReturn(1);

        $this->documentRepositoryMock->shouldReceive('getDealIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn(1);

        $this->documentRepositoryMock->shouldReceive('getInternalSignUserListInfo')
        ->once()
        ->andReturn(1);

        $this->documentRepositoryMock->shouldReceive('getArchiveIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn(1);


        


       $result = $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 0);

        //var_dump($result);

    }

    private function getObject()
    {
        return new DocumentSignOrderService( $this->userConst, $this->docConst, $this->documentRepositoryMock, );
    }

    


}
