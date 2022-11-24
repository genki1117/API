<?php
declare(strict_types=1);
namespace App\Domain\UseCases\Sample;

use App\Domain\Services\Sample\UserService;

class UserLoginUsecase
{
    /** @var UserService */
    private UserService $userService;

    /** @param UserService $userService */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param string $mailAddress
     * @param string $password
     * @return bool
     */
    public function login(string $mailAddress, string $password): bool
    {
        $user = $this->userService->getUser($mailAddress);

        if (empty($user)) {
            return false;
        }

        return $this->userService->checkPassword($user, $password);
    }
}
