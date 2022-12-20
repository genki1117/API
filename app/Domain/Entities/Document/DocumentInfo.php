<?php
declare(strict_types=1);
namespace App\Domain\Entities\Document;

/**
 * 摘要エンティティ
 */
class DocumentInfo
{
    /**
     * 摘要（ラベル）
     * @var string
     */
    private string $title;
    /**
     * 摘要（内容）
     * @var string
     */
    private string $content;

    /**
     * @param string $title
     * @param string $content
     */
    public function __construct(string $title, string $content)
    {
        $this->title = $title;
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
