<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Activity;
use App\Http\Middleware\Islogin;
use App\Http\Controllers\Joininvestment\IndexController;
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
    })->name('首页')->platform('招商');

    //兼职模块
    Route::post('/banner', [IndexController::class, 'banner'])->name('banner广告')->platform('招商');
    Route::post('/businesstypelist', [IndexController::class, 'businesstypelist'])->name('招商分类')->platform('招商');
    Route::post('/businesslist', [IndexController::class, 'businesslist'])->name('分类列表')->platform('招商');
    Route::post('/getbusiness', [IndexController::class, 'getbusiness'])->name('招商详情')->platform('招商');

});

Route::group(['middleware' => [ 'throttle:4,1']], function () {
    Route::post('/behaviour', [IndexController::class, 'behaviour'])->name('用户行为')->platform('招商');
});

