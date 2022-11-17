<?php

namespace Gp\Ding\Client;

use Illuminate\Support\Facades\Facade;

/**
 * @Class ClientApiFacade
 * @Description:
 * @CreateDate: 2022/11/17 10:32
 * @UpdateDate: 2022/11/17 10:32 By liuweiliang
 */
class ClientApiFacade extends Facade
{
    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 11:15
     * @UpdateDate: 2022/10/24 11:15 By liuweiliang
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ClientApi';
    }
}
