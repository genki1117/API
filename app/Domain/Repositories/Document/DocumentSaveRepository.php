<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Document;

use App\Accessers\DB\Document\DocumentContract;
use App\Accessers\DB\Document\DocumentArchive;
use App\Accessers\DB\Document\DocumentInternal;
use App\Accessers\DB\Document\DocumentDeal;
use App\Accessers\DB\Document\DocumentPermissionArchive;
use App\Accessers\DB\Document\DocumentPermissionInternal;
use App\Accessers\DB\Document\DocumentPermissionTransaction;
use App\Accessers\DB\Document\DocumentPermissionContract;
use App\Accessers\DB\Document\DocumentStorageContract;
use App\Accessers\DB\Document\DocumentStorageTransaction;
use App\Accessers\DB\Document\DocumentStorageInternal;
use App\Accessers\DB\Document\DocumentStorageArchive;
use App\Accessers\DB\Document\DocumentWorkFlow;
use App\Accessers\DB\Log\System\LogSystemAccess;
use App\Accessers\DB\Log\System\LogDocOperation;
use App\Domain\Repositories\Interface\Document\DocumentSaveRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
// use App\Domain\Entities\Document\DocumentDetail as DocumentEntity;

class DocumentSaveRepository implements DocumentSaveRepositoryInterface
{
    /**
     * @var Document
     */
    private DocumentContract $docContract;
    private DocumentDeal $docDeal;
    private DocumentInternal $docInternal;
    private DocumentArchive $docArchive;
    private DocumentPermissionContract $docPermissionContract;
    private DocumentPermissionTransaction $docPermissionTransaction;
    private DocumentPermissionInternal $docPermissionInternal;
    private DocumentPermissionArchive $docPermissionArchive;
    private DocumentStorageContract $docStorageContract;
    private DocumentStorageTransaction $docStorageTransaction;
    private DocumentStorageInternal $docStorageInternal;
    private DocumentStorageArchive $docStorageArchive;
    private DocumentWorkFlow $documentWorkFlow;


    /**
     * @param DocumentContract $docContract
     * @param DocumentDeal $docDeal
     * @param DocumentInternal $docInternal
     * @param DocumentArchive $docArchive
     * @param DocumentPermissionContract $docPermissionContract
     * @param DocumentPermissionTransaction $docPermissionTransaction
     * @param DocumentPermissionInternal $docPermissionInternal
     * @param DocumentPermissionArchive $docPermissionArchive
     * @param DocumentStorageContract $docStorageContract
     * @param DocumentStorageTransaction $docStorageTransaction
     * @param DocumentStorageInternal $docStorageInternal
     * @param DocumentStorageArchive $docStorageArchive
     * @param DocumentWorkFlow $documentWorkFlow
     */

     public function __construct(DocumentContract $docContract,
                                 DocumentDeal $docDeal,
                                 DocumentInternal $docInternal,
                                 DocumentArchive $docArchive,
                                 DocumentPermissionContract $docPermissionContract,
                                 DocumentPermissionTransaction $docPermissionTransaction,
                                 DocumentPermissionInternal $docPermissionInternal,
                                 DocumentPermissionArchive $docPermissionArchive,
                                 DocumentStorageContract $docStorageContract,
                                 DocumentStorageTransaction $docStorageTransaction,
                                 DocumentStorageInternal $docStorageInternal,
                                 DocumentStorageArchive $docStorageArchive,
                                 DocumentWorkFlow $documentWorkFlow
                                 )
    {
        $this->docContract              = $docContract;
        $this->docDeal                  = $docDeal;
        $this->docInternal              = $docInternal;
        $this->docArchive               = $docArchive;
        $this->docPermissionContract    = $docPermissionContract;
        $this->docPermissionTransaction = $docPermissionTransaction;
        $this->docPermissionInternal    = $docPermissionInternal;
        $this->docPermissionArchive     = $docPermissionArchive;
        $this->docStorageContract       = $docStorageContract;
        $this->docStorageTransaction    = $docStorageTransaction;
        $this->docStorageInternal       = $docStorageInternal;
        $this->docStorageArchive        = $docStorageArchive;
        $this->documentWorkFlow         = $documentWorkFlow;
    }

