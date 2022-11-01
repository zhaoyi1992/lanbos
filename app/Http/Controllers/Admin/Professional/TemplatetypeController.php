<?php
// +----------------------------------------------------------------------
// | B5LaravelCMF V2.0 [快捷通用基础管理开发平台]
// +----------------------------------------------------------------------
// | Author: z <834574377@qq.com>
// +----------------------------------------------------------------------
declare (strict_types=1);

namespace App\Http\Controllers\Admin\Professional;

use App\Extends\Helpers\Result;
use App\Extends\Libs\AdminCommonAction;
use App\Extends\Services\System\AdminStructService;
use App\Extends\Services\System\RoleStructService;
use App\Extends\Services\System\StructService;
use App\Models\Professional\Templatetype;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class TemplatetypeController extends Professional
{
    use AdminCommonAction;

    protected string $model = Templatetype::class;

    /**
     * 首页渲染
     * @return View
     */
    protected function indexRender(): View
    {
        $root_id = config('b5net.root_struct_id');
        return $this->render('', ['root_id' => $root_id]);
    }

    /**
     * 查询列表前
     * @param array $params
     * @return array
     */
    protected function indexBefore(array $params): array
    {
        $params['orderBy'] = ['parent_id' => 'asc', 'listsort' => 'asc'];
        return $params;
    }


    /**
     * 编辑页渲染
     * @param array $info
     * @return View
     */
    protected function editRender(array $info): View
    {
        if ($info['parent_id']) {
            $info['parent_name'] = implode('-', explode(',', $info['parent_name']));
        } else {
            $info['parent_name'] = '顶级部门';
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
            $root_id = config('b5net.root_struct_id');
            $parent_id = $data['parent_id'] ?? '0';

            if ($parent_id) {
                $parentInfo = $this->model::bFind($parent_id);
                if (!$parentInfo) {
                    return '上级部门信息不存在';
                }
                $data['parent_name'] = trim($parentInfo['parent_name'] . ',' . $parentInfo['name'], ',');
                $data['levels'] = trim($parentInfo['levels'] . ',' . $parentInfo['id'], ',');
            }
        }

        return $data;
    }

    /**
     * 修改后 进行parent_name和levels更新
     * @param array $data
     * @param string $type
     */
    protected function saveAfter(array $data, string $type): void
    {
        if ($type == 'edit') {
            (new StructService())->updateExtendField($data['id']);
        }
    }

    /**
     * @return View|JsonResponse
     */
    public function tree(): View|JsonResponse
    {
        if ($this->request->isMethod('POST')) {
            $list = Templatetype::where(['status' => 1])->get()->toArray();
            return Result::success('', $list);
        } else {//是否显示父级名称
            $parent = $this->request->get('parent', 0);
            $id = $this->request->get('id', 0);
            return $this->render('', ['struct_id' => $id, 'parent' => $parent]);
        }
    }

    /**
     * 删除后操作
     * @param array $data
     */
    protected function deleteAfter(array $data): void
    {
        //删除管理员组织信息
        (new AdminStructService())->deleteByStruct($data['id']);

        //删除角色数据权限信息
        (new RoleStructService())->deleteByStruct($data['id']);
    }
}
