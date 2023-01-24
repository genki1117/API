<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Batch;

use App\Domain\Repositories\Interface\Batch\ExpiryDocumentUpdateInterface;
use App\Accessers\DB\TempToken;

class ExpiryDocumentUpdateRepository implements ExpiryDocumentUpdateInterface

{
    /** @var $tempToken */
    private $tempToken;

    public function __construct(TempToken $tempToken)
    {   
        $this->tempToken = $tempToken;
    }

    public function getExpiryTokenData()
    {
        return  $this->tempToken->getExpiryToken();        
    }
}
