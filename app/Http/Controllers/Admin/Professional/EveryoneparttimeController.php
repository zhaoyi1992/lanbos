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
use App\Models\Professional\Everyoneparttime;
use App\Models\Professional\Everyoneparttimetype;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class EveryoneparttimeController extends Professional
{
    use AdminCommonAction;

    protected string $model = Everyoneparttime::class;

    /**
     * 首页列表处理
     * @param array $list
     * @return array
     */
    protected function indexAfter(array $list): array
    {
        $type = Everyoneparttimetype::where(['status' => 1])->pluck('poskey','id')->toArray();
        foreach ($list as $key => $value) {
            $list[$key]['typevalue'] = $type[$value['typeid']];
        }
        return $list;

    }
    /**
     * 首页条件数据处理
     * @param array $list
     * @return View
     */
    protected function indexRender(): View
    {
        $type = Everyoneparttimetype::bSelect(['status'=>1]);

        return $this->render('', ['type' => $type]);
    }

    /**
     * 编辑页渲染
     * @param array $info
     * @return View
     */
    protected function addRender(): View
    {
        $typeList = Everyoneparttimetype::where(['status' => 1])->get()->toArray();

        return $this->render('', ['typeList' => $typeList]);
    }

    /**
     * 编辑页渲染
     * @param array $info
     * @return View
     */
    protected function editRender(array $info): View
    {
        $typeList = Everyoneparttimetype::where(['status' => 1])->get()->toArray();

        $typevalue = Everyoneparttimetype::where(['status' => 1,'id' => $info['typeid']])->select('id','name','poskey')->first();

        return $this->render('', ['info' => $info, 'typeList' => $typeList, 'typevalue' => $typevalue]);
    }
    public function updatealllink(Request $request){
        Everyoneparttime::whereIn('id',[$request['ids']])->update(['links'=>'']);
        return Result::success('批量清空账号完成');
    }


    /**
     * 公共新增
     * @return View|JsonResponse
     */
    public function addlinks(): View|JsonResponse
    {
        if ($this->request->isMethod('POST')) {
            $data = $this->request->post();
            //验证
            if ($this->validate ?? false) {
                //验证前数据处理
                $data = $this->validateBefore($data,'add');
                if(!is_array($data)){
                    return Result::error($data);
                }
                $error = (new $this->validate())->setScene('add')->setData($data)->run();
                if ($error) {
                    return Result::error($error);
                }
            }

            //数据处理
            $data = $this->saveBefore($data,'add');

            $lines =  explode(PHP_EOL, $data['links']);
            foreach($lines as $k=>$v){
                Everyoneparttime::where('links','')->update(['links'=> trim($v)]);
            }
            return Result::success('保存成功');
        } else {
           $count= Everyoneparttime::where('links','')->count();
            return $this->render('',['count' => $count]);
        }
    }

}
