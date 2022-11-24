<?php
declare(strict_types=1);
namespace App\Foundations\Csv\Foundations;

use SplFileObject;

class CsvWriterFileManager extends CsvFileManager
{
    /** @var SplFileObject */
    protected SplFileObject $splFileObject;
    /** @var WriteCsv */
    protected WriteCsv $writeCsv;

    /**
     * @return string
     */
    public function setOpenMode():string
    {
        return 'w';
    }

    /**
     * @param array $contents
     * @return CsvWriterFileManager
     */
    public function setOutputCsvData(array $contents): self
    {
        $this->writeCsv = new WriteCsv($contents);
        return $this;
    }

    /**
     * @return bool
     */
    public function run(): bool
    {
        foreach ($this->writeCsv->getOutputCsvData() as $fields) {
            $convertedField = $this->convertEncordingIfNeeded($fields);
            if ($this->splFileObject->fputcsv($convertedField) === false) {
                return false;
            }
        }
        return true;
    }
}
