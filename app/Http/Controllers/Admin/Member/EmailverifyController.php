<?php
// +----------------------------------------------------------------------
// | B5LaravelCMF V2.0 [快捷通用基础管理开发平台]
// +----------------------------------------------------------------------
// | Author: z <834574377@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace App\Http\Controllers\Admin\Member;
use App\Extends\Libs\AdminCommonAction;
use App\Models\Emailverify;

class EmailverifyController extends Member
{
    use AdminCommonAction;
    protected string $model = Emailverify::class;

    /**
     * 首页列表处理
     * @param array $list
     * @return array
     */
    protected function indexAfter(array $list): array
    {

        foreach ($list as $key => $value) {
            $list[$key]['type']=$value['type']==1?'修改':'注册';
            $list[$key]['created_at']=date('Y-m-d H:i:s', (int)$value['created_at']);
        }
        return $list;
    }
}
