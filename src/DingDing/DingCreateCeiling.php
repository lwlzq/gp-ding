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
     * @throws ServiceException
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
            "bp" => "1"
        ];
        $privateDataValueKeyCardParamMap = [
            "bp" => "2"
        ];
        $privateDataValueKey = new PrivateDataValue([
            "cardParamMap" => $privateDataValueKeyCardParamMap,
            "cardMediaIdParamMap" => $privateDataValueKeyCardMediaIdParamMap
        ]);
        $privateData = [
            "privateDataValueKey" => $privateDataValueKey
        ];
        $cardDataCardMediaIdParamMap = [
            "updated_at" => $data['updated_at']
        ];
        $cardDataCardParamMap = $data;
//            [
//            "tem" => $tem ?? '36度',
//            "bp" => $bp ?? '血压',
//            "breath" => $breath ?? '呼吸',
//            'rate' => $rate ?? '心率',
//        ];
        $cardData = new cardData([
            "cardParamMap" => $cardDataCardParamMap,
            "cardMediaIdParamMap" => $cardDataCardMediaIdParamMap
        ]);
        $interactiveCardCreateInstanceRequest = new InteractiveCardCreateInstanceRequest([
            "cardTemplateId" => $cardTemplateId,//"ecd6cb24-8588-401f-8ddd-1ba847002bb8",
            "openConversationId" => $openConversationId,//"cidzhgBYqlRIFSmKXXyxP94Fg==",
            "receiverUserIdList" => $receiverUserIdList,//['16635583079518404','083726034927687023'],
            "outTrackId" => $outTrackId,//"ggg",
            "robotCode" => $robotCode,//"dingb42lr523h4g44nio",
            "conversationType" => 1,
//            "callbackRouteKey" => "faxxxx",
            "cardData" => $cardData,
//            "privateData" => $privateData,
//            "chatBotId" => "robotCode",
//            "userIdType" => 1,
//            "pullStrategy" => false
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
