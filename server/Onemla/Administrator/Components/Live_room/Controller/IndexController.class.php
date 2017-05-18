<?php

namespace Components\Live_room\Controller;

use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Components\User\Model\UserModel;
use Components\User\Model\UserInfoModel;
use Onemla\SessionViewController;
use Components\MemberLive_bsn\Model\Live_roomModel;
use Org\Page\Page;

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
     * 直播间列表
     */
    public function index() {
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_LIVE_ROOM);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_LIVE_ROOM_ONE);

        $model = new Live_roomModel();
        $info = $model->getList();

        //获取城市列表
        $pCitySelectJs = getPCitySelectJs('province', 'city', $pid = $info['province_id'], $cid = $info['city_id']);
        $pCountryCfg = getCfgProvinceByCountry();
        $region = '';
        foreach ($pCountryCfg as $value) {
            $region = $region . '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
        }
        $this->assign("region", $region);
        $this->assign('pCitySelectJs', $pCitySelectJs);

        $this->assign('list',($info['list']));
        $this->assign('isshow',$info['isshow']);
        $this->assign('title',$info['title']);
        $this->assign('live_name',$info['live_name']);
        $this->assign('character_id',$info['character_id']);
        $this->assign('audit_status',$info['audit_status']);
        $this->assign('page_show',$info['page_show']);

        $characterModel = M('live_character');
        $character = $characterModel->select();
        $this->assign('character',$character);

        $this->display();
    }

    /*
     * 首页显示
     * **/
    public function isshow(){
        $id = OnemlaRequest::getVar('id');
        $isshow = OnemlaRequest::getVar('isshow');
        $model = M('live_room');
        $res = $model->where(array('room_id'=>$id))->save(array('isshow'=>$isshow));
        if($res){
            $this->httpReturn(1);
        }else{
            $this->httpReturn(2);
        }
    }

    /*
     * 冻结\解冻
     * */
    public function lock(){
        $id = OnemlaRequest::getVar('id');
        $lock = OnemlaRequest::getVar('lock');
        $model = M('live_room');
        $find = $model->alias('a')
            ->join('tb_live_bsn as b on b.id=a.r_bsn_id')
            ->join('tb_user as u on u.id=b.user_id')
            ->field('u.phone,a.title')
            ->where(array('a.room_id'=>$id))
            ->find();

        if($lock==1){
            $res = $model->where(array('room_id'=>$id))->save(array('lock'=>$lock));
            if($res) $this->httpReturn(1);
            else $this->httpReturn(2,'','解冻失败');
        }else{
            $date1 = date('m');
            $date2 = date('d');
            $date3 = date('H');
            $date4 = date('i');
            $arr = array($date1,$date2,$date3,$date4,'【'.$find['title'].'】');/*发送短信内容*/
            $res1 = sendTemplateSMS($find['phone'],$arr,171305);//发送短信
            if($res1['res']==1){
                $res = $model->where(array('room_id'=>$id))->save(array('lock'=>$lock));
                if($res) $this->httpReturn(1);
                else $this->httpReturn(2,'','冻结失败');
            }else{
                $this->httpReturn(2,'',$res1[1]);
            }
        }
    }

    /*
     * 审核
     * */
    public function audit(){
        $id = OnemlaRequest::getVar("id");
        $status = OnemlaRequest::getVar("audit_res"); //1.正常  2.锁定
        $alink = OnemlaRequest::getVar('alink');
        $reason = OnemlaRequest::getVar('reason');
        $live_id = OnemlaRequest::getVar('live_id');

        $model = M('live_room');
        if($status==3){
            $data = array(
                'audit_status' => $status,
                'reason' => $reason,
            );
        }
        if($status==2){
            $data = array(
                'audit_status' => $status,
                'alink' => $alink,
                'live_id' => $live_id,
            );
        }
        $msg = $model->where(array("room_id" => $id))->save($data);
        if ($msg == "1") {
            $this->httpReturn(1);
        } else {
            $this->httpReturn(2);
        }
    }

    /*
     * 直播商管理
     * */
    public function bsn(){
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_LIVE_ROOM);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_LIVE_ROOM_ZERO);

        $live_name = OnemlaRequest::getVar('live_name');
        $bsnModel = M('live_bsn');
        $count = $bsnModel->alias('a')
            ->join('tb_user_info as u on u.user_id = a.user_id')
            ->join('tb_user as us on us.id=a.user_id')
            ->field('a.*,u.live_name,u.public_logo,u.qr_code,u.type,us.user_name')
            ->where(array('live_name'=>array('like','%'.$live_name.'%')))
            ->count();
        $Page = new Page($count, $listRows = 10, array('live_name'=>$live_name));
        $limit = '' . $Page->firstRow . ',' . $Page->listRows;
        $this->bsnList = $bsnModel->alias('a')
            ->join('tb_user_info as u on u.user_id = a.user_id')
            ->join('tb_user as us on us.id=a.user_id')
            ->field('a.*,u.live_name,u.public_logo,u.qr_code,u.type,us.user_name,us.state,us.phone')
            ->where(array('live_name'=>array('like','%'.$live_name.'%')))
            ->order('create_time desc')
            ->limit($limit)
            ->select();
        $this->assign('page_show',$Page->AdminShow());
        $this->display('bsn');
    }

    /*
     * 直播商详情
     * */
    public function bsn_detail(){
        $bsn_id = OnemlaRequest::getVar('bsn_id');
        $bsnModel = M('live_bsn');
        $this->bsnInfo = $bsnModel->alias('a')
            ->join('tb_user_info as u on a.user_id = u.user_id')
            ->where(array('a.id'=>$bsn_id))
            ->field('a.*,u.live_name,u.public_logo,u.qr_code,u.type')
            ->find();
        $this->display('bsn_detail');
    }
}
