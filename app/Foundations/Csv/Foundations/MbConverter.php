<?php
declare(strict_types=1);
namespace App\Foundations\Csv\Foundations;

class MbConverter
{
    private const CHARACTER_CODE_SJIS_WIN = 'sjis-win';
    private const CHARACTER_CODE_UTF_8    = 'UTF-8';

    /** @var array */
    private array $convertData;

    /**
     * MbConverter constructor.
     * @param array $convertData
     */
    public function __construct(array $convertData = [])
    {
        $this->convertData = $convertData;
    }

    /**
     * @param array $convertData
     * @return $this
     */
    public function setData(array $convertData): self
    {
        $this->convertData = $convertData;
        return $this;
    }

    /**
     * convert sjis-win to utf8
     * @return MbConverter
     */
    public function convertSjisWinToUtf8(): self
    {
        mb_convert_variables(self::CHARACTER_CODE_UTF_8, self::CHARACTER_CODE_SJIS_WIN, $this->convertData);
        return $this;
    }

    /**
     * convert utf8 to sjis-win
     * @return MbConverter
     */
    public function convertUtf8ToSjisWin(): self
    {
        mb_convert_variables(self::CHARACTER_CODE_SJIS_WIN, self::CHARACTER_CODE_UTF_8, $this->convertData);
        return $this;
    }

    /**
     * @return array
     */
    public function getConvertedData(): array
    {
        return $this->convertData;
    }
}
