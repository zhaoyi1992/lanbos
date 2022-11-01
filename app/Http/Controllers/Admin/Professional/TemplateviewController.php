<?php
// +----------------------------------------------------------------------
// | B5LaravelCMF V2.0 [快捷通用基础管理开发平台]
// +----------------------------------------------------------------------
// | Author: z <834574377@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace App\Http\Controllers\Admin\Professional;

use App\Extends\Libs\AdminCommonAction;
use App\Models\Professional\Templatetype;
use App\Models\Professional\Templateview;
use Illuminate\Contracts\View\View;

class TemplateviewController extends Professional
{
    use AdminCommonAction;
    protected string $model = Templateview::class;


    /**
     * 首页列表处理
     * @param array $list
     * @return array
     */
    protected function indexAfter(array $list): array
    {
        $type = Templatetype::where(['status' => 1])->pluck('name','id')->toArray();

        foreach ($list as $key => $value) {
            $list[$key]['typevalue'] = $type[$value['typeid']];
        }
        return $list;

    }
    /**
     * 编辑页渲染
     * @param array $info
     * @return View
     */
    protected function editRender(array $info): View
    {

        if ($info['typeid']) {
            $type = Templatetype::where(['id' => $info['typeid']])->first();

            $info['typename'] = $type->name;
        } else {
            $info['typename'] = '顶级类';
        }

        return $this->render('', ['info' => $info]);
    }

    /**
     * 添加和编辑保存前 处理 父级信息
     * @param array $data
     * @param string $type
     * @return array|string
     */
    protected function saveBefore(array $data, string $type): array|string
    {

        if ($type == 'add' || $type == 'edit') {
            $parent_id = $data['typeid'];
            if ($parent_id) {
                $parentInfo = Templatetype::bFind($parent_id);
                if (!$parentInfo) {
                    return '模板分类不存在';
                }

            }
        }

        return $data;
    }

}
