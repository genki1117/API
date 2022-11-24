<?php
declare(strict_types=1);
namespace App\Http\Middleware;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;

class OneTimeCsrfToken
{
    public const SUFFIX_CACHE_TOKEN = '_cache_token';
    public const SUFFIX_INPUT_TOKEN = '_input_token';

    /** @var Application */
    protected Application $app;

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }

    /**
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next): mixed
    {
        $request->session()->regenerateToken();
        $key = $request->session()->getId().self::SUFFIX_CACHE_TOKEN;
        $inputKey = $request->session()->getId().self::SUFFIX_INPUT_TOKEN;

        if (!$this->isUpdateRequestMethod($request->method())) {
            $this->app['cache']->forget($key);
            // 画面に表示されるトークンを保持
            $this->app['cache']->put($inputKey, $request->session()->token(), config('app.csrfTimeLimit'));
        } else {
            // 更新時の初回のみキャッシュにリフレッシュしたトークンを載せる
            $cacheToken = $this->app['cache']->get($key);
            if (is_null($cacheToken)) {
                $this->app['cache']->put($key, $request->session()->token(), config('app.csrfTimeLimit'));
            }
        }

        return $next($request);
    }

    /**
     * @param string $method
     * @return bool
     */
    private function isUpdateRequestMethod(string $method): bool
    {
        return $method === 'POST' || $method === 'PUT' || $method === 'DELETE' || $method === 'PATCH';
    }
}
