<?php
declare(strict_types=1);
namespace App\Domain\Entities\Organization\User;

/**
 * 選択署名者（ゲスト）
 */
class SelectSignGuestUser extends Signer
{
    /** @var int|null 会社ID */
    private ?int $counterPartyId;
    /** @var string|null 会社名 */
    private ?string $counterPartyName;
    /** @var int|null 並び順 */
    private ?int $wfSort;

    /**
     * @param string|null $familyName
     * @param string|null $firstName
     * @param string|null $email
     * @param int|null $userId
     * @param array $groups
     * @param int|null $counterPartyId
     * @param string|null $counterPartyName
     * @param int|null $wfSort
     */
    public function __construct(
        ?string $familyName,
        ?string $firstName,
        ?string $email,
        ?int $userId,
        array $groups,
        ?int $counterPartyId,
        ?string $counterPartyName,
        ?int $wfSort
    ) {
        $this->counterPartyId = $counterPartyId;
        $this->counterPartyName = $counterPartyName;
        $this->wfSort = $wfSort;
        parent::__construct($familyName, $firstName, $email, $userId, $groups);
    }

    /**
     * 会社ID返却
     * @return int|null
     */
    public function getCounterPartyId(): ?int
    {
        return $this->counterPartyId;
    }

    /**
     * 会社名返却
     * @return string|null
     */
    public function getCounterPartyName(): ?string
    {
        return $this->counterPartyName;
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
