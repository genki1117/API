<?php
declare(strict_types=1);
namespace App\Libraries;

use Illuminate\Http\Request;

/**
 * クライアント情報関連の関数群
 */
trait RequestClient
{
    /**
     * クライアントのIPアドレスを返却
     *
     * @return string|null
     */
    private function getClientIp(): ?string
    {
        return app()->make(Request::class)->ip();
    }
}
