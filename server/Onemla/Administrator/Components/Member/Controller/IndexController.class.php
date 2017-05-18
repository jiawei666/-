<?php

namespace Components\Member\Controller;

use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Components\User\Model\UserModel;
use Components\User\Model\UserInfoModel;
use Components\User\Model\AuditSubmitTimeModel;
use Onemla\SessionViewController;
use Services\User\UserService;
use Components\Home\Model\Live_chatModel;

class IndexController extends SessionViewController {

    /**
     * 用户列表
     */
    public function index() {
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_MEMBER);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_MEMBER_ONE);

        $model = new UserModel();
        $user_id = OnemlaHelper::getUserId();
        $info = $model->where(array("id" => $user_id))->find();
        $this->assign('info', $info);
        $this->display();
    }

    /**
     * 编辑会员--发送手机验证码
     */
    public function sendmessage(){
        $phone_num = OnemlaRequest::getVar('phone');
        $model = new UserModel();
        $user =$model->where(array('phone'=>$phone_num))->getField('user_name');
        if($user){
            $arr = array('res'=>0,'msg'=>'该手机已注册');
            $arr = urldecode(json_encode($arr));
            echo $arr;
            exit;
        }
        for($i=0;$i<4;$i++){//制作验证码
            $verify.=rand(0,9);
        }
        $arr = array($verify);
        $res = sendTemplateSMS($phone_num,$arr,162699);//发送短信验证码
        if($res['res']==1){//发送成功则返回,并储存session信息
            session($phone_num.'edit_info_vrf',$verify);//以手机号命名session
            $arr = array('res'=>1);
            $arr = urldecode(json_encode($arr));
            echo $arr;
        }else{
            $arr = array('res'=>0,'msg'=>$res[1]);
            $arr = urldecode(json_encode($arr));
            echo $arr;
        }
    }

    /**
     * 编辑会员信息
     */
    public function edit() {
        $model = new UserModel();
        $msg = $model->editMember();
        wingo($msg['msg'], U("Member/Index/index"));


    }

    /**
     * 密码设置
     */
    public function setPassword() {
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_MEMBER);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_MEMBER_TWO);

        $this->display("set_password");
    }

    /**
     * 修改密码
     */
    public function editPassword() {
        $model = new UserModel();
        $user_service = new UserService();
        $info = OnemlaRequest::getVar("");
        $uInfo = $model->where(array("id" => OnemlaHelper::getUserId()))->find();
        if ($info['password'] != $info['ag_password']) {
            win_savedata_back('2次输入密码不一致，请重新输入');
            return;
        }
        $verifyPass = $user_service->verifyPassword($info['old_password'], $uInfo['password']);
        if (!$verifyPass) {
            win_savedata_back('输入密码与原密码不一致');
            return;
        }

        $result = $user_service->changeUserPwd($uInfo['user_name'], $info['password'], 1);
        if ($result) {
           wingo("修改密码成功！", U("User/Index/logout"));
        } else {
            win_savedata_back("修改密码失败！");
        }
    }

    /**
     * 认证信息--个人
     */
    public function certificationPersonal() {
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_MEMBER);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_MEMBER_THEWW);
        $model = new UserInfoModel();
        $info = $model->where(array("user_id" => OnemlaHelper::getUserId(),'type'=>1))->find();
        $status = $model->where(array("user_id" => OnemlaHelper::getUserId()))->getField('status');
        //获取城市列表
        $pCitySelectJs = getPCitySelectJs('province', 'city', $pid = $info['province_id'], $cid = $info['city_id']);
        $pCountryCfg = getCfgProvinceByCountry();
        $region = '';

        foreach ($pCountryCfg as $value) {
            $region = $region . '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
        }
        $this->assign("info",$info);

        $this->assign('status',$status);
        $this->assign("region", $region);
        $this->assign('pCitySelectJs', $pCitySelectJs);

        $this->display("certification_personal");
    }

    /**
     * 认证信息--公司
     */
    public function certificationCompany() {
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_MEMBER);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_MEMBER_THEWW);

        $model = new UserInfoModel();
        $info = $model->where(array("user_id" => OnemlaHelper::getUserId(),'type'=>2))->find();
        $status = $model->where(array("user_id" => OnemlaHelper::getUserId()))->getField('status');
        //获取城市列表
        $pCitySelectJs = getPCitySelectJs('province', 'city', $pid = $info['province_id'], $cid = $info['city_id']);
        $pCountryCfg = getCfgProvinceByCountry();
        $region = '';
        foreach ($pCountryCfg as $value) {
            $region = $region . '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
        }

        $this->assign("info",$info);
        $this->assign('status',$status);
        $this->assign("region", $region);
        $this->assign('pCitySelectJs', $pCitySelectJs);

        $this->display("certification_company");
    }

    /**
     * 修改--个人信息认证
     */
    public function editCertification_personal() {
        $model = new UserInfoModel();
        $sub_time_model = M('audit_submit_times');

        $status = $model->where(array("user_id" => OnemlaHelper::getUserId()))->getField('status');
        $submit_times = $sub_time_model->where(array("user_id" => OnemlaHelper::getUserId()))->getField('submit_times');
        if ($status == 1 || $status == 3) {//未审核或者审核不通过才能提交
            $now = date("Y-m-d H:i:s");
            $re_time = $sub_time_model->where(array('user_id'=>OnemlaHelper::getUserId()))->getField('reset_time');
            $re_time = strtotime($re_time);

            if((strtotime($now)-$re_time)>86400){//24小时后重置提交次数
                $data['submit_times'] = 0;
                $data['reset_time'] = $now;
                $sub_time_model->where(array("user_id" => OnemlaHelper::getUserId()))->save($data);
                $submit_times =0;
            }

            if ($submit_times < 3 or (strtotime($now)-$re_time)>86400){//待审核过程中每天最多重复提交三次,审核不通过则刷新次数
                $info = OnemlaRequest::getVar("");
                $rInfo = $model->where(array("user_id" => OnemlaHelper::getUserId()))->find();//图片处理
                $exts_limit=array(//上传类型限制
                    'exts' =>  array('jpg', 'png', 'jpeg'),
                    'maxSize' => 2097152,
                );

                $file = upload_img($exts_limit, $rootPath = "Res/Uploads/certification/personal");
                $card_image = $file['info']['card_image']['savename'];
                $public_logo = $file['info']['public_logo']['savename'];
                $qr_code = $file['info']['qr_code']['savename'];

                $rInfo['type']==1 ? $delimgurl = 'personal':$delimgurl = 'company';
                if($card_image ==''){//如果上传为空同时数据库有值,则可以上传修改
                   if($rInfo['card_image']==''){
                       wingo('上传文件为空');
                       exit;
                   }else{
                       $card_image = $rInfo['card_image'];
                   }
                }else{
                    if($file['error'] != '' && $file['error'] != '没有文件被上传！'){
                        wingo($file['error']);
                        exit;
                    }
                    $card_image_url = delAdminFile($rInfo['card_image'],"Res/Uploads/certification/".$delimgurl."/");
                }

                if( $qr_code == ''){//如果上传为空同时数据库有值,则可以上传修改
                    if($rInfo['qr_code'] == ''){
                        wingo('上传文件为空');
                        exit;
                    }else{
                        $qr_code = $rInfo['qr_code'];
                    }
                }else{
                    if($file['error'] != '' && $file['error'] != '没有文件被上传！'){
                        wingo($file['error']);
                        exit;
                    }
                    $qr_code_url = delAdminFile($rInfo['qr_code'],"Res/Uploads/certification/".$delimgurl."/");
                }
                if($public_logo == ''){//如果上传为空同时数据库有值,则可以上传修改
                    if($rInfo['public_logo'] ==''){
                        wingo('上传文件为空');
                        exit;
                    }else{
                        $public_logo = $rInfo['public_logo'];
                    }
                }else{
                    if($file['error'] != '' && $file['error'] != '没有文件被上传！'){
                        wingo($file['error']);
                        exit;
                    }
                    $public_logo_url = delAdminFile($rInfo['public_logo'],"Res/Uploads/certification/".$delimgurl."/");
                }

                $time =  date("Y-m-d H:i:s");
                $rdata = array(//原信息
                    'type' => 1,
                    'province_id' => $rInfo['province_id'],
                    'city_id' => $rInfo['city_id'],
                    'address' => $rInfo['address'],
                    'real_name' => $rInfo['real_name'], //真实姓名
                    'document_type'=>$rInfo['document_type'],//证件类型
                    'card_id' => $rInfo['card_id'], //证件号码
                    'card_image' => $rInfo['card_image'], //证件照片
                    'live_name' => $rInfo['live_name'],//公众号名称
                    'public_logo' => $rInfo['public_logo'],//公众号logo
                    'qr_code' => $rInfo['qr_code'],//二维码
                    'update_time' =>$time,
                    'status' => 1,//审核状态默认待审核
                );
                $data = array(//新信息
                    'type' => 1,
                    'province_id' => $info['province_id'],
                    'city_id' => $info['city_id'],
                    'address' => $info['address'],
                    'real_name' => $info['real_name'], //真实姓名
                    'document_type' => $info['document_type'], //证件类型
                    'card_id' => $info['card_id'], //证件号码
                    'card_image' => $card_image, //证件照片
                    'live_name' => $info['live_name'],//公众号名称
                    'public_logo' => $public_logo,//公众号logo
                    'qr_code' => $qr_code,//二维码
                    'update_time' => $time,
                    'status' => 1,//审核状态默认待审核
                );

                if($rdata == $data){//判断是否修改过信息
                    wingo('提交失败,未做任何修改', U("Member/Index/certificationPersonal"));
                    exit;
                }

                $result = $model->where(array("user_id" => OnemlaHelper::getUserId()))->save($data);

                $submit_times_data['submit_times'] = ++$submit_times;
                $submit_times_result = $sub_time_model->where(array("user_id" => OnemlaHelper::getUserId()))->save($submit_times_data);

                if ($result && $submit_times_result) {
                    unlink($card_image_url);
                    unlink($qr_code_url);
                    unlink($public_logo_url);
                    wingo("提交信息成功！您已提交".$submit_times."次,本日剩余".(3-$submit_times)."次机会,如果审核不通过则可继续提交", U("Member/Index/certificationPersonal"));
                } else {
                    wingo("提交信息失败！", U("Member/Index/certificationPersonal"));
                }
            } else {
                $this->error('每天最多提交三次审核');
            }
        }else{
            wingo("你已经通过审核", U("Member/Index/certificationPersonal"));
        }
    }

    /**
     * 修改--公司信息认证
     */
    public function editCertification_company() {
        $model = new UserInfoModel();
        $sub_time_model = M('audit_submit_times');

        $status = $model->where(array("user_id" => OnemlaHelper::getUserId()))->getField('status');
        $submit_times = $sub_time_model->where(array("user_id" => OnemlaHelper::getUserId()))->getField('submit_times');
        if ($status == 1 || $status == 3) {//未审核或者审核不通过才能提交
            $now = date("Y-m-d H:i:s");
            $re_time = $sub_time_model->where(array('user_id'=>OnemlaHelper::getUserId()))->getField('reset_time');
            $re_time = strtotime($re_time);
            if((strtotime($now)-$re_time)>86400){
                $data['submit_times'] = 0;
                $data['reset_time'] = $now;
                $sub_time_model->where(array("user_id" => OnemlaHelper::getUserId()))->save($data);
                $submit_times =0;
            }

            if( $submit_times < 3 or (strtotime($now)-$re_time)>86400 ) {//待审核过程中每天最多重复提交三次,审核不通过则刷新次数
                $info = OnemlaRequest::getVar("");

                $rInfo = $model->where(array("user_id" => OnemlaHelper::getUserId()))->find();//图片处理
                $exts_limit=array(//上传类型限制
                    'exts' =>  array('jpg', 'png', 'jpeg'),
                    'maxSize' => 2097152,
                );
                $file = upload_img($exts_limit, $rootPath = "Res/Uploads/certification/company");
                $card_image = $file['info']['card_image']['savename'];
                $public_logo = $file['info']['public_logo']['savename'];
                $qr_code = $file['info']['qr_code']['savename'];

                $rInfo['type']==1 ? $delimgurl = 'personal':$delimgurl = 'company';
                if($card_image ==''){//如果上传为空同时数据库有值,则可以上传修改
                    if($rInfo['card_image']==''){
                        wingo('上传文件为空');
                        exit;
                    }else{
                        $card_image = $rInfo['card_image'];
                    }
                }else{
                    if($file['error'] != '' && $file['error'] != '没有文件被上传！'){
                        wingo($file['error']);
                        exit;
                    }
                    $card_image_url = delAdminFile($rInfo['card_image'],"Res/Uploads/certification/".$delimgurl."/");
                }

                if( $qr_code == ''){//如果上传为空同时数据库有值,则可以上传修改
                    if($rInfo['qr_code'] == ''){
                        wingo('上传文件为空');
                        exit;
                    }else{
                        $qr_code = $rInfo['qr_code'];
                    }

                }else{
                    if($file['error'] != '' && $file['error'] != '没有文件被上传！'){
                        wingo($file['error']);
                        exit;
                    }
                    $qr_code_url = delAdminFile($rInfo['qr_code'],"Res/Uploads/certification/".$delimgurl."/");
                }
                if($public_logo == ''){//如果上传为空同时数据库有值,则可以上传修改
                    if($rInfo['public_logo'] ==''){
                        wingo('上传文件为空');
                        exit;
                    }else{
                        $public_logo = $rInfo['public_logo'];
                    }
                }else{
                    if($file['error'] != '' && $file['error'] != '没有文件被上传！'){
                        wingo($file['error']);
                        exit;
                    }
                    $public_logo_url = delAdminFile($rInfo['public_logo'],"Res/Uploads/certification/".$delimgurl."/");
                }


                $time =  date("Y-m-d H:i:s");
                $rdata = array(//原信息
                    'type' => 2,
                    'province_id' => $rInfo['province_id'],
                    'city_id' => $rInfo['city_id'],
                    'address' => $rInfo['address'],
                    'real_name' => $rInfo['real_name'], //公司名称
                    'website' => $rInfo['website'], //公司网站
                    'card_id' => $rInfo['card_id'], //营业执照
                    'card_image' => $rInfo['card_image'], //证件照片
                    'live_name' => $rInfo['live_name'],//公众号名称
                    'public_logo' => $rInfo['public_logo'],//公众号logo
                    'qr_code' => $rInfo['qr_code'],//二维码
                    'update_time' =>$time,
                    'status' => 1,//审核状态默认待审核
                );
                $data = array(//新信息
                    'type' => 2 ,
                    'province_id' => $info['province_id'],
                    'city_id' => $info['city_id'],
                    'address' => $info['address'],
                    'real_name' => $info['real_name'], //公司名称
                    'website' => $info['website'], //公司网站
                    'card_id' => $info['card_id'], //营业执照
                    'card_image' => $card_image, //证件照片
                    'live_name' => $info['live_name'],//公众号名称
                    'public_logo' => $public_logo,//公众号logo
                    'qr_code' => $qr_code,//二维码
                    'update_time' => $time,
                    'status' => 1,//审核状态默认待审核
                );
                if($rdata == $data){//判断是否修改过信息
                    wingo('提交失败,未做任何修改', U("Member/Index/certificationCompany"));
                    exit;
                }

                $result = $model->where(array("user_id" => OnemlaHelper::getUserId()))->save($data);

                $submit_times_data['submit_times'] = ++$submit_times;
                $submit_times_result = $sub_time_model->where(array("user_id" => OnemlaHelper::getUserId()))->save($submit_times_data);

                if ($result && $submit_times_result) {

                    unlink($card_image_url);
                    unlink($qr_code_url);

                    unlink($public_logo_url);
                    wingo("提交信息成功！您已提交".$submit_times."次,本日剩余".(3-$submit_times)."次机会,如果审核不通过则可继续提交", U("Member/Index/certificationCompany"));
                } else {
                    wingo("提交信息失败！", U("Member/Index/certificationCompany"));
                }
            } else {
              $this->error('每天最多提交三次审核');
            }
        }else{
            wingo("你已经通过审核", U("Member/Index/certificationCompany")) ;
        }
    }

//    /*
//     * 聊天界面
//     * */
//    public function chat(){
//        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_MEMBER);
//        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_MEMBER_ZERO);
//
//        $model = new Live_chatModel();
//        $this->chat_list = $model->get_member_list();
//
//        $this->display('chat');
//    }
//
//    /**
//     * 聊天信息提交
//     */
//    public function chat_add(){
//        $model = new Live_chatModel();
//        $resutl = $model->add_member_list(1);
//        if ($resutl) {
//            winback('提交成功！');
//        }else{
//            win_savedata_back('提交失败，请重新提交！');
//        }
//    }

}
