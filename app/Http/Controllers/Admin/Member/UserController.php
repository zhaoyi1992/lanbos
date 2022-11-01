<?php
// +----------------------------------------------------------------------
// | B5LaravelCMF V2.0 [快捷通用基础管理开发平台]
// +----------------------------------------------------------------------
// | Author: z <834574377@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace App\Http\Controllers\Admin\Member;

use App\Extends\Libs\AdminCommonAction;
use App\Models\Member as Membertb;

class UserController extends Member
{
    use AdminCommonAction;
    protected string $model = Membertb::class;

    /**
     * 首页列表处理
     * @param array $list
     * @return array
     */
    protected function indexAfter(array $list): array
    {


        foreach ($list as $key => $value) {
            $list[$key]['appidvalue']=config('app.appid')[$value['appid']];

        }
        return $list;
    }
}
