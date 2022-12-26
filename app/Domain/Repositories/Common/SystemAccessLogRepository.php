<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Common;

use App\Accessers\DB\Log\System\LogSystemAccess;
use App\Domain\Repositories\Interface\Common\SystemAccessLogRepositoryInterface;
use App\Foundations\Context\LoggedInUserContext;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SystemAccessLogRepository implements SystemAccessLogRepositoryInterface
{
    /**
     * @var LogSystemAccess
     */
    private LogSystemAccess $logSystemAccess;

    /**
     * @param LogSystemAccess $logSystemAccess
     */
    public function __construct(LogSystemAccess $logSystemAccess)
    {
        $this->logSystemAccess = $logSystemAccess;
    }

    /**
     * @param Request $request
     * @param JsonResponse $response
     * @param LoggedInUserContext $context
     * @return int
     */
    public function outputAccessLog(Request $request, JsonResponse $response, LoggedInUserContext $context): bool
    {
        $ret = 0;

        $user = null;
        if (!empty($context->get())) {
            $user = $context->get()->getUser();
        }
        if (!empty($user)) {
            $companyId = $user->company_id;
            $userId = $user->user_id;
            $fullName = $user->full_name;
        } else {
            //TODO ユーザなしの場合は、下記の内容でよいか？
            $companyId = -1;
            $userId = -1;
            $fullName = "";
        }
        $ipAddress = $request->ip();
        $accessFuncName = $request->getPathInfo();
        $action = [
            'request' => $request->all(),
            'response' => $response->original,
        ];

        // システムアクセスログを出力
        $ret = $this->logSystemAccess->insert($companyId, $userId, $fullName, $ipAddress, $accessFuncName, $action);

        return $ret;
    }
}
