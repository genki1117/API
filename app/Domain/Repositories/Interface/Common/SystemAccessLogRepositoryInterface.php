<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Interface\Common;

use App\Foundations\Context\LoggedInUserContext;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface SystemAccessLogRepositoryInterface
{
    /**
     * @param Request $request
     * @param JsonResponse $response
     * @param LoggedInUserContext $context
     * @return int
     */
    public function outputAccessLog(Request $request, JsonResponse $response, LoggedInUserContext $context): bool;
}
