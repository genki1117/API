<?php
declare(strict_types=1);
namespace App\Foundations\Csv\Foundations;

use App\Foundations\Csv\Collection\ContentsCollection;
use App\Foundations\Csv\Collection\HeaderCollection;

class ReadCsv
{
    /** @var array */
    private array $header;
    /** @var array */
    private array $contents;

    /**
     * ReadCsv constructor.
     * @param array $header
     * @param array $contents
     */
    public function __construct(array $header, array $contents)
    {
        $this->header   = $header;
        $this->contents = $contents;
    }

    /**
     * @return HeaderCollection
     */
    public function getHeader(): HeaderCollection
    {
        return new HeaderCollection($this->header);
    }

    /**
     * @return ContentsCollection
     */
    public function getContents(): ContentsCollection
    {
        return new ContentsCollection($this->contents);
    }
}
