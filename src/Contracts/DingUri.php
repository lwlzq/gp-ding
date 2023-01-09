<?php

namespace Gp\Ding\Contracts;
/**
 * @Class Uri
 * @Description:请求单方的uri 统一放这里
 * @CreateDate: 2022/10/18 09:12
 * @UpdateDate: 2022/10/18 09:12 By liuweiliang
 */
interface DingUri
{
    const OPEN = 1;
    const DEFAULT = 0;
    const GET_DING_TALK_ACCESS_TOKEN_URI = 'https://oapi.dingtalk.com/gettoken';
    const GET_USER_ID_BY_MOBILE_URI = 'https://oapi.dingtalk.com/topapi/v2/user/getbymobile';
    const CREATE_CHAT_URI = 'https://oapi.dingtalk.com/chat/create';
    const CREATE_SCENE_CHAT_URI = 'https://oapi.dingtalk.com/topapi/im/chat/scenegroup/create';
    const STORE_CHAT_URI = 'https://oapi.dingtalk.com/topapi/im/chat/scenegroup/member/add';
    const GET_JS_API_TICKET_URI = 'https://oapi.dingtalk.com/get_jsapi_ticket';
}
