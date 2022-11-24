<?php
declare(strict_types=1);
namespace App\Http\Controllers\Samples\Api;

use App\Domain\UseCases\Sample\UserLoginUsecase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Samples\UseSampleRequest;
use App\Http\Responses\Samples\GetSampleRequest;
use App\Http\Responses\Samples\LoginResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UseSampleController extends Controller
{
    /** @var UserLoginUsecase */
    private UserLoginUsecase $userLoginUsecase;

    /**
     * @param UserLoginUsecase $userLoginUsecase
     */
    public function __construct(UserLoginUsecase $userLoginUsecase)
    {
        $this->userLoginUsecase = $userLoginUsecase;
    }

    /**
     * @return JsonResponse
     */
    public function getSample(): JsonResponse
    {
        return (new GetSampleRequest)->emit();
    }

    /**
     * @param UseSampleRequest $request
     * @return JsonResponse
     */
    public function login(UseSampleRequest $request): JsonResponse
    {
        $request = $request->all();
        if ($this->userLoginUsecase->login($request['mail_address'], $request['password'])) {
            return (new LoginResponse())->successLogin();
        } else {
            return (new LoginResponse())->faildLogin();
        }
    }
}
