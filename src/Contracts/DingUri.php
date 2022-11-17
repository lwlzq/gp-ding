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
    const GET_DING_TALK_ACCESS_TOKEN_URI = 'https://oapi.dingtalk.com/gettoken';
    const GET_USER_ID_BY_MOBILE_URI = 'https://oapi.dingtalk.com/topapi/v2/user/getbymobile';
    const CREATE_CHAT_URI = 'https://oapi.dingtalk.com/chat/create';
    const CREATE_SCENE_CHAT_URI = 'https://oapi.dingtalk.com/topapi/im/chat/scenegroup/create';
    const STORE_CHAT_URI = 'https://oapi.dingtalk.com/topapi/im/chat/scenegroup/member/add';
    const GET_JS_API_TICKET_URI = 'https://oapi.dingtalk.com/get_jsapi_ticket';
    const NO_LOGIN_BY_CODE_URI = 'https://oapi.dingtalk.com/topapi/v2/user/getuserinfo';
    const IM_CHAT_MESSAGE_URI = 'https://oapi.dingtalk.com/topapi/im/chat/scencegroup/message/send_v2';
}
