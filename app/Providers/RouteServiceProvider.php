<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::prefix('admin')
                ->middleware('admin')
                ->group(base_path('routes/admin.php'));

        //人人兼职
            Route::prefix('ptweb')
                ->middleware('ptweb')
                ->group(base_path('routes/ptweb.php'));

            //招商项目
            Route::prefix('businessapi')
                ->middleware('businessapi')
                ->group(base_path('routes/businessapi.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip())->response(function () {
                return response('您的请求太频繁，已被限制请求', 429);
            });
        });

        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(1000)->response(function () {
                return response('您的请求太频繁，已被限制请求', 429);
            });
        });


    }
}
