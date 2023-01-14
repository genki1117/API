<?php
declare(strict_types=1);
namespace App\Libraries;

use Carbon\Carbon;

/**
 * 時間操作をする関数群
 */
trait TimeFunc
{
    /**
     * Carbonクラスから文字列の日付情報に変換
     * @param Carbon|null $date
     * @param string $format
     * @return string|null
     */
    private function convertCarbonToString(?Carbon $date, string $format = 'Y-m-d'): ?string
    {
        if (is_null($date)) {
            return null;
        }
        return $date->format($format);
    }
}
