<?php
declare(strict_types=1);
namespace App\Http\Responses\Samples;

use Illuminate\Http\JsonResponse;

class LoginResponse
{
    public function successLogin()
    {
        return new JsonResponse([
            "status" => "200",
            "message" => "login_ok"
        ], 200);
    }

    public function faildLogin()
    {
        return new JsonResponse([
            "status" => "200",
            "message" => "login_ng"
        ], 200);
    }
}
