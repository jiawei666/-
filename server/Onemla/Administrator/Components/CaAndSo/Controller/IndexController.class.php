<?php

namespace Components\CaAndSo\Controller;

use Components\CaAndSo\Model\CaseModel;
use Components\Repair\Model\ChannelModel;
use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Components\User\Model\UserModel;
use Components\User\Model\UserInfoModel;
use Onemla\SessionViewController;
use Components\Activity\Model\ActivityModel;

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
     * 案例列表
     */
    public function index() {
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_CAANDSO) ;
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_CAANDSO_ONE);

        $model = new CaseModel();
        $info = $model->getList();

        foreach($info['list'] as $key => $value){//截取-工单说明-字符串
            $content = $info['list'][$key]['content'] ;
            $info['list'][$key]['content'] = msubstr($content,0,30,"utf-8",true);
        }

        $this->assign('list', $info['list']);
        $this->assign('page_show', $info['page_show']);
        $this->assign('title', $info['title']);
        $this->assign('type_id', $info['type_id']);
        $this->assign('date_start', $info['date_start']);
        $this->assign('date_end', $info['date_end']);
        $this->display();
    }

    /**
     * 新增，修改案例信息
     */
    public function editPage() {
        $model = new CaseModel();
        $id = OnemlaRequest::getVar("id");
        $info = $model->where(array("id" => $id))->find();
        $this->assign('info', $info);
        $this->display("edit");
    }

    /**
     * 新增，修改基本信息
     */
    public function edit() {
        $model = new CaseModel();
        $msg = $model->edit();
        if ($msg['code'] == "add_success") {
            wingo($msg['msg'],U("CaAndSo/Index/index"));
        }
        if ($msg['code'] == "add_error") {
           win_savedata_back($msg['msg']);
        }
        if ($msg['code'] == "edit_success") {
            wingo($msg['msg'],U("CaAndSo/Index/index"));
        }
        if ($msg['code'] == "edit_error") {
            win_savedata_back($msg['msg']);
        }
    }



    /**
     * 删除
     */
    public function delete() {
        $model = new CaseModel();
        $id = OnemlaRequest::getVar("id");

        //删除案例同时删除图片
        $info = $model->where(array("id" => $id))->field('image')->find();
        unlink($_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Res/Uploads/case/'.$info['image']);

        $result = $model->where(array("id" => $id))->delete();
        if ($result) {
            $this->httpReturn(1, $id, "删除成功");
        } else {
            $this->httpReturn(2, $id, "删除失败");
        }
    }

}
