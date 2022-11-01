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
use App\Extends\Services\System\StructService;
use App\Models\Professional\Joininvestment;
use App\Models\Professional\JoininvestmentType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class JoininvestmentController extends Professional
{
    use AdminCommonAction;

    protected string $model = Joininvestment::class;



    /**
     * 首页列表处理
     * @param array $list
     * @return array
     */
    protected function indexAfter(array $list): array
    {
        $type = JoininvestmentType::where(['status' => 1])->pluck('id','poskey')->toArray();

        foreach ($list as $key => $value) {
            $list[$key]['typevalue'] = $type[$value['id']];
        }
        return $list;

    }
    /**
     * 查询列表前
     * @param array $params
     * @return array
     */
    protected function indexBefore(array $params): array
    {
        $params['orderBy'] = ['id' => 'asc', 'listsort' => 'asc'];
        return $params;
    }
    /**
     * 首页条件数据处理
     * @param array $list
     * @return View
     */
    protected function indexRender(): View
    {
        $type = JoininvestmentType::bSelect(['status'=>1]);

        return $this->render('', ['type' => $type]);
    }
    /**
     * 编辑页渲染
     * @param array $info
     * @return View
     */
    protected function addRender(): View
    {
        $typeList = JoininvestmentType::where(['status' => 1])->get()->toArray();

        return $this->render('', ['typeList' => $typeList]);
    }

    /**
     * 编辑页渲染
     * @param array $info
     * @return View
     */
    protected function editRender(array $info): View
    {
        $typeList = JoininvestmentType::where(['status' => 1])->get()->toArray();

        return $this->render('', ['info' => $info, 'typeList' => $typeList]);
    }



}
