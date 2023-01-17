<?php

namespace App\Http\Controllers\Download;

use Carbon\CarbonImmutable;
use App\Http\Responses\Download\DownloadFileResponse;
use App\Domain\Services\Download\DownloadManagerService;
use App\Http\Requests\Download\DownloadManagerTokenRequest;
use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DownloadManagerController extends Controller
{
    /** @var DownloadManagerService */
    private DownloadManagerService $downloadManagerService;

    public function __construct(DownloadManagerService $downloadManagerService)
    {
        $this->downloadManagerService = $downloadManagerService;
    }


    public function fileDownload(Request $reqeust, string $token)
    {
        try {
            // $mUserId        = $request->m_user['user_id'];    //TODO: 要確認 
            // $mUserCompanyId = $request->m_user['company_id']; //TODO: 要確認
            $mUserId        = 1; // 仮データ
            $mUserCompanyId = 1; // 仮データ
            $nowDate        = CarbonImmutable::now();
            return $downloadDocumentResult = $this->downloadManagerService->getFile(mUserId: $mUserId, mUserCompanyId: $mUserCompanyId, token: $token, nowDate: $nowDate);    

            if (!$downloadDocumentResult) {
                throw new Exception('ダウンロードに失敗しました。');
            }

            return (new DownloadFileResponse)->successDownload();
        } catch (Exception $e) {

            return (new DownloadFileResponse)->faildDownload($e->getMessage());
        }
        
        
    }
}
