<?php
declare(strict_types=1);
namespace App\Foundations\Csv\Foundations;

use SplFileObject;

class CsvReadFileManager extends CsvFileManager
{
    const OPTION_READ_ONLY = 'read';
    const OPTION_FULL      = 'full';

    /** @var SplFileObject */
    protected SplFileObject $splFileObject;

    /**
     * @return string
     */
    public function setOpenMode(): string
    {
        return 'r';
    }

    /**
     * @return $this
     */
    public function setReadCSVOnly(): self
    {
        $this->splFileObject->setFlags(
            SplFileObject::READ_CSV
        );
        return $this;
    }

    /**
     * @return $this
     */
    public function setALLFlag(): self
    {
        $this->splFileObject->setFlags(
            SplFileObject::READ_CSV
            | SplFileObject::READ_AHEAD
            | SplFileObject::SKIP_EMPTY
            | SplFileObject::DROP_NEW_LINE
        );
        return $this;
    }

    /**
     * @param bool $isHeader
     * @return ReadCsv
     */
    public function run(bool $isHeader): ReadCsv
    {
        $header   = [];
        $contents = [];
        foreach ($this->splFileObject as $key => $line) {
            $convertedData = $this->convertEncordingIfNeeded($line);
            if ($isHeader && $key === 0) {
                $header = $convertedData;
                continue;
            }
            $contents[] = $convertedData;
        }
        return new ReadCsv($header, $contents);
    }
}
