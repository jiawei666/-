<?php

namespace Components\Cli\Model;
use Onemla\OnemlaHelper;
use Onemla\OnemlaModel;
use Onemla\OnemlaRequest;
use Services\Message\MessageService;

class MessageModel extends OnemlaModel{

    protected $tableName = 'message_push';

    /*推送状态*/
    const PUSH_STATUS_NOT   = 0;    //未推送
    const PUSH_STATUS_YES   = 1;    //已推送
    const PUSH_STATUS_DELETE   = 2; //已删除

    const PUSH_TARGET_ALL   = 0;    //全部用户
    const PUSH_TARGET_GROUP   = 1;    //分组用户

    public function checkSendMsg(){
        $pResult = $this ->where(array(
            'push_status' => array('EQ',self::PUSH_STATUS_NOT)
        )) -> select();
        if(empty($pResult)) exit;

        $nowTime = time();
        foreach($pResult as $v){
            if($v['push_time'] <= $nowTime){     //推送时间到
                $messService = new MessageService();
                $pData = array(
                    'mess_title' => $v['push_title'],
                    'mess_type' => $v['push_content_type'],
                    'mess_content' => $v['push_content'],
                    'mess_status' => $messService::MESSAGE_STATUS_NORMAL,
                    'mess_sender' => $v['push_sender'],
                    'push_id' => $v['push_id'],
                    'create_time' => $nowTime
                );
                if($v['push_target_type'] == self::PUSH_TARGET_ALL){
                    $uWhere = null;
                }elseif($v['push_target_type'] == self::PUSH_TARGET_GROUP){
                    $uWhere['user_group_id'] = array('in',$v['push_target']);
                }else{
                    exit('用户推送目标出错！');
                }
                $mResult = $messService -> pushMessage($uWhere,$pData);
                if($mResult){
                    $this -> where(array(
                        'push_id' => array('EQ',$v['push_id'])
                    )) -> save(array(
                        'push_status' => self::PUSH_STATUS_YES,
                        'last_time' => $nowTime
                    ));
                }else{
                    exit('信息推送失败！');
                }
            }
        }
    }

}