    /**
     * -------------------------
     * 契約書類新規登録
     * -------------------------
     * 
     * @param array $requestContent
     * @return boolean
     */
    public function contractInsert(array $requestContent): bool
    {
        // 契約書類登録
        $docInsertResult           = $this->docContract->insert($requestContent);

        // 契約書類閲覧権限登録
        $docPermissionInsertResult = $this->docPermissionContract->insert($requestContent) ;

        // 契約書類容量登録
        $docStorageInsertResult    = $this->docStorageContract->insert($requestContent);

        // ワークフローテーブル登録 

        // ワークフローが起票者のみ
        if (count($requestContent['select_sign_user']) === 1) {
            $companyId  = $requestContent['company_id'];
            $categoryId = $requestContent['category_id'];
            $appUserId  = $requestContent['select_sign_user'][0]['user_id'];
            $wfSort     = 0;
            $userId     = $requestContent['m_user_id'];
            $createDate = $requestContent['create_datetime'];

            $documentWorkFlowResult = $this->documentWorkFlow->insert($companyId, $categoryId, $appUserId, $wfSort, $userId, $createDate);
                if (!$documentWorkFlowResult) {
                    throw new Exception('契約書類テーブルおよび契約書類閲覧権限および契約書類容量を登録出来ません。');
                    exit;
                }

        // ゲスト署名者が未入力の場合
        } else if ($requestContent['select_sign_guest_user'] === null) {
            $selectSignUserList = $requestContent['select_sign_user'];
            foreach ($selectSignUserList as $wf_sort => $selectSignUser) {
                $companyId              = $requestContent['company_id'];
                $categoryId             = $requestContent['category_id'];
                $appUserId              = $selectSignUser['user_id'];
                $wfSort                 = $wf_sort * 10;
                $userId                 = $requestContent['m_user_id'];
                $createDate             = $requestContent['create_datetime'];

                $documentWorkFlowResult = $this->documentWorkFlow->insert($companyId, $categoryId, $appUserId, $wfSort, $userId, $createDate);
                if (!$documentWorkFlowResult) {
                    throw new Exception('契約書類テーブルおよび契約書類閲覧権限および契約書類容量を登録出来ません。');
                    exit;
                }
            }
        } else {
            $selectSignUserList = array_merge($requestContent['select_sign_user'], $requestContent['select_sign_guest_user']);
            foreach ($selectSignUserList as $wf_sort => $selectSignUser) {
                $companyId              = $requestContent['company_id'];
                $categoryId             = $requestContent['category_id'];
                $appUserId              = $selectSignUser['user_id'];
                $wfSort                 = $wf_sort * 10;
                $userId                 = $requestContent['m_user_id'];
                $createDate             = $requestContent['create_datetime'];

                $documentWorkFlowResult = $this->documentWorkFlow->insert($companyId, $categoryId, $appUserId, $wfSort, $userId, $createDate);
                if (!$documentWorkFlowResult) {
                    throw new Exception('契約書類テーブルおよび契約書類閲覧権限および契約書類容量を登録出来ません。');
                    exit;
                }
            }
        }
        if ($docInsertResult === false || $docPermissionInsertResult === false || $docStorageInsertResult === false || $documentWorkFlowResult === false) {
            throw new Exception('登録書類テーブルおよび登録書類閲覧権限および登録書類容量を登録できません。');
        }
        return true;
    }

    /**
     * -------------------------
     * 契約書類更新
     * -------------------------
     *
     * @param array $requestContent
     * @return boolean
     */
    public function contractUpdate(array $requestContent): bool
    {
        // 契約書類更新
        $docUpdateResult           = $this->docContract->update($requestContent);

        // 契約書類閲覧権限更新
        $docPermissionUpdateResult = $this->docPermissionContract->update($requestContent);

        // 契約書類容量更新
        $docStorageUpdateResult    = $this->docStorageContract->update($requestContent);

        if (!$docUpdateResult === 1 || !$docPermissionUpdateResult === 1 || !$docStorageUpdateResult === 1) {
            throw new Exception('登録書類テーブルおよび登録書類閲覧権限および登録書類容量を更新できません。');
        }
        return true;
    }









    

    // 取引書類新規登録
    public function dealInsert($request)
    {
        \DB::beginTransaction($request);
            $docSave = $this->docDeal->save($request);
            $docPermissionSave = $this->docPermissionTransaction->save($request);
            $docStorageSave = $this->docStorageTransaction->save($request);
        if ($docSave === false && $docPermissionSave === false && $docStorageSave === false) {
            \DB::rollback();
            return false;
        }
        \DB::commit();
            return true;
    }

    // 社内書類新規登録
    public function internalInsert($request)
    {
        \DB::beginTransaction($request);
            $docSave = $this->docInternal->save($request);
            $docPermissionSave = $this->docPermissionInternal->save($request);
            $docStorageSave = $this->docStorageInternal->save($request);
        if ($docSave === false && $docPermissionSave === false && $docStorageSave === false) {
            \DB::rollback();
            return false;
        }
        \DB::commit();
            return true;
    }

    // 登録書類新規登録
    public function archiveInsert($request)
    {
        \DB::beginTransaction($request);
            $docSave = $this->docArchive->save($request);
            $docPermissionSave = $this->docPermissionArchive->save($request);
            $docStorageSave = $this->docStorageArchive->save($request);
        if ($docSave === false && $docPermissionSave === false && $docStorageSave === false) {
            \DB::rollback();
            return false;
        }
        \DB::commit();
            return true;
    }

    

    // 取引書類更新保存
    public function dealUpdate($request)
    {
        \DB::beginTransaction($request);
            $docUpdate = $this->docDeal->update($request);
            $docPermissionUpdate = $this->docPermissionTransaction->update($request);
            $docStorageUpdate = $this->docStorageTransaction->update($request);
        if (!$docUpdate === 1 && !$docPermissionUpdate === 1 && !$docStorageUpdate === 1) {
            \DB::rollback();
            return false;
        }
        \DB::commit();
        return true;
    }

    // 社内書類更新保存
    public function internalUpdate($request)
    {
        \DB::beginTransaction($request);
            $docUpdate = $this->docInternal->update($request);
            $docPermissionUpdate = $this->docPermissionInternal->update($request);
            $docStorageUpdate = $this->docStorageInternal->update($request);
        if (!$docUpdate === 1 && !$docPermissionUpdate === 1 && !$docStorageUpdate === 1) {
            \DB::rollback();
            return false;
        }
        \DB::commit();
        return true;
    }

    // 登録書類更新保存
    public function archiveUpdate($request)
    {
        \DB::beginTransaction($request);
            $docUpdate = $this->docArchive->update($request);
            $docPermissionUpdate = $this->docPermissionArchive->update($request);
            $docStorageUpdate = $this->docStorageArchive->update($request); 
        if (!$docUpdate === 1 && !$docPermissionUpdate === 1 && !$docStorageUpdate === 1) {
            \DB::rollback();
            return false;
        }
        \DB::commit();
        return true;
    }
    
}
