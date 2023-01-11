<?php
/**
 * @Filename:
 * @Description:钉配置
 * @Author: liuweiliang
 * @CreateDate: 2022/11/17 11:33
 * @UpdateDate: 2022/11/17 11:33 By liuweiliang
 */

return [
    'app_key' => env('DING_APP_KEY', ''),//钉app_key
    'app_secret' => env('DING_APP_SECRET', ''),//钉app_secret'

    'event_token' => env('DING_EVENT_TOKEN', ''),//钉事件token
    'event_ase_key' => env('DING_EVENT_ASE_KEY', ''),//钉事件event_ase_key,
    'event_key' => env('DING_EVENT_KEY', ''),//钉事件event_key,

];