<?php
declare(strict_types=1);

namespace App\Http\Responses\Document;

use Illuminate\Http\JsonResponse;

class DocumentDownloadCsvResponse
{
    /**
     * errorレスポンス
     *
     * @param string $exceptionMessage
     * @return JsonResponse
     */
    public function faildDownloadCsv(string $exceptionMessage): JsonResponse
    {
        return new JsonResponse([
            "status" => "200",
            "message" => $exceptionMessage
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * successレスポンス
     *
     * @return JsonResponse
     */
    public function successDownloadCsv(): JsonResponse
    {
        return new JsonResponse([
            "status" => "200",
            "message" => "CSVファイルの取得に成功しました。"
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
