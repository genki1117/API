<?php
declare(strict_types=1);
namespace App\Domain\Services\Sample;

use App\Domain\Entities\Sample\User;
use App\Domain\Repositories\Interface\Sample\UserRepositoryInterface;
use JetBrains\PhpStorm\Pure;

class UserService
{
    /** @var UserRepositoryInterface */
    private UserRepositoryInterface $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $mailAddress
     * @return User|null
     */
    public function getUser(string $mailAddress): ?User
    {
        $userEntity = $this->userRepository->getUser($mailAddress);

        if (empty($userEntity->getMailAddress()) && empty($userEntity->getPassword())) {
            return null;
        }
        return $userEntity;
    }

    /**
     * @param User $storedUser
     * @param string $inputPassword
     * @return bool
     */
    #[Pure]
    public function checkPassword(User $storedUser, string $inputPassword): bool
    {
        return $storedUser->getPassword() === $inputPassword;
    }
}
