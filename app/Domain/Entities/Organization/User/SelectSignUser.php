<?php
declare(strict_types=1);
namespace App\Domain\Entities\Organization\User;

use App\Domain\Entities\Organization\Group;

/**
 * 選択署名者（ゲスト）
 */
class SelectSignUser extends Signer
{
    /** @var int|null 並び順 */
    private ?int $wfSort;

    /**
     * @param string|null $familyName
     * @param string|null $firstName
     * @param string|null $email
     * @param int|null $userId
     * @param array<Group>|null $groups
     * @param int|null $wfSort
     */
    public function __construct(?string $familyName, ?string $firstName, ?string $email, ?int $userId, ?array $groups, ?int $wfSort)
    {
        $this->wfSort = $wfSort;
        parent::__construct($familyName, $firstName, $email, $userId, $groups);
    }

    /**
     * 並び順返却
     * @return int|null
     */
    public function getWfSort(): ?int
    {
        return $this->wfSort;
    }
}
