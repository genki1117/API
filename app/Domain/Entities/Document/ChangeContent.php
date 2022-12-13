<?php
declare(strict_types=1);
namespace App\Domain\Entities\Document;

/**
 * 変更項目
 */
class ChangeContent
{
    /** @var string 変更前 */
    private string $beforeContent;
    /** @var string 変更後 */
    private string $afterContent;

    /**
     * @param string $beforeContent
     * @param string $afterContent
     */
    public function __construct(string $beforeContent, string $afterContent)
    {
        $this->beforeContent = $beforeContent;
        $this->afterContent  = $afterContent;
    }

    /**
     * @return string
     */
    public function getBeforeContent(): string
    {
        return $this->beforeContent;
    }

    /**
     * @return string
     */
    public function getAfterContent(): string
    {
        return $this->afterContent;
    }
}
