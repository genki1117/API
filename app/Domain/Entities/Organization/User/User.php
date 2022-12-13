<?php
declare(strict_types=1);
namespace App\Domain\Entities\Organization\User;

/**
 * サービス利用のユーザ関連の親クラス
 */
abstract class User
{
    /** @var string|null 姓 */
    private ?string $familyName;
    /** @var string|null 名 */
    private ?string $firstName;

    /**
     * @param string|null $familyName
     * @param string|null $firstName
     */
    public function __construct(?string $familyName, ?string $firstName)
    {
        $this->familyName = $familyName;
        $this->firstName  = $firstName;
    }

    /**
     * 姓を返却
     * @return string
     */
    public function getFamilyName(): string
    {
        return $this->familyName;
    }

    /**
     * 名を返却
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }
}
