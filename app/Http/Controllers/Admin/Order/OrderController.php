<?php
// +----------------------------------------------------------------------
// | B5LaravelCMF V2.0 [快捷通用基础管理开发平台]
// +----------------------------------------------------------------------
// | Author: z <834574377@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace App\Http\Controllers\Admin\Order;

use App\Extends\Libs\AdminCommonAction;
use App\Models\Professional\Order as Ordertable;

class OrderController extends Order
{
    use AdminCommonAction;
    protected string $model = Ordertable::class;

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
