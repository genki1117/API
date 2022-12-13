<?php
declare(strict_types=1);
namespace App\Domain\Entities\Organization\User;

/**
 * 署名者関連の親クラス
 */
abstract class Signer extends User
{
    /** @var string|null メールアドレス */
    private ?string $email;
    /** @var int|null ユーザID */
    private ?int $userId;
    /** @var array|null 所属部署 */
    private ?array $groups;

    /**
     * @param string|null $familyName
     * @param string|null $firstName
     * @param string|null $email
     * @param int|null $userId
     * @param array|null $groups
     */
    public function __construct(?string $familyName, ?string $firstName, ?string $email, ?int $userId, ?array $groups)
    {
        $this->email = $email;
        $this->userId = $userId;
        $this->groups = $groups;
        parent::__construct($familyName, $firstName);
    }

    /**
     * メールアドレス返却
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * ユーザーIDを返却
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     *　所属部署返却
     * @return array|null
     */
    public function getGroups(): ?array
    {
        return $this->groups;
    }
}
