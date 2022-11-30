<?php

namespace App\Http\Middleware;

use App\Domain\Entities\Users\User;
use App\Domain\Repositories\Interface\Common\LoginUserRepositoryInterface;
use App\Foundations\Context\LoggedInUserContext;
use App\Exceptions\AuthenticateException;
use Carbon\CarbonImmutable;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class AuthorizationToken
{
    public const KOTSMP_TOKEN_NAME = 'x-kotsmp-authorization';

    /** @var LoggedInUserContext */
    private LoggedInUserContext $loggedInUserContext;
    /** @var LoginUserRepositoryInterface */
    private LoginUserRepositoryInterface $loginUserRepositoryInterface;

    /**
     * @param LoginUserRepositoryInterface $loginUserRepositoryInterface
     */
    public function __construct(
        LoggedInUserContext $context,
        LoginUserRepositoryInterface $loginUserRepositoryInterface
    )
    {
        $this->loggedInUserContext = $context;
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
        $date = new CarbonImmutable();
        $token = $request->header(self::KOTSMP_TOKEN_NAME);
        if ($token === null) {
            // トークンなし
            throw new AuthenticateException(
                Lang::get("message.common.message.expired"),
                401
            );
            // return (new TokenResponse())->faildNoToken();
        }

        $data = $this->loginUserRepositoryInterface->getToken($token);
        if ($data === null) {
            // トークン存在しない場合
            // Lang::get("common.message.expired", null, "ja"),
            throw new AuthenticateException(
                Lang::get("common.message.expired"),
                401
            );
        // return (new TokenResponse())->faildNoToken();
        } elseif ($data->getExpiryDate() !== null && $data->getExpiryDate() <= $date) {
            // 期限切れ
            throw new AuthenticateException(
                Lang::get("common.message.expired"),
                401
            );
            // return (new TokenResponse())->faildNoToken();
        }

        $tokenData = json_decode($data->getData());
        $user = $this->loginUserRepositoryInterface->getUser($tokenData->company_id, $tokenData->user_id);
        if ($user === null) {
            // ユーザー存在しない場合
            throw new AuthenticateException(
                Lang::get("common.message.permission"),
                403
            );
        // return (new TokenResponse())->faildNoToken();
        } elseif (!$this->checkAuth($request->getPathInfo(), $user)) {
            // 権限チェック
            throw new AuthenticateException(
                Lang::get("common.message.permission"),
                403
            );
        // return (new TokenResponse())->faildNoToken();
        } else {
            $this->loggedInUserContext->set($user);
            // $request['m_user'] = $user->getUser();
            // $request['m_user_role'] = $user->getUserRole();
        }

        return $next($request);
    }

    /**
     * @param string $requestUri
     * @param User $user
     * @return bool
     */
    public function checkAuth(string $requestUri, User $user): bool
    {
        $ret = false;

        if (array_key_exists($requestUri, config('aut_list'))) {
            $ret = false;
        }

        $auth = config('aut_list')[$requestUri];
        if ($auth === null) {
            $ret = false;
        } elseif ($user->getUser()->user_type_id === 1) {
            $ret = $auth['guest_user_role'];
        } elseif ($user->getUser()->user_type_id === 99) {
            $ret = $auth['system_administrator_role'];
        } else {
            switch(true) {
                case $user->getUserRole()->admin_role:
                    $ret = $auth['reader_role']['admin_role'];
                    break;
                case $user->getUserRole()->template_set_role:
                    $ret = $auth['reader_role']['template_set_role'];
                    break;
                case $user->getUserRole()->workflow_set_role:
                    $ret = $auth['reader_role']['workflow_set_role'];
                    break;
                case $user->getUserRole()->master_set_role:
                    $ret = $auth['reader_role']['workflow_set_role'];
                    break;
                case $user->getUserRole()->archive_func_role:
                    $ret = $auth['reader_role']['archive_func_role'];
                    break;
                case $user->getUserRole()->ts_role:
                    $ret = $auth['reader_role']['ts_role'];
                    break;
                case $user->getUserRole()->bulk_ts_role:
                    $ret = $auth['reader_role']['bulk_ts_role'];
                    break;
                case $user->getUserRole()->cont_doc_app_role:
                    $ret = $auth['reader_role']['cont_doc_app_role'];
                    break;
                case $user->getUserRole()->deal_doc_app_role:
                    $ret = $auth['reader_role']['deal_doc_app_role'];
                    break;
                case $user->getUserRole()->int_doc_app_role:
                    $ret = $auth['reader_role']['int_doc_app_role'];
                    break;
                case $user->getUserRole()->arch_doc_app_role:
                    $ret = $auth['reader_role']['arch_doc_app_role'];
                    break;
                case $user->getUserRole()->use_cert_role:
                    $ret = $auth['reader_role']['use_cert_role'];
                    break;
                default:
                    $ret = false;
            }
        }
        return $ret;
    }
}
