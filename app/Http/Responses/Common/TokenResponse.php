<?php
declare(strict_types=1);
namespace App\Http\Responses\Common;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

class TokenResponse
{
    // public function successLogin()
    // {
    //     return new JsonResponse([
    //         "status" => "200",
    //         "message" => "login_ok"
    //     ], 200);

    // }

    public function faildNoToken()
    {
        return new JsonResponse([
            "status" => "401",
            "message" => Lang::get("common.message.expired", null, "ja"),
        ], 401);
    }

    public function faildNoUser()
    {
        return new JsonResponse([
            "status" => "403",
            "message" => Lang::get("common.message.permission", null, "ja"),
        ], 403);
    }

    public function faildNoAuth()
    {
        return new JsonResponse([
            "status" => "403",
            "message" => Lang::get("common.message.permission", null, "ja"),
        ], 403);
    }
}
