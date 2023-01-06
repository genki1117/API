<?php

declare(strict_types=1);
namespace App\Domain\Entities\Document;

class DocumentSaveOrder
{
    private $loginUserWorkFlow;

    public function __counstuct($loginUserWorkFlow)
    {
        $this->loginUserWorkFlow = $loginUserWorkFlow;
    }

    public function getLoginUserWorkFlow()
    {
       return $this->loginUserWorkFlow;
    }
}
