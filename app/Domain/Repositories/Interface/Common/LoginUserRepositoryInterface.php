<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Interface\Common;

use App\Domain\Entities\Common\TempToken;
use App\Domain\Entities\Common\User;

interface LoginUserRepositoryInterface
{
    /**
     * @param string $token
     * @param string $expiry_date
     * @return TempToken
     */
    public function getToken(string $token, string $expiry_date = null): TempToken;

    /**
     * @param string $compnay_id
     * @param string $user_id
     * @return User
     */
    public function getUser(string $compnay_id, string $user_id): User;

    /**
     * @param string $requestUri
     * @param User $user
     * @return bool
     */
    public function checkAuth(string $requestUri, User $user): bool;
}
