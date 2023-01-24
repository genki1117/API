<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Interface\Batch;

use Illuminate\Support\Collection;
use App\Domain\Entities\Common\TempToken;
use App\Domain\Entities\Users\User;

interface ExpiryDocumentUpdateInterface
{
    public function getExpiryTokenData(): collection;

    public function expriyUpdateContract($data);
}
