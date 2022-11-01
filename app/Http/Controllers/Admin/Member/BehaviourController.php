<?php
// +----------------------------------------------------------------------
// | B5LaravelCMF V2.0 [快捷通用基础管理开发平台]
// +----------------------------------------------------------------------
// | Author: z <834574377@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace App\Http\Controllers\Admin\Member;

use App\Extends\Libs\AdminCommonAction;
use App\Models\Behaviour;

class BehaviourController extends Member
{
    use AdminCommonAction;
    protected string $model = Behaviour::class;

    /**
     * 首页列表处理
     * @param array $list
     * @return array
     */
    protected function indexAfter(array $list): array
    {

        foreach ($list as $key => $value) {
            $list[$key]['address']=$value['country'].$value['region'].$value['city'];
            $list[$key]['uid']=$value['uid']?:'游客';
        }
        return $list;
    }
}
