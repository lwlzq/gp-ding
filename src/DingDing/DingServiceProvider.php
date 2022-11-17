<?php
/**
 * @Filename:
 * @Description:
 * @Author: liuweiliang
 * @CreateDate: 2022/11/17 11:31
 * @UpdateDate: 2022/11/17 11:31 By liuweiliang
 * @return ${TYPE_HINT}
 */

namespace Gp\Ding\DingDing;

use Illuminate\Support\ServiceProvider;

class DingServiceProvider extends ServiceProvider
{
    public function register()
    {
//        $this->app->singleton('test', function () {
//            return new Test();
//        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/ding.php' => config_path('ding.log'),
            __DIR__ . '/log/ding.log' =>  storage_path('logs/ding.log'),
        ]);
    }

}