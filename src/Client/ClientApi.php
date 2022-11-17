<?php

namespace Gp\Ding\Client;

use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use GuzzleHttp\Promise;

/**
 * @Class ClientApi
 * @Description:
 * @CreateDate: 2022/10/24 13:45
 * @UpdateDate: 2022/10/24 13:45 By liuweiliang
 */
class ClientApi
{
    private $client;
    private $options;
    private $url;
    private $method;
    private $response;

    private $log;

    private $trans_id;

    public function __construct()
    {
        $this->log = new ClientLog();
        $this->options['headers'] = config('micro.headers') ? config('micro.headers') : [];
        $this->trans_id = $this->uuid();
        $this->options['headers']['trans_id'] = $this->trans_id;
        $this->client = new \GuzzleHttp\Client($this->options);
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:45
     * @UpdateDate: 2022/10/24 13:45 By liuweiliang
     * @return string
     */
    public function uuid()
    {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid = substr($chars, 0, 8) . '-'
            . substr($chars, 8, 4) . '-'
            . substr($chars, 12, 4) . '-'
            . substr($chars, 16, 4) . '-'
            . substr($chars, 20, 12);
        return $uuid;
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:45
     * @UpdateDate: 2022/10/24 13:45 By liuweiliang
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function rollback()
    {
        $response = $this->client->request("POST", $this->url, $this->options);
        try {

            $this->beforeLog($this->url, "GET", $this->options);

            $response = $this->client->request("GET", $this->url, $this->options);

            $this->response = new ClientApiResponse($response);

            $this->afterLog($this->url, "GET", $this->options);

        } catch (GuzzleRequestException $e) {
            throw new ClientApiRequestException($e, $this);
        }
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:45
     * @UpdateDate: 2022/10/24 13:45 By liuweiliang
     * @return ClientLog
     */
    public function log()
    {
        return $this->log;
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:45
     * @UpdateDate: 2022/10/24 13:45 By liuweiliang
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:45
     * @UpdateDate: 2022/10/24 13:45 By liuweiliang
     * @param $query
     * @return $this
     */
    function query($query)
    {
        $this->checkOptions();
        $this->options['query'] = $query;
        return $this;
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:45
     * @UpdateDate: 2022/10/24 13:45 By liuweiliang
     * @param $data
     * @return $this
     */
    function json($data)
    {
        $this->checkOptions();
        $this->options['json'] = $data;
        return $this;
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:45
     * @UpdateDate: 2022/10/24 13:45 By liuweiliang
     * @param $data
     * @return $this
     */
    function form_params($data)
    {
        $this->checkOptions();
        $this->options['form_params'] = $data;
        return $this;
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:45
     * @UpdateDate: 2022/10/24 13:45 By liuweiliang
     * @param $data
     * @return $this
     */
    function multipart($data)
    {
        $this->options['multipart'] = $data;
        return $this;
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:44
     * @UpdateDate: 2022/10/24 13:44 By liuweiliang
     * @param $extend_header
     * @return $this
     */
    public function addHeaders($extend_header)
    {
        $this->options['headers'] = array_merge($extend_header, $this->options['headers']);
        return $this;
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:44
     * @UpdateDate: 2022/10/24 13:44 By liuweiliang
     */
    function checkOptions()
    {
        $header = [];
        if ($this->options['headers']) {
            $header = $this->options['headers'];
        }
        if (!empty($this->options))
            $this->options = [];
        $this->options['headers'] = $header;
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:44
     * @UpdateDate: 2022/10/24 13:44 By liuweiliang
     * @return ClientApiResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    function run()
    {
        try {
            $this->beforeLog($this->url, $this->method, $this->options);
            $response = $this->client->request($this->method, $this->url, $this->options);
            $this->response = new ClientApiResponse($response);
            $this->afterLog($this->url, $this->method, $this->options);
        } catch (GuzzleRequestException $e) {
            throw new ClientApiRequestException($e, $this);
        }
        return $this->response;
    }


    /**************sync***************/

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:43
     * @UpdateDate: 2022/10/24 13:43 By liuweiliang
     * @param String $uri
     * @return $this
     */
    function get(string $uri)
    {
        $this->method = 'GET';
        $this->url = $uri;
        return $this;
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:43
     * @UpdateDate: 2022/10/24 13:43 By liuweiliang
     * @param String $uri
     * @return $this
     */
    function post(string $uri)
    {
        $this->method = 'POST';
        $this->url = $uri;
        return $this;
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:43
     * @UpdateDate: 2022/10/24 13:43 By liuweiliang
     * @param String $uri
     * @return $this
     */
    function put(string $uri)
    {
        $this->method = 'PUT';
        $this->url = $uri;
        return $this;
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:43
     * @UpdateDate: 2022/10/24 13:43 By liuweiliang
     * @param String $uri
     * @return $this
     */
    function patch(string $uri)
    {
        $this->method = 'PATCH';
        $this->url = $uri;
        return $this;
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:43
     * @UpdateDate: 2022/10/24 13:43 By liuweiliang
     * @param String $uri
     * @return $this
     */
    function delete(string $uri)
    {
        $this->method = 'DELETE';
        $this->url = $uri;
        return $this;
    }

    /*************async*************/

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:43
     * @UpdateDate: 2022/10/24 13:43 By liuweiliang
     * @param String $uri
     * @param array $params
     * @return Promise\PromiseInterface
     */
    function getAsync(string $uri, array $params)
    {
        $promise = $this->client->getAsync($uri);
        return $promise;
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:43
     * @UpdateDate: 2022/10/24 13:43 By liuweiliang
     * @param String $uri
     * @param array $params
     * @return Promise\PromiseInterface
     */
    function postAsync(string $uri, array $params)
    {
        $promise = $this->client->getAsync($uri);
        return $promise;
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:43
     * @UpdateDate: 2022/10/24 13:43 By liuweiliang
     * @param String $uri
     * @param array $params
     * @return Promise\PromiseInterface
     */
    function putAsync(string $uri, array $params)
    {
        $promise = $this->client->getAsync($uri);
        return $promise;
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:44
     * @UpdateDate: 2022/10/24 13:44 By liuweiliang
     * @param String $uri
     * @param array $params
     * @return Promise\PromiseInterface
     */
    function deleteAsync(string $uri, array $params)
    {
        $promise = $this->client->getAsync($uri);
        return $promise;
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:44
     * @UpdateDate: 2022/10/24 13:44 By liuweiliang
     * @param array $promises
     * @return Array
     * @throws \Throwable
     */
    function promiseRun(array $promises): array
    {
        //todo 异步执行的暂时不用
        $res = Promise\unwrap($promises);

        foreach ($res as $key => $item) {
            $ret[$key] = new ClientApiResponse($item);
        }
        return $ret;
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:44
     * @UpdateDate: 2022/10/24 13:44 By liuweiliang
     * @param $url
     * @param $method
     * @param $options
     */
    protected function beforeLog($url, $method, $options)
    {
        $this->startTiem = microtime(true);
        $this->log()->debug('---------------new request-------------------');
        $this->log()->debug("url: $url");
        $this->log()->debug("Method:$method,  请求地址 $url");
        $this->log()->debug('数据 ', $options);

    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:44
     * @UpdateDate: 2022/10/24 13:44 By liuweiliang
     * @param $url
     * @param $method
     * @param $options
     */
    protected function afterLog($url, $method, $options)
    {
        $this->log()->debug('---------------new response-------------------');
        $this->log()->debug('数据 ', $options);
        $this->log()->debug('数据 ', [$this->response->getHeadersData()]);
        $this->log()->debug('数据 ', [$this->response->getJson()]);
        $endTime = microtime(true);
        $runTime = ceil(($endTime - $this->startTiem) * 1000);
        $this->log()->debug("--$url---------------------");
        $this->log()->debug("--执行时间:$runTime ms---------------");
        $this->log()->debug("----------------请求结束--------------------");
    }
}
