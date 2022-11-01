<?php
// +----------------------------------------------------------------------
// | B5LaravelCMF V2.0 [快捷通用基础管理开发平台]
// +----------------------------------------------------------------------
// | Author: z <834574377@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace App\Http\Controllers\Admin\Professional;

use App\Extends\Helpers\Result;
use App\Extends\Libs\AdminCommonAction;
use App\Extends\Services\Professional\EveryoneparttimetypeService;

use App\Models\Professional\Country;
use App\Models\Professional\Everyoneparttimetype;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class EveryoneparttimetypeController extends Professional
{
    use AdminCommonAction;
    protected string $model = Everyoneparttimetype::class;


    /**
     * 首页列表处理
     * @param array $list
     * @return array
     */
    protected function indexAfter(array $list): array
    {
        $type = Country::where(['status' => 1])->pluck('name','poskey')->toArray();
        foreach ($list as $key => $value) {
            $list[$key]['countryvalue'] = $type[$value['country']];
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
        $type = Country::bSelect(['status'=>1]);

        return $this->render('', ['type' => $type]);
    }
    /**
     * 编辑页渲染
     * @param array $info
     * @return View
     */
    protected function addRender(): View
    {
        $typeList = Country::where(['status' => 1])->get()->toArray();

        return $this->render('', ['typeList' => $typeList]);
    }

    /**
     * 编辑页渲染
     * @param array $info
     * @return View
     */
    protected function editRender(array $info): View
    {
        $typeList = Country::where(['status' => 1])->get()->toArray();

        return $this->render('', ['info' => $info, 'typeList' => $typeList]);
    }

    /**
     * @return View|JsonResponse
     */
    public function tree(): View|JsonResponse
    {
        if ($this->request->isMethod('POST')) {


           $country=Country::where(['status'=>1])->select('poskey','name')->get()->toArray();//parent_id
            foreach ($country as $k=>$y){
                $country[$k]['id']=$y['poskey'];
                $country[$k]['parent_id']=0;
            }
            $type=Everyoneparttimetype::where(['status'=>1])->select('id','country','poskey')->get()->toArray();//parent_id
            foreach ($type as $k=>$y){
                $y['name']= $y['poskey'];
                $y['parent_id']= $y['country'];
                $country[]=$y;
            }
            return Result::success('', $country);
        } else {//是否显示父级名称
            $parent = $this->request->get('parent', 0);
            $id = $this->request->get('id', 0);
            return $this->render('', ['struct_id' => $id, 'parent' => $parent]);
        }
    }


}
