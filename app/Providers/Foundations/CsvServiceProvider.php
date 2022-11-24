<?php
declare(strict_types=1);
namespace App\Providers\Foundations;

use App\Foundations\Csv\CsvOperation;
use App\Foundations\Csv\Foundations\CsvReadFileManager;
use App\Foundations\Csv\Foundations\CsvWriterFileManager;
use App\Foundations\Csv\Foundations\MbConverter;
use Illuminate\Support\ServiceProvider;

class CsvServiceProvider extends ServiceProvider
{
    /** @var bool */
    protected bool $defer = true;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(CsvOperation::class, function () {
            $mbConverter = new MbConverter();
            return new CsvOperation(
                new CsvReadFileManager($mbConverter),
                new CsvWriterFileManager($mbConverter)
            );
        });
    }

    /**
     * @return string[]
     */
    public function provides(): array
    {
        return [CsvOperation::class];
    }
}
