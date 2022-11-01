<?php

namespace App\Http\Controllers\Everyoneworksparttime;
use App\Models\Professional\Collect;
use App\Models\Professional\Everyoneparttime;
use App\Models\Professional\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class OrderController extends Controller
{

    /**
     * @OA\Post(
     *      path="/orderlist",
     *      tags={"订单模块"},
     *      summary="订单列表",
     *      @OA\Parameter(name="token",in="header",description="token", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="status",in="query",description="状态", required=false, @OA\Schema(type="integer")),
     *      @OA\Parameter(name="start_time",in="query",description="开始时间", required=false, @OA\Schema(type="integer")),
     *      @OA\Parameter(name="end_time",in="query",description="结束时间", required=false, @OA\Schema(type="integer")),
     *      @OA\Parameter(name="page",in="query",description="页码", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function orderlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'required|numeric',
            'status' => 'in:0,1,2,3',
            'start_time' => 'date',
            'end_time' => 'date',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }
        $users = session('member');
        $where[] = ['userid', $users['id']];
        $model = Order::query();
        if ($request['status'] !== null) {
            $model = $model->where('status', $request['status']);
        }
        if ($request['start_time'] !== null) {
            $model = $model->whereDate('create_time', '>', $request['start_time']);

        }
        if ($request['end_time'] !== null) {
            $model = $model->whereDate('create_time', '<', $request['end_time']);
        }
        $res = $model->with(['everyoneparttime'])->where($where)->select('id', 'appid', 'orderid', 'userid', 'goodsid', 'num', 'price', 'status', 'create_time', 'update_time')->orderBy('id', 'desc')->paginatenew(10, $request['page']);
        return $this->success($res);
    }

    /**
     * @OA\Post(
     *      path="/getorder",
     *      tags={"订单模块"},
     *      summary="订单详情",
     *      @OA\Parameter(name="token",in="header",description="token", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="id",in="query",description="编号", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function getorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }
        $users = session('member');

        $res = Order::where(['id' => $request['id'], 'userid' => $users['id']])->first();
        return $this->success($res);
    }

    /**
     * @OA\Post(
     *      path="/create_order",
     *      tags={"订单模块"},
     *      summary="报名",
     *      @OA\Parameter(name="token",in="header",description="token", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="id",in="query",description="编号", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function create_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }
        $users = session('member');
        $isorder = Order::where(['userid' => $users['id'], 'goodsid' => $request['id'], 'appid' => 10000, 'status' => 1])->first();
        if (!empty($isorder)) {
            return $this->fail(0, 'ลงทะเบียนแล้ว โปรดอย่าลงทะเบียนอีก', (object)[]);//已经报名了，不要重复报名
        } else {
            $goods = Everyoneparttime::where(['id' => $request['id']])->first();
            $lines =  explode(PHP_EOL, $goods['links']);
            if(empty($lines)){
                $lines[]='1';
            }
            Order::Insert(['appid' => 10000, 'orderid' => time() . rand(10000, 99999), 'userid' => $users['id'], 'goodsid' => $request['id'],
                'num' => 1, 'price' => $goods['price'], 'note' => $lines[array_rand($lines)], 'status' => 1]);
           // return $this->success($lines[array_rand($lines)]);
            return $this->success(str_replace('','\r', $lines[array_rand($lines)]));
        }
    }

    /**
     * @OA\Post(
     *      path="/collect",
     *      tags={"订单模块"},
     *      summary="收藏",
     *      @OA\Parameter(name="token",in="header",description="token", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="id",in="query",description="编号", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function collect(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }
        $users = session('member');
        $isorder = Collect::where(['userid' => $users['id'], 'goodsid' => $request['id'], 'appid' => 10000])->first();

        if (!empty($isorder)) {

            if ($isorder['status'] == 1) {
                Collect::where(['userid' => $users['id'], 'goodsid' => $request['id'], 'appid' => 10000])->update(['status' => 0]);
            } else {
                Collect::where(['userid' => $users['id'], 'goodsid' => $request['id'], 'appid' => 10000])->update(['status' => 1]);
            }
            return $this->success((object)[]);
        } else {
            Collect::Insert(['userid' => $users['id'], 'goodsid' => $request['id'], 'appid' => 10000, 'status' => 1]);
            return $this->success((object)[]);
        }
    }

    /**
     * @OA\Post(
     *      path="/collectlist",
     *      tags={"订单模块"},
     *      summary="收藏列表",
     *      @OA\Parameter(name="token",in="header",description="token", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="start_time",in="query",description="开始时间", required=false, @OA\Schema(type="integer")),
     *      @OA\Parameter(name="end_time",in="query",description="结束时间", required=false, @OA\Schema(type="integer")),
     *      @OA\Parameter(name="page",in="query",description="页码", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function collectlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'required|numeric',
            'start_time' => 'date',
            'end_time' => 'date',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }
        $users = session('member');
        $where[] = ['userid', $users['id']];
        $where[] = ['status', 1];
        $model = Collect::query();
        if ($request['start_time'] !== null) {
            $model = $model->whereDate('create_time', '>', $request['start_time']);
        }
        if ($request['end_time'] !== null) {
            $model = $model->whereDate('create_time', '<', $request['end_time']);
        }

        $res = $model->with(['everyoneparttime'])->where($where)->select('id', 'goodsid', 'userid', 'create_time', 'status')->orderBy('id', 'desc')->paginatenew(10, $request['page']);

        return $this->success($res);
    }

}

