<?php
declare(strict_types=1);
namespace App\Accessers\Queue;

use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\Pure;
use MicrosoftAzure\Storage\Queue\QueueRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Queue\Models\CreateMessageOptions;

class AddQueue
{
    /**
     * @param string $queName
     * @param array $param
     * @return int
     */
    public function createMessage(string $queName, array $param): int
    {
        $ret = 0;

        $accountName = env("AZURE_ACCOUNT_NAME");
        $accountKey = env("AZURE_ACCOUNT_KEY");
        $debug="UseDevelopmentStorage=true";
        $connectionString = sprintf("%s;DefaultEndpointsProtocol=http;AccountName=%s;AccountKey=%s", $debug, $accountName, $accountKey);

        // Create queue REST proxy.
        $queueClient = QueueRestProxy::createQueueService($connectionString);

        // OPTIONAL: Set queue metadata.
        $createQueueOptions = new CreateQueueOptions();
        foreach ($param as $key => $value) {
            $createQueueOptions->addMetaData($key, $value);
        }

        try {
            // Create queue.
            $queueClient->createQueue("myqueue", $createQueueOptions);
        } catch(ServiceException $e) {
            // Handle exception based on error codes and messages.
            Log::error($e->getMessage());
            $ret = -1;
        }

        return $ret;
    }
}
