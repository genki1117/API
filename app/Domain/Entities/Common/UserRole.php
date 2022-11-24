<?php
declare(strict_types=1);
namespace App\Domain\Entities\Common;

class UserRole
{
    /** @var string|null */
    private ?string $token;
    /** @var string|null */
    private ?string $type;
    /** @var string|null */
    private ?string $data;
    /** @var string|null */
    private ?string $expiry_date;

    /**
     * @param string|null $mailAddress
     * @param string|null $password
     */
    public function __construct(?string $token = null, ?string $type = null, ?string $data = null, ?string $expiry_date = null)
    {
        $this->token = $token;
        $this->type = $type;
        $this->data = $data;
        $this->expiry_date = $expiry_date;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @return string|null
     */
    public function getData(): ?string
    {
        return $this->data;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getExpiry_date(): ?string
    {
        return $this->expiry_date;
    }
}
