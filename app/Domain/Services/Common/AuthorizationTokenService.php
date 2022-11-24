<?php
declare(strict_types=1);
namespace App\Domain\Services\Common;

use App\Domain\Entities\Common\LoginUser;
use App\Domain\Repositories\Interface\Common\TempTokenRepositoryInterface;
use App\Domain\Repositories\Interface\Common\LoginUserRepositoryInterface;
use App\Http\Responses\Common\TokenResponse;
use Carbon\CarbonImmutable;
use JetBrains\PhpStorm\Pure;
use Illuminate\Http\Request;

class AuthorizationTokenService
{
    public const KOTSMP_TOKEN_NAME = 'x-kotsmp-authorization';

    /** @var TempTokenRepositoryInterface */
    private TempTokenRepositoryInterface $tempTokenRepository;
    /** @var LoginUserRepositoryInterface */
    private LoginUserRepositoryInterface $loginUSerRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(TempTokenRepositoryInterface $tempTokenRepository)
    {
        $this->tempTokenRepository = $tempTokenRepository;
        // $this->loginUSerRepository = $loginUSerRepository;
    }

    /**
     * @param string $hash
     * @return int
     */
    public function checkToken(Request $request): int
    {
        $ret = 0;
        $date = CarbonImmutable::now();
        $token = $request->header(self::KOTSMP_TOKEN_NAME);
        if ($token == null) {
            // トークンなし
            return (new TokenResponse())->faildNoToken();
        } else {
            $data = $this->tempTokenRepository->getToken($token);
            if ($token == null) {
                // トークン存在しない場合
                return (new TokenResponse())->faildNoToken();
            } elseif ($data->expiry_date <= $date) {
                // 期限切れ
                return (new TokenResponse())->faildNoToken();
            }

            $user = $this->loginUserRepositoryInterface->getUser($data->data->company_id, $data->data->user_id);
            if ($user == null) {
                // ユーザー存在しない場合
                return (new TokenResponse())->faildNoToken();
            } elseif ($user) {
                // 権限チェック
                return (new TokenResponse())->faildNoToken();
            } else {
                $request['m_user'] = $user->getUser();
                $request['m_user_role'] = $user->getUserRole();
            }
        }

        return $ret;
    }

    // /**
    //  * @param string $mailAddress
    //  * @return User|null
    //  */
    // public function getUser(string $mailAddress): ?User
    // {
    //     $userEntity = $this->userRepository->getUser($mailAddress);

    //     if (empty($userEntity->getMailAddress()) && empty($userEntity->getPassword())) {
    //         return null;
    //     }
    //     return $userEntity;
    // }

    // /**
    //  * @param User $storedUser
    //  * @param string $inputPassword
    //  * @return bool
    //  */
    // #[Pure]
    // public function checkPassword(User $storedUser, string $inputPassword): bool
    // {
    //     return $storedUser->getPassword() === $inputPassword;
    // }
}
