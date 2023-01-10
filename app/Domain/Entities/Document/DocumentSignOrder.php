<?php

declare(strict_types=1);
namespace App\Domain\Entities\Document;

class DocumentSignOrder
{
    /** @var \stdClass|null */
    private ?\stdClass $signDoc;
    private ?\stdClass $nextSignUser;
    private ?\stdClass $issueUser;

    /**
     * @param \stdClass|null $signDocument
     * @param \stdClass|null $nextSignUser
     * @param \stdClass|null $isseuUser
     */
    public function __construct(
        ?\stdClass $signDocument = null,
        ?\stdClass $nextSignUser = null,
        ?\stdClass $issueUser    = null
    ) 
    {
        $this->signDoc      = $signDocument;
        $this->nextSignUser = $nextSignUser;
        $this->issueUser    = $issueUser;
    }

    /** @return \stdClass|null */
    public function getSignDoc(): ?\stdClass
    {
        return $this->signDoc;
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
