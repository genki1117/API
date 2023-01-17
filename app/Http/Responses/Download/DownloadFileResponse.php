<?php
declare(strict_types=1);
namespace App\Http\Responses\Download;

use Illuminate\Http\JsonResponse;

class DownloadFileResponse
{
    public function successDownload()
    {
        return new JsonResponse([
            "status" => "200",
            "message" => "ダウンロードが完了しました。"
        ], 200);
    }

    public function faildDownload(string $exceptionMessage)
    {
        return new JsonResponse([
            "status" => "400",
            "message" => $exceptionMessage
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function beforeContent($beforeData)
    {
        return new JsonResponse($beforeData, 200);
    }

    public function afterContent($afterData)
    {
        return new JsonResponse($afterData, 200);
    }
}
