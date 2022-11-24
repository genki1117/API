<?php
declare(strict_types=1);
namespace App\Domain\Entities\Sample;

class User
{
    /** @var string|null */
    private ?string $mailAddress;
    /** @var string|null */
    private ?string $password;

    /**
     * @param string|null $mailAddress
     * @param string|null $password
     */
    public function __construct(?string $mailAddress = null, ?string $password = null)
    {
        $this->mailAddress = $mailAddress;
        $this->password    = $password;
    }

    /**
     * @return string|null
     */
    public function getMailAddress(): ?string
    {
        return $this->mailAddress;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
}
