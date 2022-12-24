<?php
declare(strict_types=1);
namespace App\Domain\Entities\Organization\User;

/**
 * アクセスユーザ
 */
class AccessUser extends User
{
    /** @var string|null 作成日時 */
    private ?string $createDatetime;

    /**
     * @param string|null $createDatetime
     * @param string|null $familyName
     * @param string|null $firstName
     */
    public function __construct(?string $createDatetime, ?string $familyName, ?string $firstName)
    {
        $this->createDatetime = $createDatetime;
        parent::__construct($familyName, $firstName);
    }

    /**
     * 作成日時を返却
     * @return string|null
     */
    public function getCreateDatetime(): ?string
    {
        return $this->createDatetime;
    }
}
