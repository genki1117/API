<?php
declare(strict_types=1);
namespace App\Console\Commands\Document;

use App\Domain\Services\Batch\ExpiryDocumentUpdateBatch;
use Illuminate\Console\Command;

class ExpiryDocumentUpdateCommand extends Command
{
    /**  */
    private ExpiryDocumentUpdateBatch $batchService;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:expiryDcoumentUpdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command expiryDcoumentUpdate';


    public function __construct(ExpiryDocumentUpdateBatch $batchService)
    {  
        parent::__construct();
        $this->batchService = $batchService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->batchService->expiryDcoumentUpdate();
    }
}
