<?php

declare(strict_types=1);
namespace App\Domain\Entities\Document;

class DocumentDelete
{
    /** @var \stdClass|null */
    private ?\stdClass $document;
    private ?\stdClass $docPermission;
    private ?\stdClass $docStorage;

    /**
     * @param \stdClass|null $document
     * @param \stdClass|null $docPermission
     * @param \stdClass|null $docStorage
     */
    public function __construct(
        ?\stdClass $document = null,
        ?\stdClass $docPermission = null,
        ?\stdClass $docStorage = null
    )
    {
        $this->document = $document;
        $this->docPermission = $docPermission;
        $this->docStorage = $docStorage;
    }

    /** @return \stdClass|null */
    public function getDeleteDocument(): ?\stdClass
    {
        return $this->document;
    }

    /** @return \stdClass|null */
    public function getDeleteDocPermission(): ?\stdClass
    {
        return $this->docPermission;
    }

    /** @return \stdClass|null */
    public function getDeleteDocStorage(): ?\stdClass
    {
        return $this->docStorage;
    }
}
