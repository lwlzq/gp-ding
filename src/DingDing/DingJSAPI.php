<?php

namespace Gp\Ding\DingDing;


/**
 * @Class DingJSAPI
 * @Description: JSAPI
 * @CreateDate: 2022/10/24 13:45
 * @UpdateDate: 2022/10/24 13:45 By liuweiliang
 */
class DingJSAPI
{

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/11/8 16:56
     * @UpdateDate: 2022/11/8 16:56 By liuweiliang
     * @param string $url
     * @return array
     */
    public static function jsapi(string $url): array
    {
        $data['noncestr'] = md5(intval(microtime(true) * 10000));
        $data['timestamp'] = time();
        $data['url'] = urldecode($url);
        $data['signature'] = self::getJsSignature($data);
        $data['agent_id'] = config('dingding.emergency_coordination.agent_id');
        $data['corp_id'] = config('dingding.emergency_coordination.corp_id');
        $data['jsApiList'] = ['biz.chat.toConversationByOpenConversationId'];
        return $data;
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/11/8 16:53
     * @UpdateDate: 2022/11/8 16:53 By liuweiliang
     * @param array $data
     * @return string
     */
    protected static function getJsSignature(array $data):string
    {
        $ticket = DingTalkService::getJsApiTicket();
        $data['jsapi_ticket'] = $ticket['ticket'];
        ksort($data);
        return sha1(urldecode(http_build_query($data, null, '&')));
    }
}
