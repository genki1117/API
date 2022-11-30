<?php

namespace App\Http\Middleware;

use App\Domain\Repositories\Interface\Common\SystemAccessLogRepositoryInterface;
use App\Foundations\Context\LoggedInUserContext;
use Closure;
use Illuminate\Http\Request;

class CommonProcess
{
    /** @var LoggedInUserContext */
    private LoggedInUserContext $loggedInUserContext;
    /** @var LoginUserRepositoryInterface */
    private SystemAccessLogRepositoryInterface $systemAccessLogRepositoryInterface;

    /**
     * @param LoginUserRepositoryInterface $loginUserRepositoryInterface
     */
    public function __construct(
        LoggedInUserContext $context,
        SystemAccessLogRepositoryInterface $systemAccessLogRepositoryInterface
    )
    {
        $this->loggedInUserContext = $context;
        $this->systemAccessLogRepositoryInterface = $systemAccessLogRepositoryInterface;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $this->systemAccessLogRepositoryInterface->outputAccessLog($request, $response, $this->loggedInUserContext);
        return $response;
    }
}
