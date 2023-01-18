<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Common;

use App\Accessers\DB\Master\MUser;
use App\Accessers\DB\Master\MUserRole;
use App\Accessers\DB\TempToken;
use App\Domain\Entities\Users\User as UserEntity;
use App\Domain\Entities\Common\TempToken as TempTokenEntity;
use App\Domain\Repositories\Interface\Common\LoginUserRepositoryInterface;

class LoginUserRepository implements LoginUserRepositoryInterface
{
    /**
     * @var MUser
     */
    private MUser $mUser;

    /**
     * @var MUserRole
     */
    private MUserRole $mUserRole;

    /**
     * @var TempToken
     */
    private TempToken $tempToken;

    /**
     * @param TempToken $tempToken
     * @param MUser $mUser
     * @param MUserRole $mUserRole
     */
    public function __construct(TempToken $tempToken, MUser $mUser, MUserRole $mUserRole)
    {
        $this->tempToken = $tempToken;
        $this->mUser = $mUser;
        $this->mUserRole = $mUserRole;
    }

    /**
     * @param string $token
     * @param string $expiryDate
     * @return TempTokenEntity
     */
    public function getToken(string $token, string $expiryDate = null): TempTokenEntity
    {
        $tempToken = $this->tempToken->getToken($token, $expiryDate);

        if (empty($tempToken)) {
            return new TempTokenEntity();
        }

        return new TempTokenEntity($tempToken->token, $tempToken->type, $tempToken->data, $tempToken->expiry_date);
    }

    /**
     * @param string $compnayId
     * @param string $email
     * @return UserEntity
     */
    public function getUser(string $compnayId, string $email): UserEntity
    {
        $userRole = null;
        $user = $this->mUser->getUserFromEmail($compnayId, $email);
        if (!empty($user)) {
            $userRole = $this->mUserRole->getUserRole($compnayId, (string)$user->user_id);
        } else {
            return new UserEntity();
        }

        return new UserEntity($user, $userRole);
    }
}
