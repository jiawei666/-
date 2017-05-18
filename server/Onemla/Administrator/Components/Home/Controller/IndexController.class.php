<?php

namespace Components\Home\Controller;

use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Components\User\Model\UserModel;
use Components\User\Model\UserInfoModel;
use Onemla\SessionViewController;
use Components\Home\Model\Live_chatModel;
use Components\Repair\Model\RepairOrderModel;
use Components\MemberLive_bsn\Model\Live_roomModel;

class IndexController extends SessionViewController {

    public function __construct()
    {//防止会员通过更改url跳转到管理员界面
        parent::__construct();
        if(OnemlaHelper::getUser()->user_type==3){
            $this->redirect('Member/Index/index');
            exit;
        }
    }

    
    /**
     * 用户列表
     */
    public function index() {
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_ADMIN);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_ADMIN_ONE);

        $model = new UserModel();
        $info = $model->getList();
        $this->assign('list', $info['list']);
        $this->assign('page_show', $info['page_show']);
        $this->assign('user_name', $info['user_name']);
        $this->assign('phone', $info['phone']);
        $this->assign('date_start', $info['date_start']);
        $this->assign('date_end', $info['date_end']);
        $this->display();
    }
    
    /**
     * 用户详情
     */
    public function userDetails() {
        $model = new UserModel();
        $user_id = OnemlaRequest::getVar("user_id");
        $info = $model->where(array("id"=>$user_id))->find();
        $this->assign('info',$info);
        $this->display("user_details");
    }

    /**
     * 锁定，解锁
     */
    public function freeze() {
        $user_id = OnemlaRequest::getVar("user_id");
        $state = OnemlaRequest::getVar("state");//1.正常  2.锁定
        $model = new UserModel();
        if ($state == "1") {
            $phone = OnemlaRequest::getVar('phone');
            $date1 = '12345678';
            $arr = array($date1);/*发送短信内容*/
            $res1 = sendTemplateSMS($phone,$arr,171306);//发送短信
            if($res1['res']==1){
                $data = array(
                    'state' => 2,
                );
                $msg = $model->where(array("id" => $user_id))->save($data);
                if ($msg == "1") {
                    $this->httpReturn(1, $msg, '已成功冻结当前用户');
                } else {
                    $this->httpReturn(2, $msg, '冻结失败');
                }
            }else{
                $this->httpReturn(2,'',$res1[1]);
                exit;
            }
        } elseif($state == "2") {
            $data = array(
                'state' => 1,
            );
            $msg = $model->where(array("id" => $user_id))->save($data);
            if ($msg == "1") {
                $this->httpReturn(1, $msg, '已成功解冻当前用户');
            } else {
                $this->httpReturn(2, $msg, '解冻失败');
            }
        }

    }
    
    
    /**
     * 删除
     */
    public function delete() {
        $id = OnemlaRequest::getVar("user_id");

        $liveModel = new Live_roomModel();//获取直播商id(bsn_id)
        $bsnModel = M('live_bsn');
        $bsn_id = $bsnModel -> where(array('user_id'=>$id))->getField('id');

        $channelModel = M('live_channel');//根据bsn_id删除频道
        $channelArr = $channelModel->where(array('bsn_id'=>$bsn_id))->field('id')->select();
        foreach($channelArr as $value){
            $liveModel->delChannel($value['id']);
        }

        $roomModel = M('live_room');//根据bsn_id删除直播间活动
        $roomArr = $roomModel->where(array('r_bsn_id'=>$bsn_id))->field('room_id')->select();
        foreach($roomArr as $value){
            $liveModel->delRoom($value['room_id']);
        }

        $tagsModel = M('live_tags');//根据bsn_id删除话题
        $tagsArr = $tagsModel->where(array('bsn_id'=>$bsn_id))->field('id')->select();
        foreach($tagsArr as  $value){
            $liveModel->delTags($value['id']);
        }

        $bsnFollowModel = M('live_bsn_follow');//根据bsn_id删除直播商关注信息
        $bsnFollowModel->where(array('bsn_id'=>$bsn_id))->delete();
        $bsnModel -> where(array('user_id'=>$id))->delete();//删除直播商

        $repairModel = M('repair_order');//删除工单
        $repairDelModel = new RepairOrderModel();
        $repairArr = $repairModel->where(array("user_id"=>$id))->select();
        foreach($repairArr as $value){
            $repairDelModel->delRepair($value['id']);
        }

        $audit_submit_time_model = M('audit_submit_times');//删除审核次数信息
        $audit_submit_time_model->where(array("user_id"=>$id))->delete();

        $UserInfoModel = new UserInfoModel();//删除用户详细信息
        $UserInfoModel->delUserInfo($id);

        $model = new UserModel();//删除用户
        $result = $model->where(array("id"=>$id))->delete();
        if ($result) {
            $this->httpReturn(1, $id, "删除成功");
        } else {
            $this->httpReturn(2, $id, "删除失败");
        }
    }

    /**
     * 密码设置
     */
    public function setPassword() {
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_ADMIN);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_ADMIN_THREE);

        $this->display("set_password");
    }

    /*
     * 聊天界面
     * */
//    public function chat(){
//        $model = new Live_chatModel();
//        $this->chat_list = $model->get_list();
//        $this->to_user_id = OnemlaRequest::getVar('from_user_id');
//        $userModel = M('user');
//        $this->username = $userModel->where(array('id'=>OnemlaRequest::getVar('from_user_id')))->getField('user_name');
//        $this->display('chat');
//    }
//
//    /**
//     * 聊天信息提交
//     */
//    public function chat_add(){
//        $model = new Live_chatModel();
//        $resutl = $model->add_list(2);
//        if ($resutl) {
//            winback('提交成功！');
//        }else{
//            win_savedata_back('提交失败，请重新提交！');
//        }
//    }





}
