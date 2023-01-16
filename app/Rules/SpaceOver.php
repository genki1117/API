<?php
declare(strict_types=1);
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SpaceOver implements Rule
{
    /**
     * バリデーションの成功を判定
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $ret = false;
        $parts = explode("　", $value);
        $count = count($parts);
        if ($count > 0) {
            if (!empty($parts[0]) && $count === 2) {
                $ret = true;
            }
        }
        return $ret;
    }

    /**
     * バリデーションエラーメッセージの取得
     *
     * @return string
     */
    public function message()
    {
        return 'error.message.space.over';
    }
}
