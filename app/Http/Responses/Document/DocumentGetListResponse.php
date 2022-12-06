<?php
declare(strict_types=1);
namespace App\Http\Responses\Document;

use Illuminate\Http\JsonResponse;

class DocumentGetListResponse
{
    /** @param array $responseData*/
    public function getListResponse(array $responseData)
    {
        return new JsonResponse($responseData, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param array $data 
     * @param int $total
     * @param int $dispPage
     * @param int $dispCount
     * @return array 
     */
    public function setGetListResponse(array $data, int $total, int $dispPage, int $dispCount): array
    {
        return [
            "data" => (object)[
                "contents" => $data
            ],
            "total" => $total,
            "per_page" => $dispPage,
            "current_page" => $dispCount,
        ];
    }
}
