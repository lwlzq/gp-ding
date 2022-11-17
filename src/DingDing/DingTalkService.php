<?php
/**
 * @Filename:
 * @Description:
 * @Author: liuweiliang
 * @CreateDate: 2022/10/24 14:00
 * @UpdateDate: 2022/10/24 14:00 By liuweiliang
 */


namespace Gp\Ding\DingDing;


use Gp\Ding\Contracts\DingTalk;
use Gp\Ding\Contracts\DingUri;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * @Class DingTalkService
 * @Description:钉的服务层
 * @CreateDate: 2022/10/24 13:45
 * @UpdateDate: 2022/10/24 13:45 By liuweiliang
 */
class DingTalkService implements DingTalk
{
    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/29 14:16
     * @UpdateDate: 2022/10/29 14:16 By liuweiliang
     * @return string
     */
    public static function getAccessToken(): string
    {
        if (!Cache::get('ding_ding_access_token')) {
            $data = \Api::get(DingUri::GET_DING_TALK_ACCESS_TOKEN_URI)
                ->query(["appkey" => config('ding.app_key'), 'appsecret' => config('ding.app_secret')])
                ->run()
                ->getData();
            Cache::set(config('app.name') . '_access_token', $data['access_token'], $data['expires_in']);
        }
        return Cache::get(config('app.name') . '_access_token');
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/28 21:27
     * @UpdateDate: 2022/10/28 21:27 By liuweiliang
     * @param string $mobile
     * @param bool $is_search_by_mobile
     * @param string $access_token
     * @return array
     * @throws \Exception
     */
    public static function getUserIdByMobile(string $mobile = '', bool $is_search_by_mobile = true): array
    {
        /**
         *
         * "errcode" => 0
         * "errmsg" => "ok"
         * "result" => array:1 [▼
         * "userid" => "16635583079518404"
         * ]
         *  "request_id" => "16m17ru6x7ksz"
         * ]
         */
        return \Api::post(DingUri::GET_USER_ID_BY_MOBILE_URI . "?access_token=" . DingTalkService::getAccessToken())
            ->form_params(['mobile' => $mobile])
            ->run()
            ->getData();
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/28 21:28
     * @UpdateDate: 2022/10/28 21:28 By liuweiliang
     * @param string $access_token
     * @param string $name 群名称
     * @param string $owner 群主id
     * @param array $useridlist 群成员id
     * @param int $showHistoryType 是否可以查看群聊天记录 1：可查看 ，，0：不可查看
     * @param int $searchable 群是否可以被搜索 1不可 ，，0可以
     * @param int $validationType 入群是否需要验证：0不需 。。1需要
     * @param $mentionAllAuthority @all 使用范围  1仅群主可@all，，，0所有人可以
     * @param int $managementType 群管理类型：0所有人，，， 1群主
     * @param int $chatBannedType 是否开启群禁言：0不禁言，，，1禁言
     * @return array
     * @throws \Exception
     */
    public static function createChat(string $name = '', string $owner = '', array $useridlist = [], int $showHistoryType = 1, int $searchable = 1, int $validationType = 0, $mentionAllAuthority = 0, int $managementType = 1, int $chatBannedType = 0): array
    {
        // TODO: Implement createChat() method.
        return \Api::post(DingUri::CREATE_CHAT_URI . '?access_token=' . DingTalkService::getAccessToken())
            ->json(([
                'name' => $name,
                'owner' => $owner,
                'useridlist' => $useridlist,
                'showHistoryType' => $showHistoryType,
                'searchable' => $searchable,
                'validationType' => $validationType,
                'mentionAllAuthority' => $mentionAllAuthority,
                'managementType' => $managementType,
                'chatBannedType' => $chatBannedType
            ]))->run()->getData();
    }


    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/28 21:29
     * @UpdateDate: 2022/10/28 21:29 By liuweiliang
     * @param string $owner_user_id
     * @param string $user_ids
     * @param string $template_id
     * @param string $title
     * @param string $uuid
     * @param string $icon
     * @param int $show_history_type
     * @param int $searchable
     * @param int $validation_type
     * @param int $mention_all_authority
     * @param int $management_type
     * @param int $chat_banned_type
     * @return array
     * @throws \Exception
     */
    public static function createSceneChat(
        string $owner_user_id = '',
        string $user_ids = '',
        string $template_id = '',
        string $title = '',
        string $uuid = '',
        string $icon = '',
        int    $show_history_type = 1,
        int    $searchable = 1,
        int    $validation_type = 0,
        int    $mention_all_authority = 0,
        int    $management_type = 1,
        int    $chat_banned_type = 0
    ): array
    {
        // TODO: Implement createChat() method.
        return \Api::post(DingUri::CREATE_SCENE_CHAT_URI . '?access_token=' . DingTalkService::getAccessToken())
            ->json(([
                'template_id' => $template_id,
                'title' => $title,
                'owner_user_id' => $owner_user_id,
                'user_ids' => $user_ids,
                'uuid' => $uuid ?? microtime(true) . Str::uuid(),
                'icon' => $icon,
                'show_history_type' => $show_history_type,
                'searchable' => $searchable,
                'validation_type' => $validation_type,
                'mention_all_authority' => $mention_all_authority,
                'management_type' => $management_type,
                'chat_banned_type' => $chat_banned_type
            ]))->run()->getData();
    }

    /**
     * @FunctionName:
     * @Description:向群里添加用户
     * @Author: liuweiliang
     * @CreateDate: 2022/11/1 17:03
     * @UpdateDate: 2022/11/1 17:03 By liuweiliang
     * @param string $open_conversation_id 群id
     * @param string $user_ids 用户id
     * @return array
     */

    public static function storeChat(string $open_conversation_id = '', string $user_ids = '',): array
    {
        // TODO: Implement addChat() method.
        return \Api::post(DingUri::STORE_CHAT_URI . '?access_token=' . DingTalkService::getAccessToken())
            ->json(([
                'open_conversation_id' => $open_conversation_id,
                'user_ids' => $user_ids
            ]))->run()->getData();
    }

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/11/8 14:42
     * @UpdateDate: 2022/11/8 14:42 By liuweiliang
     * @return array
     */
    public static function getJsApiTicket(): array
    {
        return \Api::GET(DingUri::GET_JS_API_TICKET_URI . '?access_token=' . DingTalkService::getAccessToken())
            ->json([])->run()->getData();
    }


    /**
     * @FunctionName:
     * @Description:免登陆
     * @Author: liuweiliang
     * @CreateDate: 2022/11/9 18:49
     * @UpdateDate: 2022/11/9 18:49 By liuweiliang
     * @param string $code
     * @return array
     */
    public static function noLoginByCode(string $code = ''): array
    {
        return \Api::POST(DingUri::NO_LOGIN_BY_CODE_URI . '?access_token=' . DingTalkService::getAccessToken())
            ->form_params(['code' => $code])->run()->getData();
    }
}
