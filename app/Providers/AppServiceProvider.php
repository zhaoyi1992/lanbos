<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url, Request $request,)
    {
        $url->forceScheme('https');
        Blade::component('upload', \App\Extends\Tags\Upload::class);
        \Schema::defaultStringLength(191);
        // 记录请求信息
        $requestMessage = [
            'url' => $request->url(),
            'method' => $request->method(),
            'ip' => $request->ips(),
            'headers' => $request->header('token') ?: '0',
            'params' => $request->all()
        ];
        \Log::info('请求信息: ' . json_encode($requestMessage) . "\n\n\t");
        \DB::listen(function ($query) {
            $tmp = str_replace('?', '"' . '%s' . '"', $query->sql);
            $qBindings = [];
            foreach ($query->bindings as $key => $value) {
                if (is_numeric($key)) {
                    $qBindings[] = $value;
                } else {
                    $tmp = str_replace(':' . $key, '"' . $value . '"', $tmp);
                }
            }
            $tmp = vsprintf($tmp, $qBindings);
            $tmp = str_replace("\\", "", $tmp);
            \Log::info(' SQL记录: ' . $query->time . 'ms; ' . $tmp . "\n\n\t");
        }
        );
    }
}
