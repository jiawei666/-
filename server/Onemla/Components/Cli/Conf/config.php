<?php

return array(
    'cron' => array(
        array(
            "func_name"  => "User.update",  //每天定时
            "cron_type"  => 1,
            "cron_time"  => '04:00:00',
            "space_time" => '0000-00-01 00:00:00',
            "priority"  => 100,
            "note"      => "更新影响力等数值",
            "type"      => 1,
        ),
        array(
            "func_name"  => "User.update_m",  //一个月
            "cron_type"  => 3,
            "cron_time"  => '03:55:00',
            "space_time" => '1',
            "priority"  => 100,
            "note"      => "更新影响力等数值",
            "type"      => 1,
        ),
        array(
            "func_name"  => "Message.checkSendMsg",  //一分钟
            "cron_type"  => 0,
            "cron_time"  => '00:00:00',
            "space_time" => '0000-00-00 00:01:00',
            "priority"  => 100,
            "note"      => "检查发送推送信息",
            "type"      => 1,
        ),
        array(
            "func_name"  => "Task.updateUserIntegral",  //每天定时
            "cron_type"  => 1,
            "cron_time"  => '04:00:00',
            "space_time" => '0000-00-01 00:00:00',
            "priority"  => 100,
            "note"      => "结束任务时查询用户的积分",
            "type"      => 1,
        )
    ),
);