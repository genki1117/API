<?php

declare(strict_types=1);
namespace App\Domain\Entities\Document;

class DocumentSaveOrder
{
    /** @var \stdClass|null */
    private ?\stdClass $nextSignUser;
    private ?\stdClass $issueUser;

    /**
     * @param \stdClass|null $nextSignUser
     * @param \stdClass|null $isseuUser
     */
    public function __construct(
        ?\stdClass $nextSignUser = null,
        ?\stdClass $issueUser    = null,
    ) 
    {
        $this->nextSignUser = $nextSignUser;
        $this->issueUser    = $issueUser;
    }

    /** @return \stdClass|null */
    public function getNextSignUser(): ?\stdClass
    {
        return $this->nextSignUser;
    }

    /** @return \stdClass|null */
    public function getIssueUser(): ?\stdClass
    {
        return $this->issueUser;
    }

}
