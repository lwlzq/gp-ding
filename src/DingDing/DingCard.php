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
use Gp\Ding\Contracts\DingUri;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


/**
 * @Class DingCard
 * @Description:普通发送卡片到群
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
     * @param string $cardTemplateId 卡片id
     * @param string $openConversationId 群id
     * @param array $receiverUserIdList 可以查看卡片的用户的钉id
     * @param string $outTrackId 唯一标识
     * @param string $robotCode 机器人code
     * @param array $cardDataCardParamMap 公共的 卡片模板内容替换参数，普通文本类型。
     * @param array $cardDataCardMediaIdParamMap 公共的 卡片模板内容替换参数，多媒体类型。
     * @param array $atOpenIds @的人
     * @param string $callbackRouteKey 回调地址
     * @param array $privateDataValueKeyCardMediaIdParamMap 私有的 卡片模板内容替换参数，多媒体类型
     * @param array $privateDataValueKeyCardParamMap 私有的 卡片模板内容替换参数，普通文本类型
     * @param int $conversationType 0单聊 1群聊
     * @param int $userIdType userid模式 unionId模式
     * @param bool $pullStrategy true 开启卡片纯拉模式   false 不开启卡片纯拉模式

     */
    public static function main(
        string $cardTemplateId = '',
        string $openConversationId = '',
        array  $receiverUserIdList = [],
        string $outTrackId = '',
        string $robotCode = '',
        array  $cardDataCardParamMap = [],
        array  $cardDataCardMediaIdParamMap = [],
        array  $atOpenIds = [],
        string $callbackRouteKey = '',
        array  $privateDataValueKeyCardMediaIdParamMap = [],
        array  $privateDataValueKeyCardParamMap = [],
        int    $conversationType = DingUri::OPEN,
        int    $userIdType = DingUri::OPEN,
        bool   $pullStrategy = false

    )
    {
        $client = self::createClient();
        $sendInteractiveCardHeaders = new SendInteractiveCardHeaders([]);
        $sendInteractiveCardHeaders->xAcsDingtalkAccessToken = DingTalkService::getAccessToken();
        $cardOptions = new cardOptions([
            "supportForward" => true
        ]);
        $atOpenIds = empty($atOpenIds) ? ["key" => "test"] : $atOpenIds;
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
        $cardDataCardParamMap = empty($cardDataCardParamMap) ? ["key" => "test"] : $cardDataCardParamMap;
        $cardData = new cardData([
            "cardParamMap" => $cardDataCardParamMap,
            "cardMediaIdParamMap" => $cardDataCardMediaIdParamMap
        ]);

        $sendInteractiveCardRequest = new SendInteractiveCardRequest([
            "cardTemplateId" => $cardTemplateId,
            "openConversationId" => $openConversationId,
            "receiverUserIdList" => array_values($receiverUserIdList),
            "outTrackId" => $outTrackId ?? DingTalkService::outTrackId(),
            "robotCode" => $robotCode,
            "conversationType" => $conversationType,
            "callbackRouteKey" => $callbackRouteKey ?? '',
            "cardData" => $cardData,
            "privateData" => $privateData,
            "chatBotId" => $chatBotId ?? '',
            "userIdType" => $userIdType,
            "atOpenIds" => $atOpenIds,
            "cardOptions" => $cardOptions,
            "pullStrategy" => $pullStrategy
        ]);

        try {
            $client->sendInteractiveCardWithOptions($sendInteractiveCardRequest, $sendInteractiveCardHeaders, new RuntimeOptions([]));
        } catch (Exception $err) {
            \Log::channel('ding')->error('钉普通卡片错误', ['message' => $err->getMessage(), 'line' => $err->getLine(), 'file' => $err->getFile()]);
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
