<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/21 0021
 * Time: 上午 9:49
 */

namespace Components\Home\Controller;

use Onemla\SessionViewController;
use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Components\User\Model\UserInfoModel;


class UserauditController extends SessionViewController{

    public function __construct()
    {//防止会员通过更改url跳转到管理员界面
        parent::__construct();
        if(OnemlaHelper::getUser()->user_type==3){
            $this->redirect('Member/Index/index');
            exit;
        }
    }

    public function personal(){
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_ADMIN);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_ADMIN_TWO);

        $model = new UserInfoModel();
        $info = $model->getpersonallist();

        foreach($info['list'] as $key => $val){
            $province_id = $info['list'][$key]['province_id'];
            $city_id = $info['list'][$key]['city_id'];

            $provice=getProvinceName($province_id);
            $city=getCityName($city_id);

            $info['list'][$key]['province_id'] = $provice;
            $info['list'][$key]['city_id'] = $city;
        }

        $this->assign('list',$info['list']);
        $this->assign('count',$info['count']);
        $this->assign('page_show',$info['page_show']);
        $this->assign('user_name',$info['user_name']);
        $this->assign('status',$info['status']);
        $this->display('personal');
    }


    public function company(){
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_ADMIN);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_ADMIN_TWO);

        $model = new UserInfoModel();
        $info = $model->getcompanylist();

        foreach($info['list'] as $key => $val){
            $province_id = $info['list'][$key]['province_id'];
            $city_id = $info['list'][$key]['city_id'];

            $province=getProvinceName($province_id);
            $city=getCityName($city_id);

            $info['list'][$key]['province_id'] = $province;
            $info['list'][$key]['city_id'] = $city;
        }

        $this->assign('list',$info['list']);
        $this->assign('count',$info['count']);
        $this->assign('page_show',$info['page_show']);
        $this->assign('user_name',$info['user_name']);
        $this->assign('status',$info['status']);
        $this->display('company');
    }

    public function pic_detail(){
        $username = OnemlaRequest::getVar('username');
        $image = OnemlaRequest::getVar('image');
        $type = OnemlaRequest::getVar('type');
        $this->assign('username',$username);
        $this->assign('image',$image);
        $this->assign('type',$type);
        $this->display('pic_detail');
    }

    /**
     * 审核
     */
    public function audit() {
        $id = OnemlaRequest::getVar("id");
        $status = OnemlaRequest::getVar("audit_res"); //2通过 3不通过
        $model = new UserInfoModel();
        $user_id = $model->where(array('id'=>$id))->getField('user_id');
        $live_bsn_model = M('live_bsn');//直播商数据库
        $bsn_res = $live_bsn_model->where(array('user_id'=>$user_id))->find();//查找直播商是否已开通

        if($status == 3 ){ //如果审核不通过,把认证类型清空,则可以重新认证
            $audit_sub_times_model = M('audit_submit_times');

            $audit_sub_times_model->where(array("user_id" => $user_id))->save(array('submit_times'=>0));
            $audit_reason = OnemlaRequest::getVar('reason');
            $data = array(
                'status' => $status,
//                'type' => NULL,
//                'submit_time' => 0,
                'audit_reason'=>$audit_reason,
            );

            if($bsn_res){//删除直播商以及其直播间,频道,话题
                unlink(delAdminFile(M('live_channel')->where(array('bsn_id'=>$bsn_res['id']))->getField('image'), $pathUrl = "Res/Uploads/live_channel/"));
                unlink(delAdminFile(M('live_channel')->where(array('bsn_id'=>$bsn_res['id']))->getField('itd_image'), $pathUrl = "Res/Uploads/live_channel/"));
                unlink(delAdminFile(M('live_room')->where(array('r_bsn_id'=>$bsn_res['id']))->getField('image'), $pathUrl = "Res/Uploads/live_room/"));
                unlink(delAdminFile($live_bsn_model->where(array('user_id'=>$user_id))->getField('itd_image'), $pathUrl = "Res/Uploads/live_bsn/"));

                $bsn_change_res = $live_bsn_model->where(array('user_id'=>$user_id))->delete();
                M('live_channel')->where(array('bsn_id'=>$bsn_res['id']))->delete();
                M('live_tags')->where(array('bsn_id'=>$bsn_res['id']))->delete();
                M('live_room')->where(array('r_bsn_id'=>$bsn_res['id']))->delete();
            }else{
                $bsn_change_res = true;
            }
        }else{
            $data = array(
                'status' => $status,
            );

            $live_bsn_data = array(
                'user_id' => $user_id,
                'create_time' => date('Y-m-d H:i:s'),
                );
            if($bsn_res){//
                $bsn_change_res = $live_bsn_model->where(array('user_id'=>$user_id))->save($live_bsn_data);
            }else{
                $bsn_change_res = $live_bsn_model->where(array('user_id'=>$user_id))->add($live_bsn_data);
            }
        }

        $msg = $model->where(array("id" => $id))->save($data);
        if ($msg == "1" && $bsn_change_res) {
            $this->httpReturn(1, $msg, 'success');
        } else {
            $this->httpReturn(2, $msg, 'error');
        }
    }

}