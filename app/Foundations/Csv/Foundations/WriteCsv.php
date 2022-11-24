<?php
declare(strict_types=1);
namespace App\Foundations\Csv\Foundations;

use App\Foundations\Csv\Exceptions\ArrayConstructionException;

class WriteCsv
{
    /** @var array */
    private array $csvData;

    /**
     * WriteCsv constructor.
     * @param array $csvData
     */
    public function __construct(array $csvData)
    {
        $this->csvData = $csvData;
    }

    /**
     * @return array
     */
    public function getOutputCsvData(): array
    {
        $this->validCsvData();
        return $this->csvData;
    }

    /**
     * @throws ArrayConstructionException
     */
    private function validCsvData(): void
    {
        foreach ($this->csvData as $content) {
            if (!is_array($content)) {
                throw new ArrayConstructionException();
            }
            foreach ($content as $value) {
                if (is_array($value)) {
                    throw new ArrayConstructionException();
                }
            }
        }
    }
}
