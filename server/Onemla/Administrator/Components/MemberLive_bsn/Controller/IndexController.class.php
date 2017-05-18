<?php

namespace Components\MemberLive_bsn\Controller;

use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Onemla\SessionViewController;

class IndexController extends SessionViewController {

    /**
     * 直播商信息
     */
    public function index() {
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_MEMBER_LIVE_BSN);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_MEMBER_LIVE_BSN_ONE);

        $model = M('live_bsn');
        $userinfoModel = M('user_info');
        $userinfo = $userinfoModel->where(array('user_id'=>OnemlaHelper::getUserId()))->find();
        if($userinfo['type'] == 1 ){
            $public_logo = 'personal/'.$userinfo['public_logo'];
            $qr_code = 'personal/'.$userinfo['qr_code'];
        }else{
            $public_logo = 'company/'.$userinfo['public_logo'];
            $qr_code = 'company/'.$userinfo['qr_code'];
        }

        $info = $model->where(array('user_id'=>OnemlaHelper::getUserId()))->find();
        $this->assign('public_logo',$public_logo);
        $this->assign('qr_code',$qr_code);
        $this->assign('live_name',$userinfo['live_name']);
        $this->assign('info', $info);
        $this->display();
    }

    /**
     * 修改简介
     */
    public function edit_introduce() {
        $introduce = OnemlaRequest::getVar('introduce');
        $live_name = OnemlaRequest::getVar('live_name');
        $model = M('live_bsn');
        $rInfo = $model->where(array('user_id'=>OnemlaHelper::getUserId()))->find();
        $userInfoModel = M('user_info');
        $UiRInfo =$userInfoModel->where(array('user_id'=>OnemlaHelper::getUserId()))->find();
        $public_logo_info = uploadOne($_FILES['public_logo'],'Res/Uploads/certification/'.($UiRInfo['type']==1?'personal/':'company/'));
        if($public_logo_info['flag']=="error" && $UiRInfo['public_logo']==''){
            win_savedata_back($public_logo_info['msg']=="");exit;
        };
        $qr_code_info = uploadOne($_FILES['qr_code'],'Res/Uploads/certification/'.($UiRInfo['type']==1?'personal/':'company/'));

        if($qr_code_info['flag']=="error" && $UiRInfo['qr_code']==''){
            win_savedata_back($qr_code_info['msg']);exit;
        };

        $itd_image_info = uploadOne($_FILES['itd_image'],'Res/Uploads/live_bsn/');
        if($itd_image_info['flag']=="error"){
            if($itd_image_info['msg']!='没有文件被上传！'){
                win_savedata_back($itd_image_info['msg']);exit;
            }
        };
        $public_logoUrl = empty($public_logo_info['msg']['file'])  ? '':delAdminFile($UiRInfo['public_logo'],'Res/Uploads/certification/'.($UiRInfo['type']==1?'personal/':'company/'));
        $qr_codeUrl = empty($qr_code_info['msg']['file'])? '':delAdminFile($UiRInfo['qr_code'],'Res/Uploads/certification/'.($UiRInfo['type']==1?'personal/':'company/'));
        $itd_imageUrl = empty($itd_image_info['msg']['file'])  ? '' : delAdminFile($rInfo['itd_image'],'Res/Uploads/live_bsn/');
        $res = $model
            ->where(array('user_id'=>OnemlaHelper::getUserId()))
            ->save(array(
                'introduce'=>$introduce,
                'itd_image'=>empty($itd_image_info['msg']['file']) ?$rInfo['itd_image']:$itd_image_info['msg']['file'],
            ));

        $res2 = $userInfoModel->where(array('user_id'=>OnemlaHelper::getUserId()))
            ->save(array(
                'live_name'=>$live_name,
                'public_logo'=> empty($public_logo_info['msg']['file']) ?$UiRInfo['public_logo']:$public_logo_info['msg']['file'],
                'qr_code'=> empty($qr_code_info['msg']['file']) ?$UiRInfo['qr_code']:$qr_code_info['msg']['file'],
            ));
        if($res or $res2){
            unlink($public_logoUrl);
            unlink($qr_codeUrl);
            unlink($itd_imageUrl);
            wingo('修改成功','index.php?m=MemberLive_bsn&c=Index&a=index');
        }
        if(!$res && !$res2){
            win_savedata_back('提交失败,未做任何修改');
        }
    }

}
