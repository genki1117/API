<?php
declare(strict_types=1);
namespace App\Http\Responses\Common;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

class TokenResponse
{
    /**
     * @return JsonResponse
     */
    public function faildNoToken(): JsonResponse
    {
        return new JsonResponse([
            "status" => "401",
            "message" => Lang::get("common.message.expired", null, "ja"),
        ], 401);
    }

    /**
     * @return JsonResponse
     */
    public function faildNoUser(): JsonResponse
    {
        return new JsonResponse([
            "status" => "403",
            "message" => Lang::get("common.message.permission", null, "ja"),
        ], 403);
    }

    /**
     * @return JsonResponse
     */
    public function faildNoAuth(): JsonResponse
    {
        return new JsonResponse([
            "status" => "403",
            "message" => Lang::get("common.message.permission", null, "ja"),
        ], 403);
    }
}
