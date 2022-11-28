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
    public function getToken(string $token, string $expiryDate = null): ?\stdClass
    {
        return $this->builder()
            ->select([
                "token",
                "type",
                "data",
                "expiry_date",
            ])
            ->where("token", $token)
            ->when($expiryDate, function ($query) use ($expiryDate) {
                return $query->whereDate('expiry_date', '>=', $expiryDate);
            })
            ->first();
    }
}
