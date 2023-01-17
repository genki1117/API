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


    public function fileDownload(string $token)
    {
        try {
            if (empty($token)) {
                throw new Exception('common.message.not-found');
            }
            $nowDate = CarbonImmutable::now();
            return $downloadDocumentResult = $this->downloadManagerService->getFile(token: $token, nowDate: $nowDate);    

            if (!$downloadDocumentResult) {
                throw new Exception('common.messate.permission');
            }

            return (new DownloadFileResponse)->successDownload();
        } catch (Exception $e) {

            return (new DownloadFileResponse)->faildDownload($e->getMessage());
        }
        
        
    }
}
