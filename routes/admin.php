<?php
use Illuminate\Support\Facades\Route;

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
//后台管理路由
Route::middleware(['admin.login','admin.auth','admin.demo'])->group(function(){

    //Index控制器
    Route::controller(\App\Http\Controllers\Admin\IndexController::class)->group(function (){
        Route::get('/', 'index')->name('admin.index');
        Route::get('/home', 'home')->name('admin.home');
        Route::get('/download', 'download')->name('admin.download');
    });

    //Public控制器
    Route::controller(\App\Http\Controllers\Admin\PublicController::class)->group(function (){
        Route::any('/login','login')->name('admin.login');
        Route::get('/logout','logout')->name('admin.logout');
        Route::get('/noauth','noauth')->name('admin.noauth');
    });

    //Common控制器
    Route::controller(\App\Http\Controllers\Admin\CommonController::class)->group(function (){
        Route::post('/uploadimg','uploadimg')->name('admin.uploadimg');
        Route::post('/uploadvideo','uploadvideo')->name('admin.uploadvideo');
        Route::post('/uploadfile','uploadfile')->name('admin.uploadfile');
        Route::get('/cropper','cropper')->name('admin.cropper');
        Route::any('/lockscreen','lockscreen')->name('admin.lockscreen');
        Route::any('/cacheclear','cacheclear')->name('admin.cacheclear');
        Route::any('/repass','repass')->name('admin.repass');
    });



    //业务模块
    Route::prefix('professional')->name('professional.')->group(function (){
        //模板库
        //模板分类管理TemplatetypeController
        Route::controller(\App\Http\Controllers\Admin\Professional\TemplatetypeController::class)->prefix('templatetype')->name('templatetype.')->group(function (){
            Route::any('/index', 'index')->name('index');
            Route::any('/add', 'add')->name('add');
            Route::any('/edit', 'edit')->name('edit');
            Route::any('/tree', 'tree')->name('tree');
        });

        //模板管理Templateview
        Route::controller(\App\Http\Controllers\Admin\Professional\TemplateviewController::class)->prefix('templateview')->name('templateview.')->group(function (){
            Route::any('/index', 'index')->name('index');
            Route::post('/index', 'index')->name('index');
            Route::any('/add', 'add')->name('add');
            Route::any('/edit', 'edit')->name('edit');

        });

        //人人兼职  everyoneparttime
        //国家

        Route::controller(\App\Http\Controllers\Admin\Professional\CountryController::class)->prefix('country')->name('country.')->group(function (){
            Route::any('/index', 'index')->name('index');
            Route::any('/add', 'add')->name('add');
            Route::any('/edit', 'edit')->name('edit');
            Route::any('/tree', 'tree')->name('tree');
        });
        //分类
        Route::controller(\App\Http\Controllers\Admin\Professional\EveryoneparttimetypeController::class)->prefix('everyoneparttimetype')->name('everyoneparttimetype.')->group(function (){
            Route::any('/index', 'index')->name('index');
            Route::any('/add', 'add')->name('add');
            Route::any('/edit', 'edit')->name('edit');
            Route::any('/tree', 'tree')->name('tree');
        });
        //任务
        Route::controller(\App\Http\Controllers\Admin\Professional\EveryoneparttimeController::class)->prefix('everyoneparttime')->name('everyoneparttime.')->group(function (){
            Route::any('/index', 'index')->name('index');
            Route::post('/index', 'index')->name('index');
            Route::any('/add', 'add')->name('add');
            Route::any('/edit', 'edit')->name('edit');
            Route::post('/drop', 'drop')->name('drop');
            Route::any('/addlinks', 'addlinks')->name('addlinks');
            Route::post('/updatealllink', 'updatealllink')->name('updatealllink');//批量清空

        });
        //joininvestment  招商
        //banner
        Route::controller(\App\Http\Controllers\Admin\Professional\JoininvestmentBannerController::class)->prefix('joininvestmentbanner')->name('joininvestmentbanner.')->group(function (){
            Route::any('/index', 'index')->name('index');
            Route::any('/add', 'add')->name('add');
            Route::any('/edit', 'edit')->name('edit');
            Route::any('/tree', 'tree')->name('tree');
        });
        //joininvestmenttype分类
        Route::controller(\App\Http\Controllers\Admin\Professional\JoininvestmentTypeController::class)->prefix('joininvestmenttype')->name('joininvestmenttype.')->group(function (){
            Route::any('/index', 'index')->name('index');
            Route::any('/add', 'add')->name('add');
            Route::any('/edit', 'edit')->name('edit');
            Route::any('/tree', 'tree')->name('tree');

        });
        //joininvestment信息
        Route::controller(\App\Http\Controllers\Admin\Professional\JoininvestmentController::class)->prefix('joininvestment')->name('joininvestment.')->group(function (){
            Route::any('/index', 'index')->name('index');
            Route::any('/add', 'add')->name('add');
            Route::any('/edit', 'edit')->name('edit');
            Route::any('/tree', 'tree')->name('tree');

        });

    });

    //会员模块
    Route::prefix('member')->name('member.')->group(function (){
        //用户列表
        Route::controller(\App\Http\Controllers\Admin\Member\UserController::class)->prefix('user')->name('user.')->group(function (){
            Route::any('/index', 'index')->name('index');
            Route::any('/add', 'add')->name('add');
            Route::any('/edit', 'edit')->name('edit');

        });
        //用户行为
        Route::controller(\App\Http\Controllers\Admin\Member\BehaviourController::class)->prefix('behaviour')->name('behaviour.')->group(function (){
            Route::any('/index', 'index')->name('index');
            Route::any('/add', 'add')->name('add');
            Route::any('/edit', 'edit')->name('edit');

        });
        //用户行为
        Route::controller(\App\Http\Controllers\Admin\Member\EmailverifyController::class)->prefix('emailverify')->name('emailverify.')->group(function (){
            Route::any('/index', 'index')->name('index');
            Route::any('/add', 'add')->name('add');
            Route::any('/edit', 'edit')->name('edit');

        });


    });

    //订单模块
    Route::prefix('order')->name('order.')->group(function (){
        //用户列表OrderController
        Route::controller(\App\Http\Controllers\Admin\Order\OrderController::class)->prefix('order')->name('order.')->group(function (){
            Route::any('/index', 'index')->name('index');
            Route::any('/add', 'add')->name('add');
            Route::any('/edit', 'edit')->name('edit');

        });



    });

    //系统模块
    Route::prefix('system')->name('system.')->group(function (){
        //配置管理
        Route::controller(\App\Http\Controllers\Admin\System\ConfigController::class)->prefix('config')->name('config.')->group(function (){
            Route::any('/index', 'index')->name('index');
            Route::any('/add', 'add')->name('add');
            Route::any('/edit', 'edit')->name('edit');
            Route::post('/drop', 'drop')->name('drop');
            Route::any('/site', 'site')->name('site');
            Route::post('/dropall', 'dropall')->name('dropall');
        });
        //组织部门
        Route::controller(\App\Http\Controllers\Admin\System\StructController::class)->prefix('struct')->name('struct.')->group(function (){
            Route::any('/index', 'index')->name('index');
            Route::any('/add', 'add')->name('add');
            Route::any('/edit', 'edit')->name('edit');
            Route::post('/drop', 'drop')->name('drop');
            Route::any('/tree', 'tree')->name('tree');
        });
        //岗位管理
        Route::controller(\App\Http\Controllers\Admin\System\PositionController::class)->prefix('position')->name('position.')->group(function (){
            Route::any('/index', 'index')->name('index');
            Route::any('/add', 'add')->name('add');
            Route::any('/edit', 'edit')->name('edit');
            Route::post('/drop', 'drop')->name('drop');
            Route::post('/dropall', 'dropall')->name('dropall');
        });
        //菜单管理
        Route::controller(\App\Http\Controllers\Admin\System\MenuController::class)->prefix('menu')->name('menu.')->group(function (){
            Route::any('/index', 'index')->name('index');
            Route::any('/add', 'add')->name('add');
            Route::any('/edit', 'edit')->name('edit');
            Route::post('/drop', 'drop')->name('drop');
            Route::any('/tree', 'tree')->name('tree');
        });
        //角色管理
        Route::controller(\App\Http\Controllers\Admin\System\RoleController::class)->prefix('role')->name('role.')->group(function (){
            Route::any('/index', 'index')->name('index');
            Route::any('/add', 'add')->name('add');
            Route::any('/edit', 'edit')->name('edit');
            Route::post('/drop', 'drop')->name('drop');
            Route::post('/dropall', 'dropall')->name('dropall');
            Route::post('/setstatus', 'setstatus')->name('setstatus');
            Route::any('/auth', 'auth')->name('auth');
            Route::any('/datascope', 'datascope')->name('datascope');
        });
        //人员管理
        Route::controller(\App\Http\Controllers\Admin\System\AdminController::class)->prefix('admin')->name('admin.')->group(function (){
            Route::any('/index', 'index')->name('index');
            Route::any('/add', 'add')->name('add');
            Route::any('/edit', 'edit')->name('edit');
            Route::post('/drop', 'drop')->name('drop');
            Route::post('/dropall', 'dropall')->name('dropall');
            Route::any('/tree', 'tree')->name('tree');
            Route::post('/ajaxtreelist', 'ajaxtreelist')->name('ajaxtreelist');
        });
        //登录日志
        Route::controller(\App\Http\Controllers\Admin\System\LoginlogController::class)->prefix('loginlog')->name('loginlog.')->group(function (){
            Route::any('/index', 'index')->name('index');
            Route::post('/dropall', 'dropall')->name('dropall');
        });
        //通知公告
        Route::controller(\App\Http\Controllers\Admin\System\NoticeController::class)->prefix('notice')->name('notice.')->group(function (){
            Route::any('/index', 'index')->name('index');
            Route::any('/add', 'add')->name('add');
            Route::any('/edit', 'edit')->name('edit');
            Route::post('/drop', 'drop')->name('drop');
            Route::post('/dropall', 'dropall')->name('dropall');
        });

    });

    //工具模块
    Route::prefix('tool')->group(function (){
        Route::controller(\App\Http\Controllers\Admin\Tool\FormController::class)->prefix('form')->group(function (){
            Route::get('/build', 'build');
        });
        Route::controller(\App\Http\Controllers\Admin\Tool\GenController::class)->prefix('gen')->group(function (){
            Route::any('/create', 'create');
        });
        Route::controller(\App\Http\Controllers\Admin\Tool\UploadController::class)->prefix('upload')->group(function (){
            Route::any('/index', 'index');
            Route::any('/add', 'add');
            Route::any('/edit', 'edit');
            Route::post('/drop', 'drop');
            Route::post('/dropall', 'dropall');
        });
    });


});




