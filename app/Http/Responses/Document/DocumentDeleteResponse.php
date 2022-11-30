<?php
declare(strict_types=1);
namespace App\Http\Responses\Document;

use Illuminate\Http\JsonResponse;

class DocumentDeleteResponse
{
    public function successDelete()
    {
        return new JsonResponse([
            "status" => "200",
            "message" => "各書類削除が完了しました。"
        ], 200);
    }

    public function faildDelete(string $exceptionMessage)
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
