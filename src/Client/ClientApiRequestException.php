<?php

namespace Gp\Ding\Client;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;

/**
 * @Class ClientApiRequestException
 * @Description:
 * @CreateDate: 2022/10/24 13:42
 * @UpdateDate: 2022/10/24 13:42 By liuweiliang
 */
class ClientApiRequestException extends GuzzleRequestException
{

    public $body;

    public function __construct(GuzzleRequestException $exception = null, ClientApi $clientApi)
    {
        $url = $clientApi->getUrl();

        if ($exception === null) {
            $this->message = "ClientApi Protocol not defined for " . $url;
            return;
        } elseif ($exception instanceof ConnectException) {
            $msg = "ClientApi can not connect: " . $url;
        } elseif ($exception instanceof RequestException && $exception->getCode() == 0) {
            $msg = "ClientApi cURL error url malformed: " . $url;
        } else {
            $msg = $exception->getMessage();
        }
        return parent::__construct($msg, $exception->getRequest(), $exception->getResponse(), $exception->getPrevious());
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:42
     * @UpdateDate: 2022/10/24 13:42 By liuweiliang
     * @return mixed|string|null
     */
    public function getData()
    {
        if (!$this->hasResponse()) {
            return null;
        }
        $data = json_decode($this->getResponse()->getBody()->__toString(), 1);
        if (json_last_error() == JSON_ERROR_NONE) {
            return $data;
        } else {
            return $this->getResponse()->getBody()->__toString();
        }
    }
}
