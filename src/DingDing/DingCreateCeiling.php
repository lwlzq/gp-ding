<?php

namespace Gp\Ding\DingDing;

use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Dingtalk;
use \Exception;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Tea\Utils\Utils;

use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Models\InteractiveCardCreateInstanceHeaders;
use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Models\PrivateDataValue;
use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Models\InteractiveCardCreateInstanceRequest\cardData;
use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Models\InteractiveCardCreateInstanceRequest;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;

/**
 * @Class DingCreateCeiling
 * @Description:创建 吊顶 实例
 * @CreateDate: 2022/10/28 09:28
 * @UpdateDate: 2022/10/28 09:28 By liuweiliang
 */
class DingCreateCeiling
{

    /**
     * 使用 Token 初始化账号Client
     * @return Dingtalk Client
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
     * @CreateDate: 2022/11/16 10:50
     * @UpdateDate: 2022/11/16 10:50 By liuweiliang
     * @param string $cardTemplateId
     * @param string $openConversationId
     * @param array $receiverUserIdList
     * @param string $outTrackId
     * @param string $robotCode
     * @param array $data
     * @throws BadRequestHttpException
     */
    public static function main(
        string $cardTemplateId = '',
        string $openConversationId = '',
        array  $receiverUserIdList = [],
        string $outTrackId = '',
        string $robotCode = '',
        array $data = [],
    )
    {
        $client = self::createClient();
        $interactiveCardCreateInstanceHeaders = new InteractiveCardCreateInstanceHeaders([]);
        $interactiveCardCreateInstanceHeaders->xAcsDingtalkAccessToken = DingTalkService::getAccessToken();
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
            "privateDataValueKey" => $privateDataValueKey
        ];
        $cardDataCardMediaIdParamMap = [
            "key" => "value"
        ];
        $cardDataCardParamMap = $data;
        $cardData = new cardData([
            "cardParamMap" => $cardDataCardParamMap,
            "cardMediaIdParamMap" => $cardDataCardMediaIdParamMap
        ]);
        $interactiveCardCreateInstanceRequest = new InteractiveCardCreateInstanceRequest([
            "cardTemplateId" => $cardTemplateId,
            "openConversationId" => $openConversationId,
            "receiverUserIdList" => $receiverUserIdList,
            "outTrackId" => $outTrackId,
            "robotCode" => $robotCode,
            "conversationType" => 1,
            "callbackRouteKey" => "",
            "cardData" => $cardData,
            "privateData" => $privateData,
            "chatBotId" => "robotCode",
            "userIdType" => 1,
            "pullStrategy" => false
        ]);
        try {
            $client->interactiveCardCreateInstanceWithOptions($interactiveCardCreateInstanceRequest, $interactiveCardCreateInstanceHeaders, new RuntimeOptions([]));
        } catch (Exception $err) {
            Log::channel('ding')->error('钉创建吊顶错误',['message'=>$err->getMessage(),'line'=>$err->getLine(),'file'=>$err->getFile()]);
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
