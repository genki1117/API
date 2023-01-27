<?php

namespace Tests\Unit\Service\Document;

use Exception;
use ReflectionClass;
use App\Domain\Services\Document\DocumentDownloadCsvService;
use PHPUnit\Framework\TestCase;

class DocumentDownloadCsvTest extends TestCase
{
    public function getObject()
    {
        return new DocumentDownloadCsvService();
    }
}