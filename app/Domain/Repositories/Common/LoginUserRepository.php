<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Common;

use App\Accessers\DB\Master\MUser;
use App\Accessers\DB\Master\MUserRole;
use App\Accessers\DB\TempToken;
use App\Domain\Entities\Common\User as UserEntity;
use App\Domain\Entities\Common\TempToken as TempTokenEntity;
use App\Domain\Repositories\Interface\Common\LoginUserRepositoryInterface;

class LoginUserRepository implements LoginUserRepositoryInterface
{
    /**
     * @var MUser
     */
    private MUser $mUser;

    /**
     * @var MUserRole
     */
    private MUserRole $mUserRole;

    /**
     * @var TempToken
     */
    private TempToken $tempToken;

    /**
     * @param User $user
     */
    public function __construct(TempToken $tempToken, MUser $mUser, MUserRole $mUserRole)
    {
        $this->tempToken = $tempToken;
        $this->mUser = $mUser;
        $this->mUserRole = $mUserRole;
    }

    /**
     * @param string $token
     * @return TempTokenEntity
     */
    public function getToken(string $token, string $expiry_date = null): TempTokenEntity
    {
        $tempToken = $this->tempToken->getToken($token, $expiry_date);

        if (empty($tempToken)) {
            return new TempTokenEntity();
        }

        return new TempTokenEntity($tempToken->token, $tempToken->type, $tempToken->data, $tempToken->expiry_date);
    }

    /**
     * @param string $compnay_id
     * @param string $user_id
     * @return UserEntity
     */
    public function getUser(string $compnay_id, string $user_id): UserEntity
    {
        $user = $this->mUser->getUser($compnay_id, $user_id);
        $userRole = $this->mUserRole->getUserRole($compnay_id, $user_id);

        if (empty($user)) {
            return new UserEntity();
        }

        return new UserEntity($user, $userRole);
    }

    /**
     * @param string $requestUri
     * @param UserEntity $user
     * @return bool
     */
    public function checkAuth(string $requestUri, UserEntity $user): bool
    {
        $ret = false;
        $auth = config('aut_list')[$requestUri];
        if ($auth === null) {
            $ret = false;
        } elseif ($user->getUser()->user_type_id == 1) {
            $ret = $auth['guest_user_role'];
        } elseif ($user->getUser()->user_type_id == 99) {
            $ret = $auth['system_administrator_role'];
        } else {
            switch(true) {
                case $user->getUserRole()->{"admin_role"}:
                    $ret = $auth['reader_role']['admin_role'];
                    break;
                case $user->getUserRole()->{"template_set_role"}:
                    $ret = $auth['reader_role']['template_set_role'];
                    break;
                case $user->getUserRole()->{"workflow_set_role"}:
                    $ret = $auth['reader_role']['workflow_set_role'];
                    break;
                case $user->getUserRole()->{"master_set_role"}:
                    $ret = $auth['reader_role']['workflow_set_role'];
                    break;
                case $user->getUserRole()->{"archive_func_role"}:
                    $ret = $auth['reader_role']['archive_func_role'];
                    break;
                case $user->getUserRole()->{"ts_role"}:
                    $ret = $auth['reader_role']['ts_role'];
                    break;
                case $user->getUserRole()->{"bulk_ts_role"}:
                    $ret = $auth['reader_role']['bulk_ts_role'];
                    break;
                case $user->getUserRole()->{"cont_doc_app_role"}:
                    $ret = $auth['reader_role']['cont_doc_app_role'];
                    break;
                case $user->getUserRole()->{"deal_doc_app_role"}:
                    $ret = $auth['reader_role']['deal_doc_app_role'];
                    break;
                case $user->getUserRole()->{"int_doc_app_role"}:
                    $ret = $auth['reader_role']['int_doc_app_role'];
                    break;
                case $user->getUserRole()->{"arch_doc_app_role"}:
                    $ret = $auth['reader_role']['arch_doc_app_role'];
                    break;
                case $user->getUserRole()->{"use_cert_role"}:
                    $ret = $auth['reader_role']['use_cert_role'];
                    break;
                default:
                    $ret = false;
            }
        }
        return $ret;
    }
}
