<?php

namespace Tests\Unit\Service\Document;

use App\Domain\Entities\Document\DocumentSignOrder;
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
        $this->userConst              = new UserTypeConst;
        $this->docConst               = new DocumentConst;
        $this->documentRepositoryMock = \Mockery::mock(DocumentSignOrderRepositoryInterface::class);
    }

    /**
     * @test
     *  file_prot_pw_flgがtrueだった場合エラー
     * @return void
     */
    public function signOrderContract_file_prot_pw_flg_true_test()
    {
        $dataDoc = (object)[
            'document_id' => 1,
            'title' => '契約書類テストタイトル',
            'file_prot_pw_flg' => 0
    ];
        $this->documentRepositoryMock->shouldReceive('getContractIsseuAndNextSignUserInfo->getSignDoc')
        ->once()
        ->andReturn($dataDoc);
        
        $this->expectException(Exception::class);
        $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 0);
    }

    /**
     * @test
     * 次の署名者がゲストの場合
     * @return void
     */
    public function signOrderContract_next_sgin_user_guest_test()
    {
        $dataFlow = (object)[
            'wf_sort' => 1,
            'full_name' => '山田　タロウ',
        ];

        $dataDoc = (object)[
                'document_id' => 1,
                'title' => '契約書類テストタイトル',
                'file_prot_pw_flg' => 1
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

        $data = [
            'signDoc' => 
                (object) [
                    'document_id' => 1,
                    'title' => '契約書類テストタイトル',
                    'file_prot_pw_flg' => 1,
                ],
            'nextSignUser' => 
                (object) [
                    'user_id' => 3,
                    'full_name' => '佐藤　次郎',
                    'email' => 'testsato@test.jp',
                    'user_type_id' => 0,
                    'wf_sort' => 2,
                    'category_id' => 0,
                    'counter_party_id' => NULL,
                    'counter_party_name' => NULL,
                ],
            'issueUser' => 
                (object) [
                    'full_name' => '大化　テスト',
                    'family_name' => '大化',
                    'first_name' => 'テスト',
                    'wf_sort' => 0,
                    'category_id' => 0,
                ],
        ];


        // $this->documentRepositoryMock->shouldReceive('getContractIsseuAndNextSignUserInfo->getSignDoc')
        // ->once()
        // ->andReturn($dataDoc);


        // $this->documentRepositoryMock->shouldReceive('getContractIsseuAndNextSignUserInfo')
        // ->once()
        // ->andReturn($dataSign);

        // $this->documentRepositoryMock->shouldReceive('getContractIsseuAndNextSignUserInfo->getIssueUser')
        // ->once()
        // ->andReturn($dataIssue);


        $this->documentRepositoryMock->shouldReceive('getContractIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($data);


        $result = $this->getObject()->signOrder(mUserId: 2, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 0);


        // $this->documentRepositoryMock->shouldReceive('getContractIsseuAndNextSignUserInfo')
        // ->once()
        // ->andReturn($data);
        

        // $this->documentRepositoryMock->shouldReceive('insertToken')
        // ->once()
        // ->andReturn(1);

        // $this->documentRepositoryMock->shouldReceive('getDealIsseuAndNextSignUserInfo')
        // ->once()
        // ->andReturn(1);

        // $this->documentRepositoryMock->shouldReceive('getInternalSignUserListInfo')
        // ->once()
        // ->andReturn(1);

        // $this->documentRepositoryMock->shouldReceive('getArchiveIsseuAndNextSignUserInfo')
        // ->once()
        // ->andReturn(1);

        

        //var_dump($result);

    }

    private function getObject()
    {
        return new DocumentSignOrderService( $this->userConst, $this->docConst, $this->documentRepositoryMock, );
    }

    


}
