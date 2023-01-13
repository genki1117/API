<?php

namespace App\Http\Controllers\Document;

use App\Domain\Consts\UserConst as UserConstain;
use App\Http\Responses\Document\DocumentDownloadCsvResponse;
use App\Domain\Services\Document\DocumentDownloadCsvService;
use App\Http\Requests\Document\DocumentCsvDownloadRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Http\Request;

class DocumentBulkCreateController extends Controller
{
    /** @var DocumentDownloadCsvService */
    private DocumentDownloadCsvService $documentDownloadCsvService;

    public function __construct(DocumentDownloadCsvService $documentDownloadCsvService)
    {
        $this->documentDownloadCsvService = $documentDownloadCsvService;
    }

    /**
     * @param DocumentCsvDownloadRequest $request
     * @return JsonResponse
     */
    public function dlTmpCsv(DocumentCsvDownloadRequest $request)
    {
        try {
            $mUserId        = $request->m_user['user_id'] ?? null;
            $mUserCompanyId = $request->m_user['company_id'] ?? null;
            $mUserTypeId    = $request->m_user['user_type'] ?? null;
            $categoryId     = $request->category_id ?? null;
            $fileName       = $request->file_name ?? null;

            // ユーザタイプがゲストの場合、エラー
            if ($mUserTypeId === UserConstain::USER_TYPE_GUEST) {
                throw new Exception('ゲストユーザは取得できません。');
            }

            // ユーザID、ユーザタイプID、書類カテゴリIDが取得出来ない場合は、エラー
            if ($mUserId === null || $mUserCompanyId === null || $mUserTypeId === null || $categoryId === null || $fileName === null) {
                throw new Exception('ユーザ情報、または書類情報が取得出来ませんでした。');
            }

                $this->documentDownloadCsvService->downloadCsv(
                mUserId: $mUserId, mUserCompanyId: $mUserCompanyId, mUserTypeId: $mUserTypeId, categoryId: $categoryId, fileName: $fileName
            );
            
            return (new DocumentDownloadCsvResponse)->successDownloadCsv();

        } catch (Exception $e) {
            
           return (new DocumentDownloadCsvResponse)->faildDownloadCsv($e->getMessage());
        }
        
    }
}
