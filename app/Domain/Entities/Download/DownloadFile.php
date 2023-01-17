<?php
declare(strict_types=1);
namespace App\Domain\Entities\Download;

class DownloadFile
{
    /** @var \stdClass|null */
    private ?\stdClass $data;

    /**
     * @param \stdClass|null $document
     */
    public function __construct(
        ?\stdClass $data = null,
    )
    {
        $this->data = $data;
    }

    /** @return \stdClass|null */
    public function getData(): ?\stdClass
    {
        return $this->data;
    }
}
