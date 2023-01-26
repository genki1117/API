<?php

namespace Tests\Unit\Service\Document;

use ReflectionClass;
use App\Domain\Consts\QueueConst;
use App\Accessers\Queue\QueueUtility;
use App\Domain\Entities\Document\DocumentSignOrder;
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
    /** @var MockInterface|LegacyMockInterface $documentRepositoryMock */
    private MockInterface|LegacyMockInterface $documentRepositoryMock;

    /** QueueUtility $queueUtilityMock */
    private QueueUtility $queueUtilityMock;

    /** @var UserTypeConst $userConst */
    private UserTypeConst $userConst;

    /** @var DocumentConst $docConst */
    private DocumentConst $docConst;
    

    public function setUp(): void
    {
        parent::setUp();
        $this->queueUtilityMock       = \Mockery::mock(QueueUtility::class);
        $this->userConst              = new UserTypeConst;
        $this->docConst               = new DocumentConst;
        $this->documentRepositoryMock = \Mockery::mock(DocumentSignOrderRepositoryInterface::class);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }

    /**
     * @test
     * 
     * 契約書類
     *  file_prot_pw_flgがtrueだった場合エラー
     * @return void
     */
    public function signOrderContractFileProtPwFlgTrueTest()
    {
        $this->documentRepositoryMock->shouldReceive('getContractIsseuAndNextSignUserInfo->getSignDoc')
        ->once()
        ->andReturn($this->getTestDataDocFlg_0());
        
        $this->expectException(Exception::class);
        $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 0, updateDatetime: '2022-10-10', systemUrl: '/test/test');
    }

    /**
     * @test
     * 取引書類
     *  file_prot_pw_flgがtrueだった場合エラー
     * @return void
     */
    public function signOrderDealFileProtPwFlgTrueTest()
    {
        $this->documentRepositoryMock->shouldReceive('getDealIsseuAndNextSignUserInfo->getSignDoc')
        ->once()
        ->andReturn($this->getTestDataDocFlg_0());
        
        $this->expectException(Exception::class);
        $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 1, updateDatetime: '2022-10-10', systemUrl: '/test/test');
    }

    /**
     * @test
     * 登録書類
     *  file_prot_pw_flgがtrueだった場合エラー
     * @return void
     */
    public function signOrderArchiveFileProtPwFlgTrueTest()
    {
        $this->documentRepositoryMock->shouldReceive('getArchiveIsseuAndNextSignUserInfo->getSignDoc')
        ->once()
        ->andReturn($this->getTestDataDocFlg_0());
        
        $this->expectException(Exception::class);
        $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 3, updateDatetime: '2022-10-10', systemUrl: '/test/test');
    }

    /**
     * @test
     * 社内書類
     *  file_prot_pw_flgがtrueだった場合エラー
     * @return void
     */
    public function signOrderInternalFileProtPwFlgTrueTest()
    {
        $this->documentRepositoryMock->shouldReceive('getInternalSignUserListInfo->getSignDoc')
        ->once()
        ->andReturn($this->getTestDataDocFlg_0());
        
        $this->expectException(Exception::class);
        $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 2, updateDatetime: '2022-10-10', systemUrl: '/test/test');
    }

    /**
     * @test
     * 契約書類
     * 書類取得不可
     * @return void
     */
    public function signOrderContractNextSignUserGuestTest1()
    {
        $docEntiry = new DocumentSignOrder(null, $this->getTestDataSignUser(), $this->getTestDataIssueUser());

        $this->documentRepositoryMock->shouldReceive('getContractIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->expectException(Exception::class);
        $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 0, updateDatetime: '2022-10-10', systemUrl: '/test/test');
    }

    /**
     * @test
     * 取引書類
     * 書類取得不可
     * @return void
     */
    public function signOrderDealNextSignUserGuestTest1()
    {
        $docEntiry = new DocumentSignOrder(null, $this->getTestDataSignUser(), $this->getTestDataIssueUser());

        $this->documentRepositoryMock->shouldReceive('getDealIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->expectException(Exception::class);
        $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 1, updateDatetime: '2022-10-10', systemUrl: '/test/test');
    }

    /**
     * @test
     * 社内書類
     * 書類取得不可
     * @return void
     */
    public function signOrderInternalNextSignUserGuestTest1()
    {
        $docEntiry = new DocumentSignOrder(null, $this->getTestDataSignUser(), $this->getTestDataIssueUser());

        $this->documentRepositoryMock->shouldReceive('getInternalSignUserListInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->expectException(Exception::class);
        $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 2, updateDatetime: '2022-10-10', systemUrl: '/test/test');
    }

    /**
     * @test
     * 登録書類
     * 書類取得不可
     * @return void
     */
    public function signOrderArchiveNextSignUserGuestTest1()
    {
        $docEntiry = new DocumentSignOrder(null, $this->getTestDataSignUser(), $this->getTestDataIssueUser());

        $this->documentRepositoryMock->shouldReceive('getArchiveIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->expectException(Exception::class);
        $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 3, updateDatetime: '2022-10-10', systemUrl: '/test/test');
    }

    /**
     * @test
     * 契約書類
     * 次の署名者がゲスト
     * @return void
     */
    public function signOrderContractNextSignUserGuestTest()
    {
        $docEntiry = new DocumentSignOrder($this->getTestDataDoc(), $this->getTestDataSignUser(), $this->getTestDataIssueUser());

        $this->documentRepositoryMock->shouldReceive('getContractIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->queueUtilityMock->shouldReceive('createMessage')
        ->once()
        ->andReturn(0);

        $result = $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 0, updateDatetime: '2022-10-10', systemUrl: '/test/test');
        
        $this->assertTrue($result);  
    }

    /**
     * @test
     * 契約書類
     * 次の署名者がゲスト
     * キュー登録失敗
     * @return void
     */
    public function signOrderContractNextSignUserGuestQueueFaildTest()
    {
        $docEntiry = new DocumentSignOrder($this->getTestDataDoc(), $this->getTestDataSignUser(), $this->getTestDataIssueUser());

        $this->documentRepositoryMock->shouldReceive('getContractIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->queueUtilityMock->shouldReceive('createMessage')
        ->once()
        ->andReturn(-1);

        $this->expectException(Exception::class);
        $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 0, updateDatetime: '2022-10-10', systemUrl: '/test/test');
    }


    /**
     * @test
     * 契約書類
     * 次の署名者がホスト
     * @return void
     */
    public function signOrderContractNextSignUserHostTest()
    {
        $docEntiry = new DocumentSignOrder($this->getTestDataDoc(), $this->getTestDataSignUser(), $this->getTestDataIssueUser());

        $this->documentRepositoryMock->shouldReceive('getContractIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->queueUtilityMock->shouldReceive('createMessage')
        ->once()
        ->andReturn(0);

        $result = $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 0, updateDatetime: '2022-10-10', systemUrl: '/test/test');
        
        $this->assertTrue($result); 
    }

    /**
     * @test
     * 契約書類
     * 次の署名者がホスト
     * キュー登録失敗
     * @return void
     */
    public function signOrderContractNextSignUserHostQueueFaildTest()
    {
        $docEntiry = new DocumentSignOrder($this->getTestDataDoc(), $this->getTestDataSignUser(), $this->getTestDataIssueUser());

        $this->documentRepositoryMock->shouldReceive('getContractIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->queueUtilityMock->shouldReceive('createMessage')
        ->once()
        ->andReturn(-1);

        $this->expectException(Exception::class);
        $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 0, updateDatetime: '2022-10-10', systemUrl: '/test/test');
    }

    /**
     * @test
     * 取引書類
     * 次の署名者がゲスト
     * @return void
     */
    public function signOrderDealNextSignUserGuestTest()
    {
        $docEntiry = new DocumentSignOrder($this->getTestDataDoc(), $this->getTestDataSignUser(), $this->getTestDataIssueUser());

        $this->documentRepositoryMock->shouldReceive('getDealIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->queueUtilityMock->shouldReceive('createMessage')
        ->once()
        ->andReturn(0);

        $result = $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 1, updateDatetime: '2022-10-10', systemUrl: '/test/test');
        $this->assertTrue($result);
    }

    /**
     * @test
     * 取引書類
     * 次の署名者がゲスト
     * キュー登録失敗
     * @return void
     */
    public function signOrderDealNextSignUserGuestFaildTest()
    {
        $docEntiry = new DocumentSignOrder($this->getTestDataDoc(), $this->getTestDataSignUser(), $this->getTestDataIssueUser());

        $result111 = $this->documentRepositoryMock->shouldReceive('getDealIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->queueUtilityMock->shouldReceive('createMessage')
        ->once()
        ->andReturn(-1);

        $this->expectException(Exception::class);
        $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 1, updateDatetime: '2022-10-10', systemUrl: '/test/test');
    }

    /**
     * @test
     * 取引書類
     * 次の署名者がホスト
     * @return void
     */
    public function signOrderDealNextSignUserHostTest()
    {
        $docEntiry = new DocumentSignOrder($this->getTestDataDoc(), $this->getTestDataSignUser(), $this->getTestDataIssueUser());

        $this->documentRepositoryMock->shouldReceive('getDealIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->queueUtilityMock->shouldReceive('createMessage')
        ->once()
        ->andReturn(0);

        $result = $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 1, updateDatetime: '2022-10-10', systemUrl: '/test/test');
        
        $this->assertTrue($result);
    }
    
    /**
     * @test
     * 取引書類
     * 次の署名者がホスト
     * キュー登録失敗
     * @return void
     */
    public function signOrderDealNextSignUserHostFaildTest()
    {
        $docEntiry = new DocumentSignOrder($this->getTestDataDoc(), $this->getTestDataSignUser(), $this->getTestDataIssueUser());

        $this->documentRepositoryMock->shouldReceive('getDealIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->queueUtilityMock->shouldReceive('createMessage')
        ->once()
        ->andReturn(-1);

        $this->expectException(Exception::class);
        $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 1, updateDatetime: '2022-10-10', systemUrl: '/test/test');
    }


    /**
     * @test
     * 社内書類
     * @return void
     */
    public function signOrderInternalNextSignUserHostTest()
    {
        $dataSign =
            (object) [
                '0' =>
                (object) array(
                    'full_name' => '加藤　三郎',
                    'family_name' => '加藤',
                    'first_name' => '三郎',
                    'email' => 'testkatou@test.jp',
                    'wf_sort' => 1,
                    'category_id' => 2,
                ),
                '1' =>
                (object) array(
                    'full_name' => '木村　史郎',
                    'family_name' => '木村',
                    'first_name' => '史郎',
                    'email' => 'testkimura@test.jp',
                    'wf_sort' => 2,
                    'category_id' => 2,
                ),
            ];

        $docEntiry = new DocumentSignOrder($this->getTestDataDoc(), $dataSign, $this->getTestDataIssueUser());
        

        $this->documentRepositoryMock->shouldReceive('getInternalSignUserListInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->queueUtilityMock->shouldReceive('createMessage')
        ->twice()
        ->andReturn(0);

        $result = $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 2, updateDatetime: '2022-10-10', systemUrl: '/test/test');
        $this->assertTrue($result);
    }

    /**
     * @test
     * 社内書類
     * キュー登録失敗
     * @return void
     */
    public function signOrderInternalNextSignUserGuestFaildTest()
    {
        $dataSign =
            (object) [
                '0' =>
                (object) array(
                    'full_name' => '加藤　三郎',
                    'family_name' => '加藤',
                    'first_name' => '三郎',
                    'email' => 'testkatou@test.jp',
                    'wf_sort' => 1,
                    'category_id' => 2,
                ),
                '1' =>
                (object) array(
                    'full_name' => '木村　史郎',
                    'family_name' => '木村',
                    'first_name' => '史郎',
                    'email' => 'testkimura@test.jp',
                    'wf_sort' => 2,
                    'category_id' => 2,
                ),
            ];

        $docEntiry = new DocumentSignOrder($this->getTestDataDoc(), $dataSign, $this->getTestDataIssueUser());

        $this->documentRepositoryMock->shouldReceive('getInternalSignUserListInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->queueUtilityMock->shouldReceive('createMessage')
        ->once()
        ->andReturn(-1);

        $this->expectException(Exception::class);
        $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 2, updateDatetime: '2022-10-10', systemUrl: '/test/test');
    }

    /**
     * @test
     * 社内書類
     * キュー登録失敗
     * @return void
     */
    public function signOrderInternalNextSignUserGuestFaildTest2()
    {
        $dataSign =
            (object) [
                '0' =>
                (object) array(
                    'full_name' => '加藤　三郎',
                    'family_name' => '加藤',
                    'first_name' => '三郎',
                    'email' => 'testkatou@test.jp',
                    'wf_sort' => 1,
                    'category_id' => 2,
                ),
                '1' =>
                (object) array(
                    'full_name' => '木村　史郎',
                    'family_name' => '木村',
                    'first_name' => '史郎',
                    'email' => 'testkimura@test.jp',
                    'wf_sort' => 2,
                    'category_id' => 2,
                ),
            ];

        $docEntiry = new DocumentSignOrder($this->getTestDataDoc(), $dataSign, $this->getTestDataIssueUser());

        $this->documentRepositoryMock->shouldReceive('getInternalSignUserListInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->queueUtilityMock->shouldReceive('createMessage')
        ->once()
        ->andReturn(-1);

        $this->expectException(Exception::class);
        $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 2, updateDatetime: '2022-10-10', systemUrl: '/test/test');
    }

    /**
     * @test
     * 登録書類
     * @return void
     */
    public function signOrderArchiveNextSignUserGuestTest()
    {
        $docEntiry = new DocumentSignOrder($this->getTestDataDoc(), $this->getTestDataSignUser(), $this->getTestDataIssueUser());

        $this->documentRepositoryMock->shouldReceive('getArchiveIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->queueUtilityMock->shouldReceive('createMessage')
        ->once()
        ->andReturn(0);

        $result = $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 3, updateDatetime: '2022-10-10', systemUrl: '/test/test');
        $this->assertTrue($result);
    }

    /**
     * @test
     * 登録書類
     * キュー登録失敗
     * @return void
     */
    public function signOrderArchiveNextSignUserGuestQueueFaildTest()
    {
        $docEntiry = new DocumentSignOrder($this->getTestDataDoc(), $this->getTestDataSignUser(), $this->getTestDataIssueUser());

        $this->documentRepositoryMock->shouldReceive('getArchiveIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->queueUtilityMock->shouldReceive('createMessage')
        ->once()
        ->andReturn(-1);

        $this->expectException(Exception::class);
        $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 3, updateDatetime: '2022-10-10', systemUrl: '/test/test');
    }

    /**
     * @test
     * $emailAddressが空だったらException 
     * @return void
     */
    public function emailAddressPropatymptyTest()
    {
        $docEntiry = new DocumentSignOrder($this->getTestDataDoc(), $this->getTestDataSignUser(), $this->getTestDataIssueUser());

        $this->documentRepositoryMock->shouldReceive('getArchiveIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->expectException(Exception::class);
        
        $result = $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 3, updateDatetime: '2022-10-10', systemUrl: '/test/test');

        $reflectionClass = new ReflectionClass($result);

        $property = $reflectionClass->getProperty('emailAddress');

        $property->setAccessible(true);

        $property->setValue($result, '');
    }

    /**
     * @test
     * $emailTitleが空だったらException 
     * @return void
     */
    public function emailTitlePropatymptyTest()
    {
        $docEntiry = new DocumentSignOrder($this->getTestDataDoc(), $this->getTestDataSignUser(), $this->getTestDataIssueUser());

        $this->documentRepositoryMock->shouldReceive('getArchiveIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->queueUtilityMock->shouldReceive('createMessage')
        ->once()
        ->andReturn(0);

        $this->expectException(Exception::class);
        
        $result = $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 3, updateDatetime: '2022-10-10', systemUrl: '/test/test');

        $reflectionClass = new ReflectionClass($result);

        $property = $reflectionClass->getProperty('emailTitle');

        $property->setAccessible(true);

        $property->setValue($result, '');
    }

    /**
     * @test
     * $emailContentが空だったらException 
     * @return void
     */
    public function emailContentPropatymptyTest()
    {
        $docEntiry = new DocumentSignOrder($this->getTestDataDoc(), $this->getTestDataSignUser(), $this->getTestDataIssueUser());

        $this->documentRepositoryMock->shouldReceive('getArchiveIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->queueUtilityMock->shouldReceive('createMessage')
        ->once()
        ->andReturn(0);

        $this->expectException(Exception::class);
        
        $result = $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 3, updateDatetime: '2022-10-10', systemUrl: '/test/test');

        $reflectionClass = new ReflectionClass($result);

        $property = $reflectionClass->getProperty('emailContent');

        $property->setAccessible(true);

        $property->setValue($result, '');
    }

    /**
     * @test
     * $contractIsseuAndNextSignUserが空だったらException 
     * @return void
     */
    public function contractIsseuAndNextSignUserTest()
    {
        $docEntiry = new DocumentSignOrder($this->getTestDataDoc(), $this->getTestDataSignUser(), $this->getTestDataIssueUser());

        $this->documentRepositoryMock->shouldReceive('getArchiveIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->queueUtilityMock->shouldReceive('createMessage')
        ->once()
        ->andReturn(0);

        $this->expectException(Exception::class);
        
        $result = $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 3, updateDatetime: '2022-10-10', systemUrl: '/test/test');

        $reflectionClass = new ReflectionClass($result);

        $this->assertInstanceOf($resutl->contractIsseuAndNextSignUser, $docEntiry);

        $property = $reflectionClass->getProperty('contractIsseuAndNextSignUser');

        $property->setAccessible(true);

        $property->setValue($result, null);

    }

    /**
     * @test
     * $dealIsseuAndNextSignUserが空だったらException 
     * @return void
     */
    public function dealIsseuAndNextSignUserTest()
    {
        $docEntiry = new DocumentSignOrder($this->getTestDataDoc(), $this->getTestDataSignUser(), $this->getTestDataIssueUser());

        $this->documentRepositoryMock->shouldReceive('getArchiveIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->queueUtilityMock->shouldReceive('createMessage')
        ->once()
        ->andReturn(0);

        $this->expectException(Exception::class);
        
        $result = $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 3, updateDatetime: '2022-10-10', systemUrl: '/test/test');

        $reflectionClass = new ReflectionClass($result);

        $this->assertInstanceOf($resutl->dealIsseuAndNextSignUser, $docEntiry);

        $property = $reflectionClass->getProperty('dealIsseuAndNextSignUser');

        $property->setAccessible(true);

        $property->setValue($result, null);
    }

    /**
     * @test
     * $internalIsseuAndNextSignUserが空だったらException 
     * @return void
     */
    public function internalIsseuAndNextSignUserTest()
    {
        $docEntiry = new DocumentSignOrder($this->getTestDataDoc(), $this->getTestDataSignUser(), $this->getTestDataIssueUser());

        $this->documentRepositoryMock->shouldReceive('getArchiveIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->queueUtilityMock->shouldReceive('createMessage')
        ->once()
        ->andReturn(0);

        $this->expectException(Exception::class);
        
        $result = $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 3, updateDatetime: '2022-10-10', systemUrl: '/test/test');

        $reflectionClass = new ReflectionClass($result);

        $this->assertInstanceOf($result->internalIsseuAndNextSignUser, $docEntiry);

        $property = $reflectionClass->getProperty('internalIsseuAndNextSignUser');

        $property->setAccessible(true);

        $property->setValue($result, null);

    }

    /**
     * @test
     * $archiveIsseuAndNextSignUserが空だったらException 
     * @return void
     */
    public function archiveIsseuAndNextSignUserTest()
    {
        $docEntiry = new DocumentSignOrder($this->getTestDataDoc(), $this->getTestDataSignUser(), $this->getTestDataIssueUser());

        $this->documentRepositoryMock->shouldReceive('getArchiveIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->queueUtilityMock->shouldReceive('createMessage')
        ->once()
        ->andReturn(0);

        $this->expectException(Exception::class);
        
        $result = $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 3, updateDatetime: '2022-10-10', systemUrl: '/test/test');

        $reflectionClass = new ReflectionClass($result);

        $this->assertInstanceOf($result->archiveIsseuAndNextSignUser, $docEntiry);

        $property = $reflectionClass->getProperty('archiveIsseuAndNextSignUser');

        $property->setAccessible(true);

        $property->setValue($result, null);

    }

    /**
     * @test
     *　メールアドレス取得不可
     * @return void
     */
    public function emailAddressEmptytest_1()
    {
        $docEntiry = new DocumentSignOrder($this->getTestDataDoc(), $this->getTestDataSignUserEmailEmpty(), $this->getTestDataIssueUser());
        $this->documentRepositoryMock->shouldReceive('getContractIsseuAndNextSignUserInfo')
        ->once()
        ->andReturn($docEntiry);

        $this->expectException(Exception::class);
        $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 0, updateDatetime: '2022-10-10', systemUrl: '/test/test');
    }

    // /**
    //  * @test
    //  *　メールアドレス取得不可
    //  * @return void
    //  */
    // public function emailAddressEmptytest_２()
    // {
    //     $docEntiry = new DocumentSignOrder($this->getTestDataDoc(), $this->getTestDataSignUserEmailEmpty(), $this->getTestDataIssueUser());
    //     $this->documentRepositoryMock->shouldReceive('getDealIsseuAndNextSignUserInfo')
    //     ->once()
    //     ->andReturn($docEntiry);

    //     $this->expectException(Exception::class);
    //     $this->getObject()->signOrder(mUserId: 1, mUserCompanyId: 1, mUserTypeId: 0, documentId: 1, docTypeId: 1, categoryId: 1, updateDatetime: '2022-10-10', systemUrl: '/test/test');
    // }

    

    private function getObject()
    {
        return new DocumentSignOrderService($this->queueUtilityMock, $this->userConst, $this->docConst, $this->documentRepositoryMock);
    }

    public function getTestDataDocFlg_0()
    {
        $dataDoc = (object)[
            'document_id' => 1,
            'title' => 'テストタイトル',
            'file_prot_pw_flg' => 0
        ];
        return $dataDoc;
    }

    public function getTestDataDoc()
    {
        $dataDoc = (object)[
            'document_id' => 1,
            'title' => 'テストタイトル',
            'file_prot_pw_flg' => 1
        ];
        return $dataDoc;
    }

    public function getTestDataSignUser()
    {
        $dataSign =
        (object) [
            'user_id' => 2,
            'full_name' => '佐藤　次郎',
            'email' => 'testsato@test.jp',
            'user_type_id' => 0,
            'wf_sort' => 2,
            'category_id' => 0,
            'counter_party_id' => NULL,
            'counter_party_name' => NULL,
        ];
        return $dataSign;
    }

    public function getTestDataSignUserEmailEmpty()
    {
        $dataSign =
        (object) [
            'user_id' => 2,
            'full_name' => '佐藤　次郎',
            'email' => null,
            'user_type_id' => 0,
            'wf_sort' => 2,
            'category_id' => 0,
            'counter_party_id' => NULL,
            'counter_party_name' => NULL,
        ];
        return $dataSign;
    }

    public function getTestDataIssueUser()
    {
        $dataIssue = (object)[
            'full_name' => '大化　テスト',
            'family_name' => '大化',
            'first_name' => 'テスト',
            'wf_sort' => 0,
            'category_id' => 0,
        ];
        return $dataIssue;
    }
}
