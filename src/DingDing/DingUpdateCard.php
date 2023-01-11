<?php

namespace Gp\Ding\DingDing;

use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Dingtalk;
use Gp\Ding\Contracts\DingUri;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use \Exception;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Tea\Utils\Utils;
use Illuminate\Support\Facades\Log;
use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Models\UpdateInteractiveCardHeaders;
use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Models\UpdateInteractiveCardRequest\cardOptions;
use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Models\PrivateDataValue;
use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Models\UpdateInteractiveCardRequest\cardData;
use AlibabaCloud\SDK\Dingtalk\Vim_1_0\Models\UpdateInteractiveCardRequest;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;


/**
 * @Class DingUpdateCard
 * @Description:更新卡片(吊顶卡片和普通卡片)
 * @CreateDate: 2022/10/26 18:01
 * @UpdateDate: 2022/10/26 18:01 By liuweiliang
 */
class DingUpdateCard
{
    /**
     * @FunctionName:
     * @Description:初始化账号Client
     * @Author: liuweiliang
     * @CreateDate: 2022/11/17 15:51
     * @UpdateDate: 2022/11/17 15:51 By liuweiliang
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
     * @CreateDate: 2023/1/9 15:21
     * @UpdateDate: 2023/1/9 15:21 By liuweiliang
     * @param string $outTrackId 创建卡片时的唯一标识
     * @param array $cardDataCardParamMap 公共的卡片模板内容替换参数，普通文本类型。
     * @param array $cardDataCardMediaIdParamMap 公共的卡片模板内容替换参数，多媒体类型。
     * @param array $privateDataValueKeyCardParamMap 私有的卡片模板内容替换参数，普通文本类型。
     * @param array $privateDataValueKeyCardMediaIdParamMap 私有的卡片模板内容替换参数，多媒体类型。
     * @param bool $updateCardDataByKey 按key更新cardData数据，不填默认覆盖更新
     * @param bool $updatePrivateDataByKey 按key更新privateData用户数据，不填默认覆盖更新
     * @param int $userIdType 1：userid模式（默认值） 2：unionId模式
     * 参考文档:https://open-dev.dingtalk.com/apiExplorer?spm=ding_open_doc.document.0.0.2f61722fcOdOsi#/?devType=org&api=im_1.0%23UpdateInteractiveCard
     */
    public static function main(
        string $outTrackId = '',
        array  $cardDataCardParamMap = [],
        array  $cardDataCardMediaIdParamMap = [],
        array  $privateDataValueKeyCardParamMap = [],
        array  $privateDataValueKeyCardMediaIdParamMap = [],
        bool   $updateCardDataByKey = false,
        bool   $updatePrivateDataByKey = false,
        int    $userIdType = DingUri::OPEN,
    )
    {
        $client = self::createClient();
        $updateInteractiveCardHeaders = new UpdateInteractiveCardHeaders([]);
        $updateInteractiveCardHeaders->xAcsDingtalkAccessToken = DingTalkService::getAccessToken();
        $cardOptions = new cardOptions([
            "updateCardDataByKey" => $updateCardDataByKey,
            "updatePrivateDataByKey" => $updatePrivateDataByKey
        ]);

        $privateDataValueKeyCardParamMap = empty($privateDataValueKeyCardParamMap) ? ["key" => "test"] : $privateDataValueKeyCardParamMap;
        $privateDataValueKeyCardMediaIdParamMap = empty($privateDataValueKeyCardMediaIdParamMap) ? ["key" => "test"] : $privateDataValueKeyCardMediaIdParamMap;

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
        $updateInteractiveCardRequest = new UpdateInteractiveCardRequest([
            "outTrackId" => $outTrackId,
            "cardData" => $cardData,
            "privateData" => $privateData,
            "userIdType" => $userIdType,
            "cardOptions" => $cardOptions
        ]);
        try {
            $client->updateInteractiveCardWithOptions($updateInteractiveCardRequest, $updateInteractiveCardHeaders, new RuntimeOptions([]));
        } catch (Exception $err) {
            Log::channel('ding')->error('钉普通卡片更新错误', ['message' => $err->getMessage(), 'line' => $err->getLine(), 'file' => $err->getFile()]);

            if (!($err instanceof TeaError)) {
                $err = new TeaError([], $err->getMessage(), $err->getCode(), $err);
            }
            if (!Utils::empty_($err->code) && !Utils::empty_($err->message)) {
                // err 中含有 code 和 message 属性，可帮助开发定位问题
                throw new BadRequestHttpException($err->getMessage());
            }
        }
    }
}
