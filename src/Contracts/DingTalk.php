<?php

namespace Gp\Ding\Contracts;
/**
 * @Class DingTalk
 * @Description:阿里钉钉聊天
 * @CreateDate: 2022/10/18 09:12
 * @UpdateDate: 2022/10/18 09:12 By liuweiliang
 */
interface DingTalk
{
    /**
     * @FunctionName:获取钉钉 access_token
     * @Description:access_token
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 13:59
     * @UpdateDate: 2022/10/24 13:59 By liuweiliang
     * @return string
     */
    public static function getAccessToken(): string;

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 14:24
     * @UpdateDate: 2022/10/24 14:24 By liuweiliang
     * @param string $mobile 手机号
     * @param bool $is_search_by_mobile 是否支持通过手机号搜索专属帐号。 true：支持。 fasle：不支持。
     * @param string $access_token
     * @return array
     */
    public static function getUserIdByMobile(string $mobile = '', bool $is_search_by_mobile = true): array;


    /**
     * @FunctionName:createChat
     * @Description:创建群会话
     * @Author: liuweiliang
     * @CreateDate: 2022/10/24 15:00
     * @UpdateDate: 2022/10/24 15:00 By liuweiliang
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
     */
    public static function createChat(string $name = '', string $owner = '', array $useridlist = [], int $showHistoryType = 1, int $searchable = 1, int $validationType = 0, $mentionAllAuthority = 0, int $managementType = 1, int $chatBannedType = 0): array;


    /**
     * @FunctionName:
     * @Description:场景群
     * @Author: liuweiliang
     * @CreateDate: 2022/10/25 11:06
     * @UpdateDate: 2022/10/25 11:06 By liuweiliang
     * @param string $access_token
     * @param string $template_id
     * @param string $title
     * @param string $owner_user_id
     * @param string $user_ids
     * @param int $show_history_type
     * @param int $searchable
     * @param int $validation_type
     * @param int $mention_all_authority
     * @param int $management_type
     * @param string $uuid
     * @param string $icon
     * @param int $chat_banned_type
     * @return array
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
    ): array;


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
    public static function storeChat(
        string $open_conversation_id = '',
        string $user_ids = '',
    ): array;

    public static function getJsApiTicket():array;


    /**
     * @FunctionName:
     * @Description:免登
     * @Author: liuweiliang
     * @CreateDate: 2022/11/9 18:50
     * @UpdateDate: 2022/11/9 18:50 By liuweiliang
     * @param string $code
     * @return array
     */
    public static function noLoginByCode(string $code = ''):array;
}
