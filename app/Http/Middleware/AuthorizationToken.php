<?php
declare(strict_types=1);
namespace App\Http\Middleware;

use App\Domain\Entities\Users\User;
use App\Domain\Repositories\Interface\Common\LoginUserRepositoryInterface;
use App\Foundations\Context\LoggedInUserContext;
use App\Exceptions\AuthenticateException;
use Closure;
use Illuminate\Http\Request;

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
    ) {
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
        $token = $request->header(self::KOTSMP_TOKEN_NAME);
        if ($token === null) {
            // トークンなし
            throw new AuthenticateException(
                "common.message.expired",
                401
            );
        }

        //TODO　AzureADとの認証処理の設計が確定次第、実装する.
        // 旧設計方式(temp_tokenテーブル使用)の処理は一旦、コメント化

        // $data = $this->loginUserRepositoryInterface->getToken($token);
        // if ($data->getToken() === null) {
        //     // トークン存在しない場合
        //     // Lang::get("common.message.expired", null, "ja"),
        //     throw new AuthenticateException(
        //         "common.message.expired",
        //         401
        //     );
        // // return (new TokenResponse())->faildNoToken();
        // } elseif ($data->getExpiryDate() !== null && $data->getExpiryDate() <= $date) {
        //     // 期限切れ
        //     throw new AuthenticateException(
        //         "common.message.expired",
        //         401
        //     );
        //     // return (new TokenResponse())->faildNoToken();
        // }

        // $tokenData = json_decode($data->getData());

        // $user = $this->loginUserRepositoryInterface->getUser($tokenData->company_id, $tokenData->user_id);

        //TODO 仮でAzureADとの認証処理で、compnayId=1 userId=1のユーザを取得したとしておく
        // テストDBにcompnayId=1 userId=1  email="host1@email.com"で登録していること前提
        $user = $this->loginUserRepositoryInterface->getUser(compnayId:"1", email:"host1@email.com");
        if ($user->getUser() === null) {
            // ユーザー存在しない場合
            throw new AuthenticateException(
                "common.message.permission",
                403
            );
        } elseif (!$this->checkAuth($request->getPathInfo(), $user)) {
            // 権限チェック
            throw new AuthenticateException(
                "common.message.permission",
                403
            );
        } else {
            $this->loggedInUserContext->set($user);
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

        //全体共通のミドルウェア（Kernel.phpの$middleware）は、ルート存在チェックより先に動作するため
        if (!array_key_exists($requestUri, config('auth_list'))) {
            return true;
        }

        $auth = config('auth_list')[$requestUri];

        // 申込者については、該当するAPIがPHP側にはないため、考慮不要
        if ($user->getUser()->user_type_id === 1) {
            $ret = $auth['guest_user_role'];
        } elseif ($user->getUser()->user_type_id === 99) {
            $ret = $auth['system_administrator_role'];
        } elseif ($user->getUser()->user_type_id === 0) {
            $ret = $auth['reader_role'];
            if (!empty($user->getUserRole())) {
                if ($ret === false && $user->getUserRole()->admin_role) {
                    $ret = $auth['admin_role'];
                }
                if ($ret === false && $user->getUserRole()->template_set_role) {
                    $ret = $auth['template_set_role'];
                }
                if ($ret === false && $user->getUserRole()->workflow_set_role) {
                    $ret = $auth['workflow_set_role'];
                }
                if ($ret === false && $user->getUserRole()->master_set_role) {
                    $ret = $auth['master_set_role'];
                }
                if ($ret === false && $user->getUserRole()->archive_func_role) {
                    $ret = $auth['archive_func_role'];
                }
                if ($ret === false && $user->getUserRole()->template_rgst_role) {
                    $ret = $auth['template_rgst_role'];
                }
                if ($ret === false && $user->getUserRole()->ts_role) {
                    $ret = $auth['ts_role'];
                }
                if ($ret === false && $user->getUserRole()->bulk_ts_role) {
                    $ret = $auth['bulk_ts_role'];
                }
                if ($ret === false && $user->getUserRole()->bulk_veri_func_role) {
                    $ret = $auth['bulk_veri_func_role'];
                }
                if ($ret === false && $user->getUserRole()->cont_doc_app_role) {
                    $ret = $auth['cont_doc_app_role'];
                }
                if ($ret === false && $user->getUserRole()->deal_doc_app_role) {
                    $ret = $auth['deal_doc_app_role'];
                }
                if ($ret === false && $user->getUserRole()->int_doc_app_role) {
                    $ret = $auth['int_doc_app_role'];
                }
                if ($ret === false && $user->getUserRole()->arch_doc_app_role) {
                    $ret = $auth['arch_doc_app_role'];
                }
                if ($ret === false && $user->getUserRole()->use_cert_role) {
                    $ret = $auth['use_cert_role'];
                }
            }
        }

        return $ret;
    }
}
