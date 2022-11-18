<?php

namespace Gp\Ding\DingDing;

use AlibabaCloud\SDK\Dingtalk\Vexclusive_1_0\Dingtalk;
use \Exception;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Tea\Utils\Utils;

use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dingtalk\Vexclusive_1_0\Models\SendPhoneDingHeaders;
use AlibabaCloud\SDK\Dingtalk\Vexclusive_1_0\Models\SendPhoneDingRequest;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @Class DingPhone
 * @Description: 钉电话
 * @CreateDate: 2022/10/24 13:45
 * @UpdateDate: 2022/10/24 13:45 By liuweiliang
 */
class DingPhone {

    /**
     * 使用 Token 初始化账号Client
     * @return Dingtalk Client
     */
    public static function createClient(){
        $config = new Config([]);
        $config->protocol = "https";
        $config->regionId = "central";
        return new Dingtalk($config);
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/11/16 10:44
     * @UpdateDate: 2022/11/16 10:44 By liuweiliang
     * @param array $user_ids
     * @param string $content
     */
    public static function main(array $user_ids = [], string $content = ''){
        $client = self::createClient();
        $sendPhoneDingHeaders = new SendPhoneDingHeaders([]);
        $sendPhoneDingHeaders->xAcsDingtalkAccessToken = DingTalkService::getAccessToken();
        $sendPhoneDingRequest = new SendPhoneDingRequest([
            "userids" => $user_ids,
            "content" => $content
        ]);
        try {
            $client->sendPhoneDingWithOptions($sendPhoneDingRequest, $sendPhoneDingHeaders, new RuntimeOptions([]));
        }
        catch (Exception $err) {
            \Log::channel('ding')->error('钉电话',['message'=>$err->getMessage(),'line'=>$err->getLine(),'file'=>$err->getFile()]);

            if (!($err instanceof TeaError)) {
                $err = new TeaError([], $err->getMessage(), $err->getCode(), $err);
            }
            if (!Utils::empty_($err->code) && !Utils::empty_($err->message)) {
                // err 中含有 code 和 message 属性，可帮助开发定位问题
                throw new BadRequestHttpException($err->message);
            }
        }
    }
}
