<?php

namespace App\Http\Controllers\Everyoneworksparttime;

use App\Imports\ContentImport;
use App\Models\Professional\Collect;
use App\Models\Professional\Country;
use App\Models\Professional\Everyoneparttime;
use App\Models\Professional\Everyoneparttimetype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class WorkController extends Controller
{
    /**
     * @OA\Post(
     *      path="/typelist",
     *      tags={"兼职模块"},
     *      summary="兼职分类",
     *      @OA\Parameter(name="token",in="header",description="token", required=true, @OA\Schema(type="string")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function typelist(Request $request)
    {
        $country = session('country');
        $countryid=   Country::where(['status' => 1,'poskey' => $country])->pluck('id')->first();
        $countryid=    $countryid?$country:'TH';
        $res = Everyoneparttimetype::where(['status' => 1,'country' => $countryid])->select('id','name')->get();
        return $this->success($res);
    }
    /**
     * @OA\Post(
     *      path="/worklist",
     *      tags={"兼职模块"},
     *      summary="兼职列表",
     *      @OA\Parameter(name="token",in="header",description="token", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="typeid",in="query",description="分类", required=true, @OA\Schema(type="integer")),
     *      @OA\Parameter(name="address",in="query",description="地址", required=false, @OA\Schema(type="string")),
     *      @OA\Parameter(name="page",in="query",description="页码", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function worklist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'typeid' => 'required|numeric',
            'address' => 'max:100',
            'page' => 'numeric',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }
        $res = Everyoneparttime::where(['typeid' => $request['typeid'],'status' => 1])->where('address', 'like', '%' .$request['address'] . '%')->select('id','typeid','name','keyword','price','address')->orderBy('id', 'desc')->paginatenew(10, $request['page']);
        return $this->success($res);
    }
    /**
     * @OA\Post(
     *      path="/getwork",
     *      tags={"兼职模块"},
     *      summary="任务详情",
     *      @OA\Parameter(name="token",in="header",description="token", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="id",in="query",description="编号", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function getwork(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }
        $res = Everyoneparttime::where(['id' => $request['id'],'status' => 1])->first();

        $users = session('member');
        $resc = Collect::where(['goodsid' => $request['id'], 'userid' => $users['id'],'status'=>1])->first();



        $res['collect']=empty($resc)?0:1;
        return $this->success($res);
    }

}

