<?php

namespace Gp\Ding\DingDing;

use AlibabaCloud\SDK\Dingtalk\Vexclusive_1_0\Dingtalk;
use AlibabaCloud\SDK\Dingtalk\Vexclusive_1_0\Models\SendAppDingHeaders;
use AlibabaCloud\SDK\Dingtalk\Vexclusive_1_0\Models\SendAppDingRequest;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Tea\Utils\Utils;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;
use Darabonba\OpenApi\Models\Config;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @Class Ding
 * @Description: 钉一下
 * @CreateDate: 2022/10/24 13:45
 * @UpdateDate: 2022/10/24 13:45 By liuweiliang
 */
class Ding
{
    /**
     * @FunctionName:
     * @Description: 使用 Token 初始化账号Client
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 16:09
     * @UpdateDate: 2022/10/24 16:09 By liuweiliang
     * @return Dingtalk
     */
    public static function createClient()
    {
        $config = new Config([]);
        $config->protocol = "https";
        $config->regionId = "central";
        return new Dingtalk($config);
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 16:07
     * @UpdateDate: 2022/10/24 16:07 By liuweiliang
     * @param array $user_ids
     * @param string $content
     * @param string $access_token
     */
    public static function main(array $user_ids = [], string $content = '')
    {
        \Log::channel('ding')->error('钉一下aaa');

        $client = self::createClient();
        $sendAppDingHeaders = new SendAppDingHeaders([]);
        $sendAppDingHeaders->xAcsDingtalkAccessToken = DingTalkService::getAccessToken();
        $sendAppDingRequest = new SendAppDingRequest(
            [
                'userids' => $user_ids,
                "content" => $content
            ]
        );
        try {
            $client->sendAppDingWithOptions($sendAppDingRequest, $sendAppDingHeaders, new RuntimeOptions([]));
        } catch (\Exception $err) {
            \Log::channel('ding')->error('钉一下',['message'=>$err->getMessage(),'line'=>$err->getLine(),'file'=>$err->getFile()]);
            if (!($err instanceof TeaError)) {
                $err = new TeaError([], $err->getMessage(), $err->getCode(), $err);
            }

            if (!Utils::empty_($err->code) && !Utils::empty_($err->message)) {
                throw new BadRequestHttpException($err->message);
                // err 中含有 code 和 message 属性，可帮助开发定位问题
            }
        }
    }
}
