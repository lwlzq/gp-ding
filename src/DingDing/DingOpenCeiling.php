<?php

// This file is auto-generated, don't edit it. Thanks.
namespace Gp\Ding\DingDing;

use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Dingtalk;
use \Exception;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Tea\Utils\Utils;
use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Models\TopboxOpenHeaders;
use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Models\TopboxOpenRequest;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @Class DingOpenCeiling
 * @Description:酷应用 开启吊顶
 * @CreateDate: 2022/10/26 18:01
 * @UpdateDate: 2022/10/26 18:01 By liuweiliang
 */
class DingOpenCeiling {

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
     * @CreateDate: 2022/10/26 17:58
     * @UpdateDate: 2022/10/26 17:58 By liuweiliang
     * @param string $openConversationId 群id
     * @param array $receiverUserIdList 访问user_id
     * @param string $outTrackId 唯一标识一张卡片的外部ID，最大长度64 我们保持与 群id 一致
     * @param string $coolAppCode 应用code
     * @param int $expiredTime 过期时间
     * @param string $robotCode 机器人code
     * @throws BadRequestHttpException
     */
    public static function main(
        string $openConversationId = '',
        array $receiverUserIdList = [],
        string $outTrackId = '',
        string $coolAppCode = '',
        int $expiredTime = 0,
        string $robotCode = ''

    ){
        $client = self::createClient();
        $topboxOpenHeaders = new TopboxOpenHeaders([]);
        $topboxOpenHeaders->xAcsDingtalkAccessToken = DingTalkService::getAccessToken();
        $topboxOpenRequest = new TopboxOpenRequest([
            "openConversationId" => $openConversationId,//"cidzhgBYqlRIFSmKXXyxP94Fg==",//群id
            "receiverUserIdList" => $receiverUserIdList,//['16635583079518404','083726034927687023'],//user_id
            "outTrackId" => $outTrackId,//"ggg",//
            "coolAppCode" => $coolAppCode,//"COOLAPP-1-101ED8B90373212B27100009",//appcode
            "expiredTime" => $expiredTime,//1850042969000,
            "robotCode" => $robotCode,//"dingb42lr523h4g44nio",
            "platforms" => "ios|mac|android|win",

        ]);
        try {
            $client->topboxOpenWithOptions($topboxOpenRequest, $topboxOpenHeaders, new RuntimeOptions([]));
        } catch (Exception $err) {
            \Log::channel('ding')->error('钉开启吊顶错误',['message'=>$err->getMessage(),'line'=>$err->getLine(),'file'=>$err->getFile()]);
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
