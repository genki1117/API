<?php
declare(strict_types=1);
namespace App\Accessers\Queue;

use Illuminate\Support\Facades\Log;
use MicrosoftAzure\Storage\Queue\QueueRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Queue\Models\CreateQueueOptions;

class QueueUtility
{
    /**
     * Azure Queue storageのキューへメッセージを追加する
     * @param string $queName
     * @param array $param
     * @return int
     */
    public function createMessage(string $queName, string $param): int
    {
        $ret = 0;

        $connectionString = $this->getConnectionString();

        // Create queue REST proxy.
        $queueClient = QueueRestProxy::createQueueService($connectionString);

        try {
            // Create Message.
            $queueClient->createMessage($queName, $param);
        } catch(ServiceException $e) {
            // Handle exception based on error codes and messages.
            Log::error($e->getMessage());
            $ret = -1;
        }

        return $ret;
    }

    /**
     * Azure Queue storageへキューを作成する
     * @param string $queName
     * @param array $param
     * @return int
     */
    public function createQueue(string $queName, array $param): int
    {
        $ret = 0;

        $connectionString = $this->getConnectionString();

        // Create queue REST proxy.
        $queueClient = QueueRestProxy::createQueueService($connectionString);

        // OPTIONAL: Set queue metadata.
        $createQueueOptions = new CreateQueueOptions();
        foreach ($param as $key => $value) {
            $createQueueOptions->addMetaData($key, $value);
        }

        try {
            // Create queue.
            $queueClient->createQueue($queName, $createQueueOptions);
        } catch(ServiceException $e) {
            // Handle exception based on error codes and messages.
            Log::error($e->getMessage());
            $ret = -1;
        }

        return $ret;
    }

    /**
     * Azure Queue storageへの接続文字列を取得する
     *
     * @return string
     */
    private function getConnectionString(): string
    {
        $accountName = config('app.azure_queue_account_name');
        $accountKey = config('app.azure_queue_account_key');
        $queueEndpoint = config("app.azure_queue_endpoint");
        $connectionString = sprintf("DefaultEndpointsProtocol=http;AccountName=%s;AccountKey=%s;QueueEndpoint=%s", $accountName, $accountKey, $queueEndpoint);

        return $connectionString;
    }
}
