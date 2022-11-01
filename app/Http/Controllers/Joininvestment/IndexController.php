<?php

namespace App\Http\Controllers\Joininvestment;

use App\Imports\ContentImport;

use App\Models\Behaviour;
use App\Models\Professional\Joininvestment;
use App\Models\Professional\JoininvestmentBanner;
use App\Models\Professional\JoininvestmentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Zhuzhichao\IpLocationZh\Ip;

class IndexController extends Controller
{


    /**
     * @OA\Post(
     *      path="/banner",
     *      tags={"招商模块"},
     *      @OA\Parameter(name="place",in="query",description="位置0；首页，1：分类1列表页，2：分类2列表页，3：分类3列表页", required=true, @OA\Schema(type="integer")),
     *      @OA\Parameter(name="hot",in="query",description="推荐0；否，1：是", required=false, @OA\Schema(type="integer")),
     *      summary="banner广告",
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function banner(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'place' => 'required|numeric|in:0,1,2,3',
            'hot' => 'numeric',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }
        $where = [];
        if ($request['hot'] !== null) {
            $where['hot'] = $request['hot'];
        }
        if ($request['place'] !== null) {
            $where['place'] = $request['place'];
        }
        $res = JoininvestmentBanner::where(['status' => 1])->where($where)->select('id', 'title', 'img', 'link', 'listsort', 'hot')->orderBy('listsort')->get();
        return $this->success($res);
    }

    /**
     * @OA\Post(
     *      path="/businesstypelist",
     *      tags={"招商模块"},
     *      summary="招商分类列表",
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function businesstypelist(Request $request)
    {
        $res = JoininvestmentType::where(['status' => 1])->select('id', 'name', 'poskey', 'img', 'listsort', 'status')->orderBy('listsort')->get();
        return $this->success($res);
    }

    /**
     * @OA\Post(
     *      path="/businesslist",
     *      tags={"招商模块"},
     *      summary="招商列表",
     *      @OA\Parameter(name="joininvestmenttype",in="query",description="分类id", required=true, @OA\Schema(type="integer")),
     *      @OA\Parameter(name="hot",in="query",description="推荐0；否，1：是", required=false, @OA\Schema(type="integer")),
     *      @OA\Parameter(name="page",in="query",description="页码", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function businesslist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'joininvestmenttype' => 'numeric',
            'hot' => 'numeric',
            'page' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }
        $where = [];
        if ($request['joininvestmenttype'] !== null) {
            $where['joininvestmenttype'] = $request['joininvestmenttype'];
        }
        if ($request['hot'] !== null) {
            $where['hot'] = $request['hot'];
        }

        $res = Joininvestment::where($where)->select('id','joininvestmenttype','title','content','img','link','hot','listsort','status')->orderBy('listsort', 'desc')->paginatenew(10, $request['page']);
        return $this->success($res);
    }


    public function getbusiness(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }

        return $this->success(1);
    }
    /**
     * @OA\Post(
     *      path="/behaviour",
     *      tags={"招商模块"},
     *      summary="用户行为",
     *      @OA\Parameter(name="source",in="query",description="操作描述", required=true, @OA\Schema(type="string")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function behaviour(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'source' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }
        $res1 = Ip::find(isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : request()->getClientIp());
        $user = session('member');
        if ($user == null) {
            $uid = 0;
        } else {
            $uid = $user['id'];
        }
        Behaviour::insert(['ip' => isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : request()->getClientIp(),'appid'=>request()->route()->getPlatform(), 'urlname' => $request['source'], 'uid' => $uid, 'source' => 'web接口请求', 'country' => $res1[0], 'region' => $res1[1], 'city' => $res1[2], 'clicks' => 1, 'add_time' => date("Y-m-d H:i:s", time())]);
        return $this->success((object)[]);
    }
}

