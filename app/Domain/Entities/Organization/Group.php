<?php
declare(strict_types=1);
namespace App\Domain\Entities\Organization;

/**
 * 部署
 */
class Group
{
    /** @var int|null 部署ID */
    private ?int $groupId;
    /** @var string|null 部署名 */
    private ?string $groupName;

    /**
     * @param int|null $groupId
     * @param string|null $groupName
     */
    public function __construct(?int $groupId, ?string $groupName)
    {
        $this->groupId = $groupId;
        $this->groupName = $groupName;
    }

    /**
     * 部署ID返却
     * @return int|null
     */
    public function getGroupId(): ?int
    {
        return $this->groupId;
    }

    /**
     * 部署名返却
     * @return string|null
     */
    public function getGroupName(): ?string
    {
        return $this->groupName;
    }
}
