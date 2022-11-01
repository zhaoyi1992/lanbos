<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="蓝博客户端api接口文档",
 *     description="API接口返回描述：code=200正常，code=401登录信息错误，code=0其他错误。msg为返回描述，data为返回结构体",
 *     version="0.1",
 *      @OA\Contact(
 *          email="834574377@qq.com"
 *      ),
 * ),
 *  @OA\Server(
 *      description="招商项目线上",
 *      url="https://www.hsbfa.xyz/businessapi/"
 *  ),
 *  @OA\Server(
 *      description="人人兼职线上",
 *      url="https://www.hsbfa.xyz/ptweb/"
 *  ),
 * @OA\Server(
 *      description="人人兼职本机测试",
 *      url="http://192.168.60.42/ptweb/"
 *  ),
 * @OA\Server(
 *      description="邮箱发送服务器",
 *      url="https://juliangtec.com/app"
 *  ),
 *  * @OA\Server(
 *      description="DSP线上",
 *      url="https://api.lanbodsp.com"
 *  ),
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

}
