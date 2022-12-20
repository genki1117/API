<?php
declare(strict_types=1);
namespace App\Domain\Entities\Document;

/**
 * 印鑑情報
 */
class Sign
{
    private ?int $userId;
    private ?string $xAxis; //TODO 処理内部ではstringで扱い、返却時にintに変換
    private ?string $yAxis; //TODO 処理内部ではstringで扱い、返却時にintに変換

    /**
     * @param int|null $userId
     * @param string|null $xAxis
     * @param string|null $yAxis
     */
    public function __construct(?int $userId, ?string $xAxis, ?string $yAxis)
    {
        $this->userId = $userId;
        $this->xAxis = $xAxis;
        $this->yAxis = $yAxis;
    }

    /**
     * ユーザID
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * X座標
     * @return string|null
     */
    public function getXAxis(): ?string
    {
        return $this->xAxis;
    }

    /**
     * Y座標
     * @return string|null
     */
    public function getYAxis(): ?string
    {
        return $this->yAxis;
    }
}
