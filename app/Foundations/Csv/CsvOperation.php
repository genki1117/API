<?php
declare(strict_types=1);
namespace App\Foundations\Csv;

use App\Foundations\Csv\Foundations\CsvFileManager;
use App\Foundations\Csv\Foundations\CsvReadFileManager;
use App\Foundations\Csv\Foundations\CsvWriterFileManager;
use App\Foundations\Csv\Foundations\ReadCsv;

class CsvOperation
{
    public const CONVERT_SJIS_WIN_TO_UTF8 = 1;
    public const CONVERT_UTF8_TO_SJIS_WIN = 2;
    private const DEFAULT_OPTION_MAP = [
        'read_flag'   => 'full',
        'header_flag' => true
    ];

    /** @var CsvFileManager */
    private CsvFileManager $csvReadFileManager;
    /** @var CsvWriterFileManager */
    private CsvWriterFileManager $csvWriterFileManager;
    /** @var bool */
    private bool $isConvertUtf8    = false;
    /** @var bool */
    private bool $isConvertSjisWin = false;

    /**
     * @param CsvReadFileManager $csvReadFileManager
     * @param CsvWriterFileManager $csvWriterFileManager
     */
    public function __construct(
        CsvReadFileManager $csvReadFileManager,
        CsvWriterFileManager $csvWriterFileManager
    ) {
        $this->csvReadFileManager   = $csvReadFileManager;
        $this->csvWriterFileManager = $csvWriterFileManager;
    }

    /**
     * @param int $type
     * @return CsvOperation
     */
    public function changeCharacterCode(int $type): self
    {
        if ($type === self::CONVERT_SJIS_WIN_TO_UTF8) {
            $this->isConvertUtf8 = true;
        }
        if ($type === self::CONVERT_UTF8_TO_SJIS_WIN) {
            $this->isConvertSjisWin = true;
        }
        return $this;
    }

    /**
     * @param string $filePath
     * @param array $option
     * @return ReadCsv
     */
    public function read(string $filePath, array $option = []): ReadCsv
    {
        $option = array_merge(self::DEFAULT_OPTION_MAP, $option);

        $csvFileManager = $this->csvReadFileManager->setResource($filePath);
        $csvFileManager = $this->changeCharacterIfNeeded($csvFileManager);

        if ($option['read_flag'] === CsvReadFileManager::OPTION_READ_ONLY) {
            $csvFileManager->setReadCSVOnly();
        } else {
            $csvFileManager->setALLFlag();
        }

        return $csvFileManager->run($option['header_flag']);
    }

    /**
     * @param string $csvPath
     * @param array $contents
     * @return bool
     */
    public function write(string $csvPath, array $contents): bool
    {
        $csvFileManager = $this->csvWriterFileManager->setResource($csvPath);
        $csvFileManager = $this->changeCharacterIfNeeded($csvFileManager);

        return $csvFileManager->setOutputCsvData($contents)->run();
    }

    /**
     * @param CsvFileManager $csvFileManager
     * @return CsvFileManager
     */
    private function changeCharacterIfNeeded(CsvFileManager $csvFileManager): CsvFileManager
    {
        if ($this->isConvertUtf8) {
            $csvFileManager->toConvertUtf8();
        }
        if ($this->isConvertSjisWin) {
            $csvFileManager->toConvertSjisWin();
        }
        return $csvFileManager;
    }
}
