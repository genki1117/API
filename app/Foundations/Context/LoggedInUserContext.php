<?php
declare(strict_types=1);
namespace App\Foundations\Context;

use App\Domain\Entities\Users\User;

class LoggedInUserContext
{
    /** @var User|null */
    private ?User $user = null;

    /**
     * @param User $user
     */
    public function set(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User|null
     */
    public function get(): ?User
    {
        return $this->user;
    }
}
