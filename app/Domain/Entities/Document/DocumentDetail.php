<?php

declare(strict_types=1);
namespace App\Domain\Entities\Document;

class DocumentDetail
{
    /** @var \stdClass|null */
    private ?\stdClass $documentList;
    private ?\stdClass $documentPermissionList;
    private ?\stdClass $documentWorkFlow;
    private ?\stdClass $logDocAccess;
    private ?\stdClass $logDocOperation;
    private ?\stdClass $logSystemAccess;

    /**
     * @param \stdClass|null $documentType
     */
    public function __construct(?\stdClass $documentList = null, 
                                ?\stdClass $documentPermissionList = null, 
                                ?\stdClass $documentWorkFlow = null, 
                                ?\stdClass $logDocAccess = null, 
                                ?\stdClass $logDocOperation = null)
    {
        $this->documentList = $documentList;
        $this->documentPermissionList = $documentPermissionList;
        $this->documentWorkFlow = $documentWorkFlow;
        $this->logDocAccess = $logDocAccess;
        $this->logDocOperation = $logDocOperation;
    }

    /**
     * @return \stdClass|null
     */
    public function getDocumentList(): ?\stdClass
    {
        return $this->documentList;
    }

    /**
     * @return \stdClass|null
     */
    public function getDocumentPermissionList(): ?\stdClass
    {
        return $this->documentPermissionList;
    }

    /**
     * @return \stdClass|null
     */
    public function getDocumentWorkFlow(): ?\stdClass
    {
        return $this->documentWorkFlow;
    }

    /**
     * @return \stdClass|null
     */
    public function getLogDocAccess(): ?\stdClass
    {
        return $this->logDocAccess;
    }

    /**
     * @return \stdClass|null
     */
    public function getLogDocOperation(): ?\stdClass
    {
        return $this->logDocOperation;
    }

    /**
     * @return \stdClass|null
     */
    public function getLogSystemAccess(): ?\stdClass
    {
        return $this->logSystemAccess;
    }

}
