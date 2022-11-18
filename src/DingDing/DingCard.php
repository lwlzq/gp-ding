<?php

namespace Gp\Ding\DingDing;

use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Dingtalk;
use \Exception;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Tea\Utils\Utils;

use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Models\SendInteractiveCardHeaders;
use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Models\SendInteractiveCardRequest\cardOptions;
use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Models\PrivateDataValue;
use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Models\SendInteractiveCardRequest\cardData;
use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Models\SendInteractiveCardRequest;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


/**
 * @Class DingCard
 * @Description:普通卡片
 * @CreateDate: 2022/10/26 18:01
 * @UpdateDate: 2022/10/26 18:01 By liuweiliang
 */
class DingCard
{
    /**
     * @FunctionName:
     * @Description:Token 初始化账号Client
     * @Author: liuweiliang
     * @CreateDate: 2022/11/17 14:12
     * @UpdateDate: 2022/11/17 14:12 By liuweiliang
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
     * @CreateDate: 2022/11/17 14:22
     * @UpdateDate: 2022/11/17 14:22 By liuweiliang
     * @param string $cardTemplateId
     * @param string $openConversationId
     * @param array $receiverUserIdList
     * @param string $outTrackId
     * @param string $robotCode
     * @param array $data
     * @param array $atOpenIds
     */
    public static function main(
        string $cardTemplateId = '',
        string $openConversationId = '',
        array  $receiverUserIdList = [],
        string $outTrackId = '',
        string $robotCode = '',
        array  $data = [],
        array  $atOpenIds = []

    )
    {
        $client = self::createClient();
        $sendInteractiveCardHeaders = new SendInteractiveCardHeaders([]);
        $sendInteractiveCardHeaders->xAcsDingtalkAccessToken = DingTalkService::getAccessToken();
        $cardOptions = new cardOptions([
            "supportForward" => true
        ]);

        $privateDataValueKeyCardMediaIdParamMap = [
            "key" => "value"
        ];
        $privateDataValueKeyCardParamMap = [
            "key" => "value"
        ];
        $privateDataValueKey = new PrivateDataValue([
            "cardParamMap" => $privateDataValueKeyCardParamMap,
            "cardMediaIdParamMap" => $privateDataValueKeyCardMediaIdParamMap
        ]);
        $privateData = [
            "dingId" => $privateDataValueKey
        ];
        $cardDataCardMediaIdParamMap = $data;
        $cardDataCardParamMap = [
            "key" => "value"
        ];
        $cardData = new cardData([
            "cardParamMap" => $cardDataCardParamMap,
            "cardMediaIdParamMap" => $cardDataCardMediaIdParamMap
        ]);
        $sendInteractiveCardRequest = new SendInteractiveCardRequest([
            "cardTemplateId" => $cardTemplateId,
            "openConversationId" => $openConversationId,
            "receiverUserIdList" => array_values($receiverUserIdList),
            "outTrackId" => $outTrackId ?? microtime(true) . Str::uuid(),
            "robotCode" => $robotCode,
            "conversationType" => 1,
            "callbackRouteKey" => $callbackRouteKey ?? '',
            "cardData" => $cardData,
            "privateData" => $privateData,
            "chatBotId" => $chatBotId ?? '',
            "userIdType" => 1,
            "atOpenIds" => $atOpenIds,
            "cardOptions" => $cardOptions,
            "pullStrategy" => false
        ]);

        try {
            $client->sendInteractiveCardWithOptions($sendInteractiveCardRequest, $sendInteractiveCardHeaders, new RuntimeOptions([]));
        } catch (Exception $err) {
            \Log::channel('ding')->error('钉普通卡片错误',['message'=>$err->getMessage(),'line'=>$err->getLine(),'file'=>$err->getFile()]);
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
