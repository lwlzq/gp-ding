# gp-ding
> 在 钉钉 官方 SDK 基础上进行封装，简便使用
```
For Laravel >= 5.8 
    PHP >= 7.4
```

```
1.composer require liuweiliang/groupding

2.config/app/php

    providers[
        Gp\Ding\DingDing\DingServiceProvider::class
    ]
    
    aliases[
        'Api'=> \Gp\Ding\Client\ClientApiFacade::class, 
    ]
    
3.php artisan vendor:publish
```
![img.png](img.png)
```
4.选择对应的服务发布 如上图 就输入 1

5.在 /config/logging.php 内 追加 如下配置 

     'ding'=>[
            'driver' => 'daily',
            'path' => storage_path('logs/ding.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 7,
     ],
     
6.执行 php artisan config:clear
```

### 部分 API 实现 
```     
    生成 access_token
    \Gp\Ding\DingDing\DingTalkService::getAccessToken()
    
    通过手机号 获取 钉Id
    \Gp\Ding\DingDing\DingTalkService::getUserIdByMobile("手机号")
    
    创建普通群 参数可到 本SDK 内自行匹配
    \Gp\Ding\DingDing\DingTalkService::createChat()
    
    创建场景群
    \Gp\Ding\DingDing\DingTalkService::createSceneChat(
            string $owner_user_id = '',群主dingId
            string $user_ids = '',用户dingId
            string $template_id = '',创建群模板Id 钉后台自找
            string $title = '',群名称
            string $uuid = '',唯一标识 推荐 ceil((microtime(true) * 1000)) . Str::uuid(),
            string $icon = '',icon
            int    $show_history_type = 1,是否可以查看群聊天记录 1：可查看 ，，0：不可查看
            int    $searchable = 1,群是否可以被搜索 1不可 ，，0可以
            int    $validation_type = 0,入群是否需要验证：0不需 。。1需要
            int    $mention_all_authority = 0, @all 使用范围  1仅群主可@all，，，0所有人可以
            int    $management_type = 1,群管理类型：0所有人，，， 1群主
            int    $chat_banned_type = 0,是否开启群禁言：0不禁言，，，1禁言
    )
    
    向群内添加用户
    \Gp\Ding\DingDing\DingTalkService::storeChat('群id','dingId1,dingId2')
    
    获取 JSAPI 的 Ticket
    \Gp\Ding\DingDing\DingTalkService::getJsApiTicket()
    
    钉一下
     Gp\Ding\DingDing\Ding::ding(
        array 钉id,
        string 内容
     )
     
     钉电话
     Gp\Ding\DingDing\DingPhone::ding(
        array 钉id,
        string 内容
     )
     
///////////////////////////// 需要先手动(或自动)在群里 更多酷应用 -> 启动机器人后方可使用 (目前后端钉官方不支持自动开启酷应用机器人)////////////////////////////////////////////
      
     普通卡片
     Gp\Ding\DingDing\DingCard::main(
        string $cardTemplateId = '',模板id 钉后台自找
        string $openConversationId = '',群id
        array  $receiverUserIdList = [],可以看到该模板的用户钉id
        string $outTrackId = '',唯一标识 要考虑 高频请求下 唯一
        string $robotCode = '',机器人编码 钉后台自找
        array  $data = [],卡片变量数据 根据钉后台卡片模板内 变量编写
        array  $atOpenIds = [] @xxx 钉id
     )
     
     
     创建吊顶卡片
     Gp\Ding\DingDing\DingCreateCeiling::main(
        string $cardTemplateId = '',模板id 钉后台自找
        string $openConversationId = '',群id
        array  $receiverUserIdList = [],可以看到该模板的用户钉id
        string $outTrackId = '',唯一标识 要考虑 高频请求下 唯一  推荐雪花id  或 ceil((microtime(true) * 1000)) . Str::uuid()
        string $robotCode = '',机器人编码 钉后台自找
        array $data = [],卡片变量数据 根据钉后台卡片模板内 变量编写
     )
     
     启动吊顶卡片
     Gp\Ding\DingDing\DingCreateCeiling::main(
        string $openConversationId = '',群id
        array $receiverUserIdList = [],可以看到该模板的用户钉id
        string $outTrackId = '',唯一标识 要考虑 高频请求下 唯一  推荐雪花id  或 ceil((microtime(true) * 1000)) . Str::uuid()
        string $coolAppCode = '',应用编码 钉后台自找
        int $expiredTime = 0,过期时间
        string $robotCode = ''机器人编码 钉后台自找
     )
```