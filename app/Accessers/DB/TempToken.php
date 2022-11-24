<?php
declare(strict_types=1);
namespace App\Accessers\DB;

use App\Accessers\DB\FluentDatabase;

class TempToken extends FluentDatabase
{
    protected string $table = "temp_token";

    /**
     * @param string $mailAddress
     * @return \stdClass|null
     */
    public function getToken(string $token, string $expiry_date = null): ?\stdClass
    {
        return $this->builder()
            ->select([
                "token",
                "type",
                "data",
                "expiry_date",
            ])
            ->where("token", $token)
            ->when($expiry_date, function ($query) use ($expiry_date) {
                return $query->whereDate('expiry_date', '>=', $expiry_date);
            })
            ->first();
    }
}
