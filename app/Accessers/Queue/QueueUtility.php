<?php
declare(strict_types=1);
namespace App\Accessers\Queue;

use Illuminate\Support\Facades\Log;
use MicrosoftAzure\Storage\Queue\QueueRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Queue\Models\CreateQueueOptions;

class QueueUtility
{
    public const QUEUE_NAME_SENDMAIL = "sendmail";
    public const QUEUE_NAME_BULKVALIDATION = "bulkvalidation";
    public const QUEUE_NAME_SIGN = "sign";
    public const QUEUE_NAME_TIMESTAMP = "timestamp";
    public const QUEUE_NAME_DOCUMENTSAVE = "documentsave";
    public const QUEUE_NAME_DOCUMENTDELETE = "documentdelete";
    public const QUEUE_NAME_DLCSV = "dlcsv";
    public const QUEUE_NAME_DLPDF = "dlpdf";
    public const QUEUE_META_KEY_DEFAULT = "option";

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
        $queueEndpoint = env("AZURE_STORAGE_QUEUE_ENDPOINT");
        $connectionString = sprintf("DefaultEndpointsProtocol=http;AccountName=%s;AccountKey=%s;QueueEndpoint=%s", $accountName, $accountKey, $queueEndpoint);

        // Create queue REST proxy.
        $queueClient = QueueRestProxy::createQueueService($connectionString);

        // OPTIONAL: Set queue metadata.
        $createQueueOptions = new CreateQueueOptions();
        foreach ($param as $key => $value) {
            $createQueueOptions->addMetaData($key, $value);
        }

        try {
            // Create queue.
            // 同一名称でキューは登録できないが、一旦はBL設計書の名称通りとする。
            // (将来的に考慮が必要であれば、末尾にタイムスタンプ等を付与して対応
            // $microTime = str_replace('.', '-', (string)microtime(true));
            // $queueClient->createQueue($queName."-".$microTime, $createQueueOptions);
            $queueClient->createQueue($queName, $createQueueOptions);
        } catch(ServiceException $e) {
            // Handle exception based on error codes and messages.
            Log::error($e->getMessage());
            $ret = -1;
        }

        return $ret;
    }
}
