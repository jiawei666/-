<?php
//
//namespace Components\Activity\Controller;
//
//use Components\Repair\Model\ChannelModel;
//use Onemla\OnemlaHelper;
//use Onemla\OnemlaRequest;
//use Onemla\ViewController;
//use Components\User\Model\UserModel;
//use Components\User\Model\UserInfoModel;
//use Onemla\SessionViewController;
//use Components\Activity\Model\ActivityModel;
//
//class IndexController extends SessionViewController {
//
//    public function __construct()
//    {//防止会员通过更改url跳转到管理员界面
//        parent::__construct();
//        if(OnemlaHelper::getUser()->user_type==3){
//            $this->redirect('Member/Index/index');
//            exit;
//        }
//    }
//
//    /**
//     * 活动列表
//     */
//    public function index() {
//        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_ACTIVITY) ;
//      OnemlaHelper::setActived(OnemlaHelper::ACTIVED_ACTIVITY_ONE);
//        $model = new ActivityModel();
//        $info = $model->getList();
//
//        $this->assign('list', $info['list']);
//        $this->assign('page_show', $info['page_show']);
//        $this->assign('status',$info['status']);
//        $this->assign('user_name', $info['user_name']);
//        $this->assign('title', $info['title']);
//        $this->display();
//    }
//
//    /**
//     * 新增，修改基本信息
//     */
//    public function editPage() {
//        $model = new ActivityModel();
//        $id = OnemlaRequest::getVar("id");
//        $info = $model->where(array("id" => $id))->find();
//        $this->assign('info', $info);
//
//        $channel_model = new ChannelModel();
//        $channel_info = $channel_model->field('id,channel_name')->select();
//        $this->assign('channel_info',$channel_info);//读取tb_channel表的id,channel_name数据,输出到模板
//
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
//        $this->display('detail');
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
//            wingo($msg['msg'],U('Activity/Index/index'));
//        }
//        if ($msg['code'] == "add_error") {
//            win_savedata_back($msg['msg']);
//        }
//        if ($msg['code'] == "edit_success") {
//            wingo($msg['msg'],U('Activity/Index/index'));
//        }
//        if ($msg['code'] == "edit_error") {
//            win_savedata_back($msg['msg']);
//        }
//    }
//
//    /**
//     * 审核
//     */
//    public function audit() {
//        $id = OnemlaRequest::getVar("id");
//        $status = OnemlaRequest::getVar("audit_res"); //1.正常  2.锁定
//        $reason = OnemlaRequest::getVar('reason');
//        $model = new ActivityModel();
//        $data = array(
//            'status' => $status,
//            'reason' => $reason,
//        );
//        $msg = $model->where(array("id" => $id))->save($data);
//        if ($msg == "1") {
//            $this->httpReturn(1, $msg, 'success');
//        } else {
//            $this->httpReturn(2, $msg, 'error');
//        }
//    }
//
//    /**
//     * 删除
//     */
//    public function delete() {
//        $model = new ActivityModel();
//        $id = OnemlaRequest::getVar("id");
//
//        //删除活动同时删除图片
//        $info = $model->where(array("id" => $id))->field('bg_image,logo')->find();
//        unlink($_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Res/Uploads/activity/'.$info['logo']);
//        unlink($_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Res/Uploads/activity/'.$info['bg_image']);
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
