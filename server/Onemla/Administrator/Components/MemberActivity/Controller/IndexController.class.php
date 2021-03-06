<?php
//
//namespace Components\MemberActivity\Controller;
//
//use Onemla\OnemlaHelper;
//use Onemla\OnemlaRequest;
//use Onemla\ViewController;
//use Components\User\Model\UserModel;
//use Components\User\Model\UserInfoModel;
//use Onemla\SessionViewController;
//use Components\Activity\Model\ActivityModel;
//use Components\Repair\Model\ChannelModel;
//
//class IndexController extends SessionViewController {
//
//    /**
//     * 活动列表
//     */
//    public function index() {
//        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_MEMBER_ACTIVITY);
//        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_MEMBER_ACTIVITY_ONE);
//
//        $model = new ActivityModel();
//        $ChannelModel = new ChannelModel();
//        $info = $model->getMemberList();
//        $channel_list = $ChannelModel->getList();
//        $this->assign('channel_list', $channel_list);
//        $this->assign('list', $info['list']);
//        $this->assign('page_show', $info['page_show']);
////        $this->assign('user_name', $info['user_name']);
//        $this->assign('status',$info['status']);
//        $this->assign('title', $info['title']);
//        $this->assign('channel_id', $info['channel_id']);
//        $this->display();
//    }
//
//    /**
//     * 新增，修改基本信息
//     */
//    public function editPage() {
//
//        $model = new ActivityModel();
//        $ChannelModel = new ChannelModel();
//        $id = OnemlaRequest::getVar("id");
//        $info = $model->where(array("id" => $id))->find();
//        $channel_list = $ChannelModel->getList();
//        $this->assign('channel_list', $channel_list);
//        $this->assign('info', $info);
//        $this->display("edit");
//    }
//
//    /**
//     * 详情
//     */
//    public function detail() {
//        $model = new ActivityModel();
//        $id = OnemlaRequest::getVar("id");
//        $info = $model->where(array("id" => $id))->find();
//
//        // 通过channel_id获取channel_name
//        $c_model = new ChannelModel();
//        $c_id = $info['channel_id'];
//        $c_name_arr=$c_model->where(array("id" => $c_id))->field("channel_name")->find();
//        $info['channel'] = $c_name_arr['channel_name'];
//
//        $this->assign('info', $info);
//        $this->display("detail");
//    }
//
//    /**
//     * 新增，修改基本信息
//     */
//    public function edit() {
//        $model = new ActivityModel();
//        $msg = $model->edit();
//        if ($msg['code'] == "upload_error") {
//            win_savedata_back($msg['msg']);
//        }
//        if ($msg['code'] == "add_success") {
//            wingo($msg['msg'],U('MemberActivity/Index/index'));
//        }
//        if ($msg['code'] == "add_error") {
//            win_savedata_back($msg['msg']);
//        }
//        if ($msg['code'] == "edit_success") {
//            wingo($msg['msg'],U('MemberActivity/Index/index'));
//        }
//        if ($msg['code'] == "edit_error") {
//            win_savedata_back($msg['msg']);
//        }
//    }
//
//    /**
//     * 删除
//     */
//    public function delete() {
//        $model = new ActivityModel();
//        $id = OnemlaRequest::getVar("id");
//        //删除本地图片
//        $rInfo = $model->where(array("id" => $id))->find();
//        $bg_image_url = delAdminFile($rInfo['bg_image'], $pathUrl = "Res/Uploads/activity/"); //删除本地图
//        $logo_url = delAdminFile($rInfo['logo'], $pathUrl = "Res/Uploads/activity/"); //删除本地图
//        unlink($bg_image_url);
//        unlink($logo_url);
//
//        $result = $model->where(array("id" => $id))->delete();
//        if ($result) {
//            $this->httpReturn(1, $id, "删除成功");
//        } else {
//            $this->httpReturn(2, $id, "删除失败");
//        }
//    }
//
//}
