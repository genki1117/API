<?php
declare(strict_types=1);
namespace App\Domain\Entities\Common;

class User
{
    /** @var \stdClass|null */
    private ?\stdClass $m_user;
    /** @var \stdClass|null */
    private ?\stdClass $m_user_role;

    /**
     * @param \stdClass|null $m_user
     * @param \stdClass|null $m_user_role
     */
    public function __construct(?\stdClass $m_user = null, ?\stdClass $m_user_role = null)
    {
        $this->m_user = $m_user;
        $this->m_user_role = $m_user_role;
    }

    /**
     * @return \stdClass|null
     */
    public function getUser(): ?\stdClass
    {
        return $this->m_user;
    }

    /**
     * @return \stdClass|null
     */
    public function getUserRole(): ?\stdClass
    {
        return $this->m_user_role;
    }
}
