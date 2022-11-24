<?php
declare(strict_types=1);
namespace App\Http\Responses\Samples;

use Illuminate\Http\JsonResponse;

class GetSampleRequest
{
    /**
     * @return JsonResponse
     */
    public function emit(): JsonResponse
    {
        return new JsonResponse([
            "status" => "200",
            "message" => "login_ok"
        ], 200);
    }
}
