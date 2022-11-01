<?php

namespace App\Http\Middleware;

use App\Models\Member;
use Closure;
use Illuminate\Support\Facades\Validator;

class Islogin
{
    /**
     * Handle an incoming request.
     * 身份验证
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($request->header('token') == null) {
            return response()->json([
                'code' => 401,
                'msg' => 'token 为空',
                'data' => [],
            ]);
        }
        if ($request->header('userid') !== null) {
            $users = Member::where(['id' => $request->header('userid')])->first();
            session(['member' => $users]);
        } else {
            $member = session('member');
            if ($member == null) {
                return response()->json([
                    'code' => 402,
                    'msg' => '身份验证,未登录',
                    'data' => [],
                ]);
            }

            $res = Member::where(['email' => $member['email'], 'token' => $request->header('token')])->first();

            if (empty($res)) {
                return response()->json([
                    'code' => 403,
                    'msg' => '您的账号已在另一台设备登录',
                    'data' => ['email' => $member['email'], 'token' => $request->header('token')],
                ]);
            }
        }
        return $next($request);
    }
}
