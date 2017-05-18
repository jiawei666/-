<?php

namespace Components\MemberRepair\Controller;

use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Components\User\Model\UserModel;
use Components\User\Model\UserInfoModel;
use Onemla\SessionViewController;
use Components\Repair\Model\RepairOrderModel;
use Components\Repair\Model\RepairReplyModel;
use Components\Repair\Model\RepairRecordModel;

class IndexController extends SessionViewController {

    /**
     * 工单列表
     */
    public function index() {
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_MEMBER_REPAIR);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_MEMBER_REPAIR_ONE);
        $model = new RepairOrderModel();
        $info = $model->getMemberList();

        foreach($info['list'] as $key => $value){//截取-工单说明-字符串
            $introduction = strip_tags($info['list'][$key]['introduction']) ;
            $info['list'][$key]['introduction'] = msubstr($introduction,0,40,"utf-8",true);
        }
        
        $this->assign('list', $info['list']);
        $this->assign('page_show', $info['page_show']);
        $this->assign('user_name', $info['user_name']);
        $this->assign('type', $info['type']);
        $this->assign('repair_number', $info['repair_number']);
        $this->display();
    }

    /**
     * 新增，修改基本信息
     */
    public function editPage() {
        $model = new RepairOrderModel();
        $id = OnemlaRequest::getVar("id");
        $info = $model->where(array("id" => $id))->find();
        $this->assign('info', $info);
        $this->display("edit");
    }

    /**
     * 详情
     */
    public function detail() {
        $model = new RepairOrderModel();
        $id = OnemlaRequest::getVar("id");
        $info = $model->where(array("id" => $id))->find();

        $this->assign('info', $info);
        $this->display("detail");
    }

    /**
     * 新增，修改基本信息
     */
    public function edit() {
        $model = new RepairOrderModel();
        $msg = $model->edit();
        if ($msg['code']=="upload_error") {
            win_savedata_back($msg['msg']);
        }
        if ($msg['code'] == "add_success") {
           wingo($msg['msg'],U('MemberRepair/Index/index'));
        }
        if ($msg['code'] == "add_error") {
            win_savedata_back($msg['msg']);
        }
        if ($msg['code'] == "edit_success") {
            wingo($msg['msg'],U('MemberRepair/Index/index'));
        }
        if ($msg['code'] == "edit_error") {
            win_savedata_back($msg['msg']);
        }
    }

    /**
     * 删除
     */
    public function delete() {
        $model = new RepairOrderModel();
        $id = OnemlaRequest::getVar("id");
        $result = $model->delRepair($id);
        if ($result) {
            $this->httpReturn(1, $id, "删除成功");
        } else {
            $this->httpReturn(2, $id, "删除失败");
        }
    }

    /**
     * 回复界面
     */
    public function record(){
        $order_model = new RepairOrderModel();
        $this->repair_detail = $order_model->get_detail();
        $record_model = new RepairRecordModel();
        $this->record_list = $record_model->get_list();
        $this->display('record');
    }

    /**
     * 回复提交
     */
    public function record_add(){
        $record_model = new RepairRecordModel();
        $resutl = $record_model->add_list(1);
        if ($resutl) {
            winback('提交成功！');
        }else{
            win_savedata_back('提交失败，请重新提交！');
        }
    }

}
