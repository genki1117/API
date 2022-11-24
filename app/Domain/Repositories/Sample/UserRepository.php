<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Sample;

use App\Accessers\DB\Sample\User;
use App\Domain\Entities\Sample\User as UserEntity;
use App\Domain\Repositories\Interface\Sample\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var User
     */
    private User $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param string $mailAddress
     * @return UserEntity
     */
    public function getUser(string $mailAddress): UserEntity
    {
        $user = $this->user->getUser($mailAddress);

        if (empty($user)) {
            return new UserEntity();
        }

        return new UserEntity($user->mail_address, $user->password);
    }
}
