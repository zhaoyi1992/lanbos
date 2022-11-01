<?php

namespace App\Http\Middleware;

use App\Models\Behaviour;
use Closure;
use Zhuzhichao\IpLocationZh\Ip;
use Illuminate\Support\Facades\DB;

//前置操作
class Activity
{
    public function handle($request, Closure $next)
    {
        //  前置操作
        $response = $next($request);

        //  后置操作
        $res1 = Ip::find(isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : request()->getClientIp());
        $user = session('member');
        if ($user == null) {
            $uid = 0;
        } else {
            $uid = $user['id'];
        }
        Behaviour::insert(['ip' => isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : request()->getClientIp(),'appid'=>request()->route()->getPlatform(), 'urlname' => request()->route()->getName(), 'uid' => $uid, 'source' => 'web接口请求', 'country' => $res1[0], 'region' => $res1[1], 'city' => $res1[2], 'clicks' => 1, 'add_time' => date("Y-m-d H:i:s", time())]);
        return $response;
    }
}
