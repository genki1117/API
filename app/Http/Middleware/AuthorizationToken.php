<?php

namespace App\Http\Middleware;

use App\Accessers\DB\TempToken;
use App\Domain\Repositories\Interface\Common\LoginUserRepositoryInterface;
use App\Domain\Repositories\Interface\Common\TempTokenRepositoryInterface;
use App\Domain\Services\Common\AuthorizationTokenService;
use App\Http\Responses\Common\TokenResponse;
use Carbon\CarbonImmutable;
use Closure;
use DateTime;
use Illuminate\Http\Request;

class AuthorizationToken
{
    public const KOTSMP_TOKEN_NAME = 'x-kotsmp-authorization';

    // /** @var AuthorizationTokenService */
    // private AuthorizationTokenService $authorizationTokenService;
    // /** @var TempTokenRepositoryInterface */
    // private TempTokenRepositoryInterface $tempTokenRepository;
    /** @var LoginUserRepositoryInterface */
    private LoginUserRepositoryInterface $loginUserRepositoryInterface;

    /**
     * @param AuthorizationTokenService $authorizationTokenService
     */
    public function __construct(loginUserRepositoryInterface $loginUserRepositoryInterface)
    {
        // $this->authorizationTokenService = $authorizationTokenService;
        $this->loginUserRepositoryInterface = $loginUserRepositoryInterface;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // $date = new DateTime();
        // $this->authorizationTokenService->checkToken();
        $date = new CarbonImmutable();
        $token = $request->header(self::KOTSMP_TOKEN_NAME);
        if ($token == null) {
            // トークンなし
            return (new TokenResponse())->faildNoToken();
        } else {
            $data = $this->loginUserRepositoryInterface->getToken($token);
            if ($data == null) {
                // トークン存在しない場合
                return (new TokenResponse())->faildNoToken();
            } elseif ($data->getExpiryDate() !== null && $data->getExpiryDate() <= $date) {
                // 期限切れ
                return (new TokenResponse())->faildNoToken();
            }

            $token_data = json_decode($data->getData());
            $user = $this->loginUserRepositoryInterface->getUser($token_data->company_id, $token_data->user_id);
            if ($user == null) {
                // ユーザー存在しない場合
                return (new TokenResponse())->faildNoToken();
            } elseif (!$this->loginUserRepositoryInterface->checkAuth($request->getRequestUri(), $user)) {
                // 権限チェック
                return (new TokenResponse())->faildNoToken();
            } else {
                $request['m_user'] = $user->getUser();
                $request['m_user_role'] = $user->getUserRole();
            }
        }

        return $next($request);
    }
}
