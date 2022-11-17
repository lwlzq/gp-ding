<?php

namespace Gp\Ding\Client;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Class ClientApiResponse
 * @Description:
 * @CreateDate: 2022/10/24 12:55
 * @UpdateDate: 2022/10/24 12:55 By liuweiliang
 */
class ClientApiResponse extends Response
{
    private $res;
    private $contents;
    const SUCCESS_CODE = 0;
    const SUCCESS_MESSAGE = ['ok', 'success', 'OK', 'SUCCESS'];


    function __construct(Response $res)
    {
        $this->res = $res;
        $this->contents = $res->getBody()->getContents();
        parent::__construct($res->getStatusCode(), $res->getHeaders(), $res->getBody());
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:41
     * @UpdateDate: 2022/10/24 13:41 By liuweiliang
     * @return string
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:41
     * @UpdateDate: 2022/10/24 13:41 By liuweiliang
     * @return Response
     */
    public function getRes()
    {
        return $this->res;
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:42
     * @UpdateDate: 2022/10/24 13:42 By liuweiliang
     * @return array
     */
    public function getHeadersData()
    {
        return $this->res->getHeaders();
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:42
     * @UpdateDate: 2022/10/24 13:42 By liuweiliang
     * @return mixed
     */
    public function getJson()
    {
        $result = json_decode($this->contents, true);
        return $result;
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:42
     * @UpdateDate: 2022/10/24 13:42 By liuweiliang
     * @return mixed
     * @throws AuthorizationException
     */
    public function getData()
    {
        $data = $this->getJson();
        try {
            if ($data['errcode'] != self::SUCCESS_CODE || !in_array($data['errmsg'], self::SUCCESS_MESSAGE)) {
                throw new BadRequestHttpException($data['errmsg']);
            }
        } catch (ClientException $exception) {
            switch ($exception->getCode()) {
                case 403:
                    throw new AuthorizationException($exception->getMessage());
                case 404:
                    throw new NotFoundHttpException($exception->getMessage(), $e);
                default:
                    throw new HttpException($exception->getCode(), $exception->getMessage(), $exception);
            }
        } catch (ServerException $exception) {
            //5xx
            throw new HttpException(502, '内部服务错误');
        } catch (RequestException $exception) {
            //发送网络错误(连接超时、DNS错误等)
            throw new HttpException(504, '内部网络错误');
        } catch (TransferException $exception) {
            //其余异常
            throw new HttpException(500, '内部未知错误');
        }
        return $data;
    }
}
