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


/**
 * @Class DingCard
 * @Description:普通卡片
 * @CreateDate: 2022/10/26 18:01
 * @UpdateDate: 2022/10/26 18:01 By liuweiliang
 */
class DingCard
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
     * @param string[] $args
     * @return void
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
        $atOpenIds = ['175548342820844423' => '陈体顺','116264463320844567'=>'33'];
        $privateDataValueKeyCardMediaIdParamMap = [
            "title" => "title1"
        ];
        $privateDataValueKeyCardParamMap = [
            "butddton" => "hello"
        ];
        $privateDataValueKey = new PrivateDataValue([
            "cardParamMap" => $privateDataValueKeyCardParamMap,
            "cardMediaIdParamMap" => $privateDataValueKeyCardMediaIdParamMap
        ]);
        $privateData = [
            "16635583079518404" => $privateDataValueKey
        ];
        $cardDataCardMediaIdParamMap = $data;
        $cardDataCardParamMap = [
            "desc" => "https://www.baidu.com"
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
            "callbackRouteKey" => $callbackRouteKey ?? 'http://127.0.0.1:8000/api/aaa',
            "cardData" => $cardData,
            "privateData" => $privateData,
            "chatBotId" => $chatBotId ?? '',
            "userIdType" => 1,
            "atOpenIds" => $atOpenIds,
            "cardOptions" => $cardOptions,
            "pullStrategy" => false
        ]);
//        dd($sendInteractiveCardRequest);

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
