<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Interface\Common;

use App\Domain\Entities\Common\TempToken;
use App\Domain\Entities\Users\User;

interface LoginUserRepositoryInterface
{
    /**
     * @param string $token
     * @param string $expiryDate
     * @return TempToken
     */
    public function getToken(string $token, string $expiryDate = null): TempToken;

    /**
     * @param string $compnayId
     * @param string $userId
     * @return User
     */
    public function getUser(string $compnayId, string $userId): User;
}
