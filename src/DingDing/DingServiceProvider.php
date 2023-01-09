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

/**
 * @Class DingServiceProvider
 * @Description:钉服务提供者
 * @CreateDate: 2023/1/9 17:42
 * @UpdateDate: 2023/1/9 17:42 By liuweiliang
 * ${PARAM_DOC}
 * @return ${TYPE_HINT}
 * ${THROWS_DOC}
 */

class DingServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/ding.php' => config_path('ding.log'),
            __DIR__ . '/log/ding.log' =>  storage_path('logs/ding.log'),
        ]);
    }

}