<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Batch;

use App\Accessers\DB\Document\DocumentArchive;
use App\Accessers\DB\Document\DocumentInternal;
use App\Accessers\DB\Document\DocumentDeal;
use App\Accessers\DB\Document\DocumentContract;
use App\Accessers\DB\TempToken;
use Illuminate\Support\Collection;
use App\Domain\Repositories\Interface\Batch\ExpiryDocumentUpdateInterface;


class ExpiryDocumentUpdateRepository implements ExpiryDocumentUpdateInterface

{
    /** @var $docArchive */
    private $docArchive;

    /** @var $docInternal */
    private $docInternal;

    /** @var $docDeal */
    private $docDeal;

    /** @var $docContract */
    private $docContract;

    /** @var $tempToken */
    private $tempToken;


    public function __construct(
            DocumentArchive $docArchive,
            DocumentInternal $docInternal,
            DocumentDeal $docDeal,
            DocumentContract $docContract,
            TempToken $tempToken
        )
    {   
        $this->docArchive  = $docArchive;
        $this->docInternal = $docInternal;
        $this->docDeal     = $docDeal;
        $this->docContract = $docContract;
        $this->tempToken   = $tempToken;
    }

    
    public function getExpiryTokenData(): collection
    {
        return  $this->tempToken->getExpiryToken();        
    }

    public function expriyUpdateContract($data)
    {
        $this->docContract->expiryUpdate($data);
    }
}
