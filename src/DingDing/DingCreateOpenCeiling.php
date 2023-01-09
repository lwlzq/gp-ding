<?php

namespace Gp\Ding\DingDing;

use AlibabaCloud\SDK\Dingtalk\Vim_2_0\Dingtalk;
use Gp\Ding\Contracts\DingUri;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use \Exception;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Tea\Utils\Utils;
use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dingtalk\Vim_2_0\Models\CreateTopboxHeaders;
use AlibabaCloud\SDK\Dingtalk\Vim_2_0\Models\CreateTopboxRequest\cardSettings;
use AlibabaCloud\SDK\Dingtalk\Vim_2_0\Models\UnionIdPrivateDataMapValue;
use AlibabaCloud\SDK\Dingtalk\Vim_2_0\Models\UserIdPrivateDataMapValue;
use AlibabaCloud\SDK\Dingtalk\Vim_2_0\Models\CreateTopboxRequest\cardData;
use AlibabaCloud\SDK\Dingtalk\Vim_2_0\Models\CreateTopboxRequest;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;

class DingCreateOpenCeiling
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
     * @CreateDate: 2023/1/9 16:06
     * @UpdateDate: 2023/1/9 16:06 By liuweiliang
     * @param string $cardTemplateId
     * @param string $outTrackId
     * @param string $callbackRouteKey
     * @param array $cardDataCardParamMap
     * @param array $userIdPrivateDataMapValueKeyCardParamMap
     * @param array $unionIdPrivateDataMapValueKeyCardParamMap
     * @param bool $pullStrategy
     * @param int $conversationType
     * @param string $openConversationId
     * @param string $userId
     * @param string $unoinId
     * @param string $robotCode
     * @param string $coolAppCode
     * @param string $groupTemplateId
     * @param array $receiverUserIdList
     * @param array $receiverUnionIdList
     * @param int $expiredTime
     * @param string $platforms
     * 参考文档:https://open-dev.dingtalk.com/apiExplorer?spm=ding_open_doc.document.0.0.13933834MvadYf#/?devType=org&api=im_2.0%23CreateTopbox
     */
    public static function main(
        string $cardTemplateId = '',
        string $outTrackId = '',
        string $callbackRouteKey = '',
        array  $cardDataCardParamMap = [],
        array  $userIdPrivateDataMapValueKeyCardParamMap = [],
        array  $unionIdPrivateDataMapValueKeyCardParamMap = [],
        bool   $pullStrategy = false,
        int    $conversationType = DingUri::OPEN,
        string $openConversationId = '',
        string $userId = '',
        string $unoinId = '',
        string $robotCode = '',
        string $coolAppCode = '',
        string $groupTemplateId = '',
        array  $receiverUserIdList = [],
        array  $receiverUnionIdList = [],
        int    $expiredTime = 1850042969000,
        string $platforms = "ios|win",

    )
    {
        $client = self::createClient();
        $createTopboxHeaders = new CreateTopboxHeaders([]);
        $createTopboxHeaders->xAcsDingtalkAccessToken = DingTalkService::getAccessToken();
        $cardSettings = new cardSettings([
            "pullStrategy" => $pullStrategy
        ]);
        $unionIdPrivateDataMapValueKeyCardParamMap = empty($unionIdPrivateDataMapValueKeyCardParamMap) ? ["key" => "xxx"] : $unionIdPrivateDataMapValueKeyCardParamMap;

        $unionIdPrivateDataMapValueKey = new UnionIdPrivateDataMapValue([
            "cardParamMap" => $unionIdPrivateDataMapValueKeyCardParamMap
        ]);
        $unionIdPrivateDataMap = [
            "unionIdPrivateDataMapValueKey" => $unionIdPrivateDataMapValueKey
        ];

        $userIdPrivateDataMapValueKeyCardParamMap = empty($userIdPrivateDataMapValueKeyCardParamMap) ? ["key" => "xxx"] : $userIdPrivateDataMapValueKeyCardParamMap;

        $userIdPrivateDataMapValueKey = new UserIdPrivateDataMapValue([
            "cardParamMap" => $userIdPrivateDataMapValueKeyCardParamMap
        ]);
        $userIdPrivateDataMap = [
            "userIdPrivateDataMapValueKey" => $userIdPrivateDataMapValueKey
        ];

        $cardDataCardParamMap = empty($cardDataCardParamMap) ? ["key" => "xxx"] : $cardDataCardParamMap;

        $cardData = new cardData([
            "cardParamMap" => $cardDataCardParamMap
        ]);
        $createTopboxRequest = new CreateTopboxRequest([
            "cardTemplateId" => $cardTemplateId,
            "outTrackId" => $outTrackId,
            "callbackRouteKey" => $callbackRouteKey,
            "cardData" => $cardData,
            "userIdPrivateDataMap" => $userIdPrivateDataMap,
            "unionIdPrivateDataMap" => $unionIdPrivateDataMap,
            "cardSettings" => $cardSettings,
            "conversationType" => $conversationType,
            "openConversationId" => $openConversationId,
            "userId" => $userId,
            "unoinId" => $unoinId,
            "robotCode" => $robotCode,
            "coolAppCode" => $coolAppCode,
            "groupTemplateId" => $groupTemplateId,
            "receiverUserIdList" => $receiverUserIdList,
            "receiverUnionIdList" => $receiverUnionIdList,
            "expiredTime" => $expiredTime,
            "platforms" => $platforms
        ]);
        try {
            $client->createTopboxWithOptions($createTopboxRequest, $createTopboxHeaders, new RuntimeOptions([]));
        } catch (Exception $err) {
            \Log::channel('ding')->error('创建并启用吊顶错误', ['message' => $err->getMessage(), 'line' => $err->getLine(), 'file' => $err->getFile()]);

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
