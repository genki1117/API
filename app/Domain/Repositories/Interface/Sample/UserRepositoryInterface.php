<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Interface\Sample;

use App\Domain\Entities\Sample\User;

interface UserRepositoryInterface
{
    /**
     * @param string $mailAddress
     * @return User
     */
    public function getUser(string $mailAddress): User;
}
