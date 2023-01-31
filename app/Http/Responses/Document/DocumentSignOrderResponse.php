<?php
declare(strict_types=1);
namespace App\Http\Responses\Document;

use Illuminate\Http\JsonResponse;

class DocumentSignOrderResponse
{
    public function successSignOrder()
    {
        return new JsonResponse([
            "status" => "200",
            "message" => "署名依頼メールを送信しました。"
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function failedSignOrder(string $exceptionMessage)
    {
        return new JsonResponse([
            "status" => "400",
            "message" => $exceptionMessage
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
