<?php
declare(strict_types=1);
namespace App\Domain\Entities\Users;

class User
{
    /** @var \stdClass|null */
    private ?\stdClass $mUser;
    /** @var \stdClass|null */
    private ?\stdClass $mUserRole;

    /**
     * @param \stdClass|null $mUser
     * @param \stdClass|null $mUserRole
     */
    public function __construct(?\stdClass $mUser = null, ?\stdClass $mUserRole = null)
    {
        $this->mUser = $mUser;
        $this->mUserRole = $mUserRole;
    }

    /**
     * @return \stdClass|null
     */
    public function getUser(): ?\stdClass
    {
        return $this->mUser;
    }

    /**
     * @return \stdClass|null
     */
    public function getUserRole(): ?\stdClass
    {
        return $this->mUserRole;
    }
}
