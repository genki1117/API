<?php
declare(strict_types=1);
namespace App\Accessers\DB;

use Carbon\CarbonImmutable;
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

    public function getTokenData(string $token): ?\stdClass
    {
        return $this->builder()
            ->select([
                "data",
                "expiry_date"
            ])
            ->where("token", $token)
            ->where('expiry_date', '>=', CarbonImmutable::now())
            ->whereNull('delete_datetime')
            ->first();
    }

}
