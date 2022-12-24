<?php
declare(strict_types=1);
namespace App\Domain\Entities\Common;

class TempToken
{
    /** @var string|null */
    private ?string $token;
    /** @var string|null */
    private ?string $type;
    /** @var string|null */
    private ?string $data;
    /** @var string|null */
    private ?string $expiryDate;

    /**
     * @param string|null $token
     * @param string|null $type
     * @param string|null $data
     * @param string|null $expiryDate
     */
    public function __construct(?string $token = null, ?string $type = null, ?string $data = null, ?string $expiryDate = null)
    {
        $this->token = $token;
        $this->type = $type;
        $this->data = $data;
        $this->expiryDate = $expiryDate;
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
    public function getExpiryDate(): ?string
    {
        return $this->expiryDate;
    }
}
