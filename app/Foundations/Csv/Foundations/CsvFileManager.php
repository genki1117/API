<?php
declare(strict_types=1);
namespace App\Foundations\Csv\Foundations;

use SplFileObject;

abstract class CsvFileManager
{
    /** @var bool */
    private bool $isConvertUtf8    = false;
    /** @var bool */
    private bool $isConvertSjisWin = false;
    /** @var MbConverter */
    protected MbConverter $mbConverter;
    /** @var SplFileObject */
    protected SplFileObject $splFileObject;

    /**
     * CsvWriterFileManager constructor.
     * @param MbConverter $mbConverter
     */
    public function __construct(MbConverter $mbConverter)
    {
        $this->mbConverter = $mbConverter;
    }

    abstract public function setOpenMode(): string;

    /**
     * @param string $resource
     * @return CsvFileManager
     */
    public function setResource(string $resource): self
    {
        $this->splFileObject = new SplFileObject($resource, $this->setOpenMode());
        return $this;
    }

    /**
     * @return CsvFileManager
     */
    public function toConvertSjisWin(): self
    {
        $this->isConvertSjisWin = true;
        return $this;
    }

    /**
     * @return CsvFileManager
     */
    public function toConvertUtf8(): self
    {
        $this->isConvertUtf8 = true;
        return $this;
    }

    /**
     * @param array $fields
     * @return array
     */
    protected function convertEncordingIfNeeded(array $fields): array
    {
        if ($this->isConvertSjisWin) {
            return $this->mbConverter
                ->setData($fields)
                ->convertUtf8ToSjisWin()
                ->getConvertedData();
        }

        if ($this->isConvertUtf8) {
            return $this->mbConverter
                ->setData($fields)
                ->convertSjisWinToUtf8()
                ->getConvertedData();
        }
        return $fields;
    }
}
