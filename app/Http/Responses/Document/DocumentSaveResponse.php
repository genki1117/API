<?php
declare(strict_types=1);
namespace App\Http\Responses\Document;

use App\Domain\Entities\Document\DocumentDetail;
use Illuminate\Http\JsonResponse;

class DocumentSaveResponse
{
    public function documentSaveResponse($documentSaveResult)
    {
        return new JsonResponse([
            "date" => [
                "result" => $documentSaveResult,
            ]
        ], 200);
    }

    public function successSave()
    {
        return new JsonResponse([
            "status" => "200",
            "message" => "書類保存が完了しました。" ,
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function faildSave(string $exceptionMessage)
    {
        return new JsonResponse([
            "status" => "400",
            "messaage" => $exceptionMessage,
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
