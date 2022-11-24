<?php
declare(strict_types=1);
namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Http\Request;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * @param Request $request
     * @return bool
     */
    protected function tokensMatch($request): bool
    {
        $key = $request->session()->getId().OneTimeCsrfToken::SUFFIX_CACHE_TOKEN;
        $inputKey = $request->session()->getId().OneTimeCsrfToken::SUFFIX_INPUT_TOKEN;

        $cacheToken = $this->app['cache']->get($key);
        $inputToken = $this->app['cache']->get($inputKey);

        $token = $this->getTokenFromRequest($request);

        if ($cacheToken === $request->session()->token() && $token === $inputToken) {
            // フォーム画面からきたトークンと同じトークンか判定し、同じ場合はチェックを通過させる
            $request->session()->put('_token', $token);
        }

        return parent::tokensMatch($request);
    }
}
