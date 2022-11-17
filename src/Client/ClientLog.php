<?php

namespace Gp\Ding\Client;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * @Class ClientLog
 * @Description:
 * @CreateDate: 2022/10/24 13:41
 * @UpdateDate: 2022/10/24 13:41 By liuweiliang
 */
class ClientLog extends Logger
{
    public $log;

    function __construct()
    {
        parent::__construct("client");
        $this->pushHandler(new StreamHandler(storage_path('logs/client.log')));
    }
}
