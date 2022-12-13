<?php
declare(strict_types=1);
namespace App\Domain\Entities\Organization\User;

/**
 * 変更操作ユーザ
 */
class OperationUser extends User
{
    /** @var string|null 作成日時 */
    private ?string $createDatetime;
    /** @var array|null 変更項目一覧 */
    private ?array $content;

    /**
     * @param string|null $familyName
     * @param string|null $firstName
     * @param string|null $createDatetime
     * @param array|null $content
     */
    public function __construct(
        ?string $familyName,
        ?string $firstName,
        ?string $createDatetime,
        ?array $content
    ) {
        $this->createDatetime = $createDatetime;
        $this->content = $content;
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

    /**
     * 変更項目一覧を返却
     * @return array|null
     */
    public function getContent(): ?array
    {
        return $this->content;
    }
}
