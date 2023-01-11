<?php

namespace Gp\Ding\DingDing;

use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Dingtalk;
use \Exception;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Tea\Utils\Utils;
use Illuminate\Support\Facades\Log;
use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Models\InteractiveCardCreateInstanceHeaders;
use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Models\PrivateDataValue;
use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Models\InteractiveCardCreateInstanceRequest\cardData;
use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Models\InteractiveCardCreateInstanceRequest;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;
use Gp\Ding\Contracts\DingUri;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @Class DingCreateCeiling
 * @Description:酷应用创建吊顶
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
     * @CreateDate: 2023/1/9 15:50
     * @UpdateDate: 2023/1/9 15:50 By liuweiliang
     * @param string $cardTemplateId
     * @param string $openConversationId
     * @param string $callbackRouteKey
     * @param array $receiverUserIdList
     * @param string $outTrackId
     * @param string $robotCode
     * @param array $cardDataCardParamMap
     * @param string $chatBotId
     * @param array $privateDataValueKeyCardMediaIdParamMap
     * @param array $privateDataValueKeyCardParamMap
     * @param array $cardDataCardMediaIdParamMap
     * @param int $conversationType
     * @param int $userIdType
     * @param bool $pullStrategy
     */
    public static function main(
        string $cardTemplateId = '',
        string $openConversationId = '',
        string $callbackRouteKey = '',
        array  $receiverUserIdList = [],
        string $outTrackId = '',
        string $robotCode = '',
        array  $cardDataCardParamMap = [],
        string $chatBotId = '',
        array  $privateDataValueKeyCardMediaIdParamMap = [],
        array  $privateDataValueKeyCardParamMap = [],
        array  $cardDataCardMediaIdParamMap = [],
        int    $conversationType = DingUri::OPEN, //会话类型： - 1：群聊 - 2：单聊助手
        int    $userIdType = DingUri::OPEN,
        bool   $pullStrategy = false
    )
    {
        $client = self::createClient();
        $interactiveCardCreateInstanceHeaders = new InteractiveCardCreateInstanceHeaders([]);
        $interactiveCardCreateInstanceHeaders->xAcsDingtalkAccessToken = DingTalkService::getAccessToken();

        $privateDataValueKeyCardMediaIdParamMap = empty($privateDataValueKeyCardMediaIdParamMap) ? ["key" => "test"] : $privateDataValueKeyCardMediaIdParamMap;
        $privateDataValueKeyCardParamMap = empty($privateDataValueKeyCardParamMap) ? ["key" => "test"] : $privateDataValueKeyCardParamMap;

        $privateDataValueKey = new PrivateDataValue([
            "cardParamMap" => $privateDataValueKeyCardParamMap,
            "cardMediaIdParamMap" => $privateDataValueKeyCardMediaIdParamMap
        ]);
        $privateData = [
            "privateDataValueKey" => $privateDataValueKey
        ];

        $cardDataCardMediaIdParamMap = empty($cardDataCardMediaIdParamMap) ? ["key" => "test"] : $cardDataCardMediaIdParamMap;

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
            "conversationType" => $conversationType,
            "callbackRouteKey" => $callbackRouteKey,
            "cardData" => $cardData,
            "privateData" => $privateData,
            "chatBotId" => $chatBotId,
            "userIdType" => $userIdType,
            "pullStrategy" => $pullStrategy
        ]);
        try {
            $client->interactiveCardCreateInstanceWithOptions($interactiveCardCreateInstanceRequest, $interactiveCardCreateInstanceHeaders, new RuntimeOptions([]));
        } catch (Exception $err) {
            Log::channel('ding')->error('钉创建吊顶错误', ['message' => $err->getMessage(), 'line' => $err->getLine(), 'file' => $err->getFile()]);
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
