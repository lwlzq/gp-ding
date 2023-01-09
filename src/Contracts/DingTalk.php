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
     * @param string $name 群名称
     * @param string $owner 群主id
     * @param array $userid_list 群成员id
     * @param int $show_history_type 是否可以查看群聊天记录 1：可查看 ，，0：不可查看
     * @param int $searchable 群是否可以被搜索 1不可 ，，0可以
     * @param int $validation_type 入群是否需要验证：0不需 。。1需要
     * @param $mention_all_authority @all 使用范围  1仅群主可@all，，，0所有人可以
     * @param int $management_type 群管理类型：0所有人，，， 1群主
     * @param int $chat_banned_type 是否开启群禁言：0不禁言，，，1禁言
     * @return array
     */
    public static function createChat(
        string $name = '',
        string $owner = '',
        array $userid_list = [],
        int $show_history_type = DingUri::DEFAULT,
        int $searchable = DingUri::OPEN,
        int $validation_type = DingUri::DEFAULT,
        int $mention_all_authority = DingUri::DEFAULT,
        int $management_type = DingUri::OPEN,
        int $chat_banned_type = DingUri::DEFAULT
    ): array;


    /**
     * @FunctionName:
     * @Description:创建场景群
     * @Author: liuweiliang
     * @CreateDate: 2022/10/25 11:06
     * @UpdateDate: 2022/10/25 11:06 By liuweiliang
     * @param string $template_id 场景群模板
     * @param string $title 群名称
     * @param string $owner_user_id 群主钉id
     * @param string $user_ids 群内用户钉id
     * @param int $show_history_type 是否可以查看群聊天记录 1：可查看 ，，0：不可查看
     * @param int $searchable 群是否可以被搜索 1不可 ，，0可以
     * @param int $validation_type 入群是否需要验证：0不需 。。1需要
     * @param int $mention_all_authority @all 使用范围  1仅群主可@all，，，0所有人可以
     * @param int $management_type 群管理类型：0所有人，，， 1群主
     * @param string $uuid 唯一标识
     * @param string $icon 群icon
     * @param int $chat_banned_type 是否开启群禁言：0不禁言，，，1禁言
     * @return array
     */
    public static function createSceneChat(
        string $owner_user_id = '',
        string $user_ids = '',
        string $template_id = '',
        string $title = '',
        string $uuid = '',
        string $icon = '',
        int    $show_history_type = DingUri::OPEN,
        int    $searchable = DingUri::OPEN,
        int    $validation_type = DingUri::DEFAULT,
        int    $mention_all_authority = DingUri::DEFAULT,
        int    $management_type = DingUri::OPEN,
        int    $chat_banned_type = DingUri::DEFAULT
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
    public static function storeChat(string $open_conversation_id = '', string $user_ids = '',): array;


    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2023/1/9 11:33
     * @UpdateDate: 2023/1/9 11:33 By liuweiliang
     * @return array
     */
    public static function getJsApiTicket():array;


    /**
     * @FunctionName:outTrackId
     * @Description:群唯一outTrackId
     * @Author: liuweiliang
     * @CreateDate: 2023/1/9 11:42
     * @UpdateDate: 2023/1/9 11:42 By liuweiliang
     * @return string
     */
    public static function outTrackId():string;

    /**
     * @FunctionName:
     * @Description:
     * @Author: liuweiliang
     * @CreateDate: 2023/1/9 16:56
     * @UpdateDate: 2023/1/9 16:56 By liuweiliang
     * @param string $ceiling_card_id 吊顶id
     * @param string $open_conversation_id 群id
     * @param string $out_track_id 唯一标识
     * @param array $card_param_map 数据
     * @param string $group_template_id
     * @param int $conversation_type
     * @return mixed
     */
    public static function createOpenCeiling(string $ceiling_card_id = '', string $open_conversation_id = '', string $out_track_id = '', array $card_param_map = [], string $group_template_id = '', int $conversation_type = DingUri::OPEN);

}
