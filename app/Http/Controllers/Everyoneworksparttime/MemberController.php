<?php

namespace App\Http\Controllers\Everyoneworksparttime;

use App\Models\Billing;
use App\Models\Emailverify;
use App\Models\Member;
use App\Models\Msgcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;
use App\Http\Controllers\Everyoneworksparttime\Controller;

class MemberController extends Controller
{


    /**
     * @OA\Post(
     *      path="/tologin",
     *      tags={"用户模块"},
     *      summary="用户登录",
     *      @OA\Parameter(name="email",in="query",description="邮箱", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="password",in="query",description="密码", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="country",in="query",description="国家代码", required=true, @OA\Schema(type="string")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function tologin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|min:8|max:10|regex:/^[0-9]{8,10}$/',
            'password' => 'required|max:100',
            'country' => 'required|max:10',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }
        $users = Member::where(['email' => $request['email']])->first();
        if (!empty($users)) {
            if ($users->password == base64_encode(base64_encode($request->password))) {
                $token = Str::random(100);
                Member::where(['email' => $request['email']])->update(['token' => $token]);
                $users = Member::where(['email' => $request['email']])->first()->toArray();
                session(['member' => $users]);

                session(['country' => $request['country']]);
                return $this->success($token);
            } else {
                return $this->fail(0, 'รหัสผ่านบัญชีผิดพลาด', '');//账号密码错误
            }
        } else {
            return $this->fail(0, 'ไม่มีผู้ใช้', '');//用户不存在
        }
    }

    /**
     * @OA\Post(
     *      path="/toreg",
     *      tags={"用户模块"},
     *      summary="用户注册",
     *      @OA\Parameter(name="name",in="query",description="姓名", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="sex",in="query",description="性别0未知，1男，2女", required=true, @OA\Schema(type="integer")),
     *      @OA\Parameter(name="email",in="query",description="邮箱", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="password",in="query",description="密码", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="code",in="query",description="验证码", required=true, @OA\Schema(type="string")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function toreg(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'sex' => 'required|in:0,1,2',
            'email' => 'required|string|min:8|max:10|regex:/^[0-9]{8,10}$/',
            'password' => 'required|max:100',
            'code' => 'required|min:4|max:6',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }
        if (substr($request['email'], 0, 1) == 0) {
            $request['phone1'] = '66' . substr($request['email'], 1);
        } else {
            $request['phone1'] = '66' . $request['email'];
        }

        $resverify = Emailverify::where('email', $request['phone1'])->where('code', $request['code'])->where('type', 0)->orderBy('created_at', 'desc')->first();
        if (empty($resverify)) {
            return $this->fail(0, 'ไม่มีรหัสยืนยัน', '');//验证码不存在
        } else {
            $codetime = floor((time() - $resverify->created_at) / 60);
            $time = 5;
            if ($codetime < $time) {
                $token = Str::random(100);
                $res = Member::insert(['appid' => 10000, 'email' => $request['email'], 'name' => $request['name'],
                    'sex' => $request['sex'], 'password' => base64_encode(base64_encode($request['password'])), 'token' => $token]);
                if ($res) {
                    return $this->success($token);
                } else {
                    return $this->fail(0, "การลงทะเบียนไม่สำเร็จ", []);//注册失败
                }
                return $this->success($token);
            } else {
                return $this->fail(0, "รหัสยืนยันหมดอายุ", []);//验证码已过期
            }

        }


    }


    /**
     * @OA\Post(
     *      path="/email",
     *      tags={"用户模块"},
     *      summary="邮箱/手机号是否已注册",
     *      @OA\Parameter(name="email",in="query",description="邮箱", required=true, @OA\Schema(type="string")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function email(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|min:8|max:10|regex:/^[0-9]{8,10}$/',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }
        $resverify = Member::where('email', $request['email'])->first();
        if (empty($resverify)) {

            return $this->success((object)[]);
        } else {
            return $this->fail(0, 'บัญชีมีอยู่แล้ว', '');//账号已存在
        }
    }

    /**
     * @OA\Post(
     *      path="/editphoto",
     *      tags={"用户模块"},
     *      summary="修改头像",
     *      @OA\Parameter(name="token",in="header",description="token", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="photourl",in="query",description="链接地址", required=true, @OA\Schema(type="string")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function editphoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photourl' => 'required|url',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }
        $users = session('member');
        $res = Member::where(['id' => $users['id']])->update(['photo' => $request['photourl']]);
        if ($res) {
            return $this->success((object)[]);
        } else {
            return $this->fail(0, 'ไม่มีผู้ใช้', '');//用户不存在
        }
    }

    /**
     * @OA\Post(
     *      path="/emailverify",
     *      tags={"用户模块"},
     *      summary="邮箱/手机号验证码",
     *      @OA\Parameter(name="phone",in="query",description="手机号", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="type",in="query",description="类型0注册，1修改密码", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function emailverify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //  'email' => 'required|email',
            //  'phone' => 'required|regex:/^(\\+?0?66\\-?)?\\d{10}$/',     //正则验证
            'phone' => 'required|string|min:8|max:10|regex:/^[0-9]{8,10}$/',     //正则验证
            'type' => 'required|in:0,1',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }

        if (substr($request['phone'], 0, 1) == 0) {
            $request['phone1'] = '66' . substr($request['phone'], 1);
        } else {
            $request['phone1'] = '66' . $request['phone'];
        }
        $model = Emailverify::where('email', $request['phone'])->where('type', $request['type'])->orderBy('created_at', 'desc')->first();
        if (empty($model)) {
            return $this->sendMsg($request['phone'], $request['type']);
            //  return $this->postemail($request['email'], $request['type']);
        } else {
            $codetime = floor((time() - $model->created_at) / 60);
            $time = 1;
            if ($codetime > $time) {
                return $this->sendMsg($request['phone1'], $request['type']);
            } else {
                return $this->fail(0, "ส่งบ่อยเกินไป โปรดลองอีกครั้งใน " . $time . " minutes", []);
            }
        }
    }

    /**
     * @OA\Post(
     *      path="/phoneverify",
     *      tags={"用户模块"},
     *      summary="国内手机验证码",
     *      @OA\Parameter(name="phone",in="query",description="手机号", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="type",in="query",description="类型0注册，1修改密码", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function phoneverify(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'phone' => 'required|regex:/^1[345789][0-9]{9}$/',     //正则验证
            'type' => 'required|in:0,1',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }
        $model = Emailverify::where('email', $request['phone'])->where('type', $request['type'])->orderBy('created_at', 'desc')->first();

        if (empty($model)) {

            return $this->phonesendMsg($request['phone'], $request['type']);
            //  return $this->postemail($request['email'], $request['type']);
        } else {
            $codetime = floor((time() - $model->created_at) / 60);
            $time = 1;
            if ($codetime > $time) {
                return $this->phonesendMsg($request['phone'], $request['type']);
                //   return $this->postemail($request['phone'], $request['type']);
            } else {
                return $this->fail(0, "ส่งบ่อยเกินไป โปรดลองอีกครั้งใน " . $time . " minutes", []);
            }
        }
    }

    /**
     * @OA\Post(
     *      path="/editpassword",
     *      tags={"用户模块"},
     *      summary="修改密码",
     *      @OA\Parameter(name="token",in="header",description="token", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="password",in="query",description="密码", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="code",in="query",description="验证码", required=true, @OA\Schema(type="string")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function editpassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|max:16',
            'code' => 'required|string|min:4|max:6',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }
        $users = session('member');

        $resverify = Emailverify::where('email', '66' . $users['email'])->where('code', $request['code'])->where('type', 1)->orderBy('created_at', 'desc')->first();
        if (empty($resverify)) {
            return $this->fail(0, 'ไม่มีรหัสยืนยัน', '');//验证码不存在
        } else {
            $codetime = floor((time() - $resverify->created_at) / 60);
            $time = 5;
            if ($codetime > $time) {
                $res = Member::where(['id' => $users['id']])->update(['password' => base64_encode(base64_encode($request['password']))]);
                if ($res) {
                    return $this->success((object)[]);
                } else {
                    return $this->fail(0, 'แก้ไขไม่ได้', '');//修改失败
                }
            } else {
                return $this->fail(0, "รหัสยืนยันหมดอายุ", []);//验证码已过期
            }
        }


    }


    /**
     * @OA\Post(
     *      path="/forgetpassword",
     *      tags={"用户模块"},
     *      summary="忘记密码",
     *      @OA\Parameter(name="email",in="query",description="手机号/邮箱", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="password",in="query",description="密码", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="code",in="query",description="验证码", required=true, @OA\Schema(type="string")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function forgetpassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|string|min:6|max:16',
            'code' => 'required|string|min:4|max:6',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }
        $resverify = Emailverify::where('email', '66' . $request['email'])->where('code', $request['code'])->where('type', 1)->orderBy('created_at', 'desc')->first();
        if (empty($resverify)) {
            return $this->fail(0, 'ไม่มีรหัสยืนยัน', '');//验证码不存在
        } else {
            $codetime = floor((time() - $resverify->created_at) / 60);
            $time = 5;
            if ($codetime < $time) {
                $res = Member::where(['email' => $request['email']])->update(['password' => base64_encode(base64_encode($request['password']))]);
                if ($res) {
                    return $this->success((object)[]);
                } else {
                    return $this->fail(0, 'แก้ไขไม่ได้', '');//修改失败
                }
            } else {
                return $this->fail(0, "รหัสยืนยันหมดอายุ", []);//验证码已过期
            }
        }


    }

    /**
     * @OA\Post(
     *      path="/editname",
     *      tags={"用户模块"},
     *      summary="修改名称",
     *      @OA\Parameter(name="token",in="header",description="token", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="name",in="query",description="名称", required=true, @OA\Schema(type="string")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function editname(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:1|max:16',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }
        $users = session('member');
        $res = Member::where(['id' => $users['id']])->update(['name' => $request['name']]);

        if ($res) {
            return $this->success((object)[]);
        } else {
            return $this->fail(0, 'แก้ไขไม่ได้', '');//修改失败
        }
    }

    /**
     * @OA\Get(
     *      path="/userinfo",
     *      tags={"用户模块"},
     *      summary="用户信息",
     *      @OA\Parameter(name="token",in="header",description="token", required=true, @OA\Schema(type="string")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function userinfo(Request $request)
    {

        $users = session('member');
        $res = Member::where(['id' => $users['id']])->first()->toArray();
        return $this->success($res);
    }


    /**
     * @OA\Post(
     *      path="/edituserinfo",
     *      tags={"用户模块"},
     *      summary="修改用户信息",
     *      @OA\Parameter(name="token",in="header",description="token", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="photo",in="query",description="头像", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="name",in="query",description="姓名", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="sex",in="query",description="性别0未知，1男，2女", required=true, @OA\Schema(type="integer")),
     *      @OA\Parameter(name="profession",in="query",description="职业", required=true, @OA\Schema(type="string")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function edituserinfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|max:100',
            'name' => 'required|max:50',
            'sex' => 'required|in:0,1,2',
            'profession' => 'required|max:100',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }

        $users = session('member');
        $res = Member::where(['id' => $users['id']])->update(['photo' => $request['photo'], 'name' => $request['name']
            , 'sex' => $request['sex'], 'profession' => $request['profession']]);

        if ($res) {
            return $this->success((object)[]);
        } else {
            return $this->fail(0, 'แก้ไขไม่ได้', '');//修改失败
        }


    }

    /**
     * @OA\Get(
     *      path="/userbill",
     *      tags={"用户模块"},
     *      summary="用户账单",
     *      @OA\Parameter(name="token",in="header",description="token", required=true, @OA\Schema(type="string")),
     *      @OA\Parameter(name="transaction",in="query",description="交易类型 0：财务后台充值， 1：财务后台提现。 2：广告消费 ", required=false, @OA\Schema(type="integer")),
     *      @OA\Parameter(name="start_time",in="query",description="开始时间", required=false, @OA\Schema(type="date")),
     *      @OA\Parameter(name="end_time",in="query",description="结束时间", required=false, @OA\Schema(type="date")),
     *      @OA\Parameter(name="perpage",in="query",description="每页数", required=true, @OA\Schema(type="integer")),
     *      @OA\Parameter(name="page",in="query",description="页码", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function userbill(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'perpage' => 'required|numeric',
            'page' => 'required|numeric',
            'transaction' => 'numeric',
            'start_time' => 'date',
            'end_time' => 'date',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->fail(0, $errors->first('*'), '');
        }
        $users = session('member');

        $model = Billing::query();

        if ($request['transaction'] !== null) {
            $model = $model->where('transaction', $request['transaction']);
        }

        if ($request['start_time'] !== null) {
            $model = $model->whereDate('created_at', '>', $request['start_time']);

        }
        if ($request['end_time'] !== null) {
            $model = $model->whereDate('created_at', '<', $request['end_time']);
        }
        $res = $model->where(['userid' => $users['id']])->select('billingid', 'userid', 'money_type', 'transaction', 'amount', 'description', 'created_at', 'status')->simplePaginate($request['perpage'], $request['page']);;
        return $this->success($res);
    }


    /**
     * @OA\Get(
     *      path="/getmoney",
     *      tags={"用户模块"},
     *      summary="用户余额",
     *      @OA\Parameter(name="token",in="header",description="token", required=true, @OA\Schema(type="string")),
     *      @OA\Response(response=200,description="{code: int, msg:string, data:[]}",
     *        ),
     *     @OA\PathItem (
     *
     *     ),
     * )
     */
    public function getmoney(Request $request)
    {
        $user = session('member');


        //美元余额可用
        $data['USD'] = $this->myusd($user['id']);
        //广告花费总计
        $data['AD'] = $this->myad($user['id']);
        $data = json_decode(json_encode($data), true);
        return $this->success($data);
    }


}
