<?php

use App\Models\Professional\Everyoneparttime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Everyoneworksparttime\MemberController;
use App\Http\Controllers\Everyoneworksparttime\WorkController;
use App\Http\Controllers\Everyoneworksparttime\OrderController;
use App\Http\Controllers\Everyoneworksparttime\IndexController;
use App\Http\Middleware\Activity;
use App\Http\Middleware\Islogin;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//
Route::group(['middleware' => [ 'throttle:4,1',Activity::class]], function () {
    Route::get('/', function () {

        return response()->json([
            'code' => 200,
            'msg' => '成功',
            'data' => base64_encode(base64_encode('654321')),
        ]);
    })->name('首页')->platform('人人兼职');
    //用户模块
    Route::post('/tologin', [MemberController::class, 'tologin'])->name('登录操作')->platform('人人兼职');
    Route::post('/toreg', [MemberController::class, 'toreg'])->name('注册操作')->platform('人人兼职');
    Route::post('/emailmessage', [IndexController::class, 'emailmessage'])->name('邮件模板')->platform('人人兼职');
    Route::post('/emailverify', [MemberController::class, 'emailverify'])->name('手机验证码')->platform('人人兼职');
    Route::post('/phoneverify', [MemberController::class, 'phoneverify'])->name('邮箱验证')->platform('人人兼职');
    Route::post('/email', [MemberController::class, 'email'])->name('邮箱是否已注册')->platform('人人兼职');
    Route::post('/contentpload', [IndexController::class, 'contentpload'])->name('类容导入')->platform('人人兼职');
    Route::post('/forgetpassword', [MemberController::class, 'forgetpassword'])->name('忘记密码')->platform('人人兼职');


});
Route::group(['middleware' => [ Activity::class, Islogin::class]], function () {
    //用户模块
    Route::get('/userinfo', [MemberController::class, 'userinfo'])->name('用户信息')->platform('人人兼职');
    Route::post('/edituserinfo', [MemberController::class, 'edituserinfo'])->name('修改用户信息')->platform('人人兼职');
    Route::post('/editphoto', [MemberController::class, 'editphoto'])->name('修改头像')->platform('人人兼职');
    Route::post('/editpassword', [MemberController::class, 'editpassword'])->name('修改密码')->platform('人人兼职');
    Route::post('/editname', [MemberController::class, 'editname'])->name('修改名称')->platform('人人兼职');
    Route::get('/userbill', [MemberController::class, 'userbill'])->name('用户账单信息')->platform('人人兼职');
    Route::get('/getmoney', [MemberController::class, 'getmoney'])->name('用户余额')->platform('人人兼职');

    //订单模块
    Route::post('/orderlist', [OrderController::class, 'orderlist'])->name('订单列表')->platform('人人兼职');
    Route::post('/getorder', [OrderController::class, 'getorder'])->name('订单详情')->platform('人人兼职');
    Route::post('/create_order', [OrderController::class, 'create_order'])->name('报名')->platform('人人兼职');
    Route::post('/collect', [OrderController::class, 'collect'])->name('收藏')->platform('人人兼职');
    Route::post('/collectlist', [OrderController::class, 'collectlist'])->name('收藏列表')->platform('人人兼职');
    //其他模块
    Route::post('/upload', [IndexController::class, 'upload'])->name('图片上传')->platform('人人兼职');
    Route::post('/gettemplate', [IndexController::class, 'gettemplate'])->name('模板获取')->platform('人人兼职');


    //兼职模块
    Route::post('/typelist', [WorkController::class, 'typelist'])->name('兼职分类')->platform('人人兼职');
    Route::post('/worklist', [WorkController::class, 'worklist'])->name('兼职列表')->platform('人人兼职');
    Route::post('/getwork', [WorkController::class, 'getwork'])->name('兼职详情')->platform('人人兼职');


});
