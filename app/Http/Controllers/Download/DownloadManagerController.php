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

    //string $token, int $dlFileId

    public function fileDownload(Request $request)
    {
        // var_export($request->hash);
        // var_export($request->dl_file_id);
        $nowDate = CarbonImmutable::now();
        try {
            if ($request->hash && $request->dl_file_id === null) {

                $downloadDocumentResult = $this->downloadManagerService->getFile($request->hash, $request->dl_file_id, $nowDate);
                return (new DownloadFileResponse)->successDownload();
            }  
            

            if (!$downloadDocumentResult) {
                throw new Exception('common.messate.permission');
            }

            return (new DownloadFileResponse)->successDownload();
        } catch (Exception $e) {

            return (new DownloadFileResponse)->faildDownload($e->getMessage());
        }
    
        
    }
}
