<?php
declare(strict_types=1);
namespace App\Http\Responses;

use App\Domain\Entities\Document\ChangeContent;
use App\Domain\Entities\Organization\Group;
use App\Domain\Entities\Organization\User\AccessUser;
use App\Domain\Entities\Organization\User\OperationUser;
use App\Domain\Entities\Organization\User\SelectSignGuestUser;
use App\Domain\Entities\Organization\User\SelectSignUser;
use App\Domain\Entities\Organization\User\SelectViewUser;
use Carbon\Carbon;

/**
 * システム共通レスポンスボディ生成関数郡
 */
trait SystemResponseFunc
{
    /**
     * Carbonクラスから文字列の日付情報に変換
     * @param Carbon|null $date
     * @param string $format
     * @return string|null
     */
    private function convertCarbonToString(?Carbon $date, string $format = 'Y-m-d'): ?string
    {
        if (is_null($date)) {
            return null;
        }
        return $date->format($format);
    }

    /**
     * 選択署名者（ホスト）のレスポンスボディ生成
     * @param array $selectSignUser
     * @return array
     */
    private function getSelectSignUserResponseBody(array $selectSignUser): array
    {
        if (empty($selectSignUser)) {
            return [];
        }

        return array_map(callback: function (SelectSignUser $user) {
            return [
                'group_array' => !empty($user->getGroups()) ? array_map(callback: function (Group $group) {
                    return [
                        'group_id' => $group->getGroupId()
                    ];
                }, array: $user->getGroups()): [],
                'user_id' => $user->getUserId(),
                'family_name' => $user->getFamilyName(),
                'first_name' => $user->getFirstName(),
                'email' => $user->getEmail(),
                'wf_sort' => $user->getWfSort(),
            ];
        }, array: $selectSignUser);
    }

    /**
     * 選択署名者（ゲスト）のレスポンスボディ生成
     * @param array $selectSignGuestUser
     * @return array
     */
    private function getSelectSignGuestUserResponseBody(array $selectSignGuestUser): array
    {
        if (empty($selectSignGuestUser)) {
            return [];
        }

        return array_map(callback: function (SelectSignGuestUser $user) {
            return [
                'counter_party_id' => $user->getCounterPartyId(),
                'counter_party_name' => $user->getCounterPartyName(),
                'group_array' => !empty($user->getGroups()) ? array_map(callback: function (Group $group) {
                    return [
                        'group_name' => $group->getGroupName()
                    ];
                }, array: $user->getGroups()): [],
                'user_id' => $user->getUserId(),
                'family_name' => $user->getFamilyName(),
                'first_name' => $user->getFirstName(),
                'email' => $user->getEmail(),
                'wf_sort' => $user->getWfSort(),
            ];
        }, array: $selectSignGuestUser);
    }

    /**
     * 選択署名者（閲覧者）のレスポンスボディを生成
     * @param array $selectViewUser
     * @return array
     */
    private function getSelectViewUserResponseBody(array $selectViewUser): array
    {
        if (empty($selectViewUser)) {
            return [];
        }

        return array_map(callback: function (SelectViewUser $user) {
            return [
                'group_array' => !empty($user->getGroups()) ? array_map(callback: function (Group $group) {
                    return [
                        'group_id' => $group->getGroupId()
                    ];
                }, array: $user->getGroups()) : [],
                'user_id' => $user->getUserId(),
                'family_name' => $user->getFamilyName(),
                'first_name' => $user->getFirstName(),
                'email' => $user->getEmail(),
            ];
        }, array: $selectViewUser);
    }

    /**
     * 変更履歴情報のレスポンスボディを生成
     * @param array $operationData
     * @return array
     */
    private function getOperationDataResponseBody(array $operationData): array
    {
        if (empty($operationData)) {
            return [];
        }

        return array_map(callback: function (OperationUser $user) {
            return [
                'create_datetime' => $user->getCreateDatetime(),
                'family_name' => $user->getFamilyName(),
                'first_name' => $user->getFirstName(),
                'content' => !empty($user->getContent()) ? array_map(callback: function (ChangeContent $content) {
                    return [
                        'before' => $content->getBeforeContent(),
                        'after' => $content->getAfterContent(),
                    ];
                }, array: $user->getContent()) : [],
            ];
        }, array: $operationData);
    }

    /**
     * アクセス履歴情報のレスポンスボディを生成
     * @param array $accessData
     * @return array
     */
    private function getAccessDataResponseBody(array $accessData): array
    {
        if (empty($accessData)) {
            return [];
        }

        return array_map(callback: function (AccessUser $user) {
            return [
                'create_datetime' => $user->getCreateDatetime(),
                'family_name' => $user->getFamilyName(),
                'first_name' => $user->getFirstName(),
            ];
        }, array: $accessData);
    }
}
