<?php
declare(strict_types=1);
namespace App\Domain\Entities\Document;

/**
 * 摘要
 */
class Summary
{
    /** @var string|null 摘要（ラベル） */
    private ?string $title;
    /** @var string|null 摘要（内容） */
    private ?string $content;

    /**
     * @param string|null $title
     * @param string|null $content
     */
    public function __construct(?string $title, ?string $content)
    {
        $this->title = $title;
        $this->content = $content;
    }

    /**
     * 摘要（ラベル）を返却
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * 摘要（内容）を返却
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }
}
