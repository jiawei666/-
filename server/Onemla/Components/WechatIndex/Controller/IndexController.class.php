<?php

namespace Components\WechatIndex\Controller;

use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Components\User\Model\UserModel;
use Components\User\Model\UserInfoModel;
use Org\Page\Page;

class IndexController extends ViewController {


    /*
     *微信进来储存session
     ***/
    protected function _initialize() {
        $arr = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.C('WCAPPID').'&secret='.C('WCAPPSECRET').'&code=' . $_GET['code'] . '&grant_type=authorization_code');
        $r = json_decode($arr, 1);
        $u = D('live_wc_member')->where('open_id=\'' . $r['openid'] . '\'')->find();
        $arr2 = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".C('WCAPPID')."&secret=".C('WCAPPSECRET')."");
        $r2 = json_decode($arr2, 1);
        $user_wc = file_get_contents('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$r2['access_token'].'&openid='.$r['openid'].'&lang=zh_CN');
        $r3 = json_decode($user_wc, 1);
        if(!empty($r['openid'])){
            if (!$u) {
                $data['open_id'] = $r['openid'];
                $data['nickname'] = $r3['nickname'];
                $data['last_login_time'] = date('Y-m-d H:i:s');
                $id = D('live_wc_member')->add($data);
            } else {
                $data['nickname'] = $r3['nickname'];
                $data['last_login_time'] = date('Y-m-d H:i:s');
                $id = D('live_wc_member') -> where(array('id'=>$u['id']))->save($data);
            }
            if($id) session('open_id',$r['openid']);
        }
    }

//    public function test(){
//    $a = curlLiveInfo(507);
//    dump($a);
//    }

    public function test(){
        $this->display('test');
    }

    public function test2(){
        $this->display('test2');
    }

    /**
     * 首页
     */
    public function index() {//array('isshow'=>1,'lock'=>1,'audit_status'=>2)
        session('confirm',null);
        $bannerModel = M('live_banner');
        $roomModel = M('live_room');
        $userInfoModel = M('user_info');
        $now = date('Y-m-d H:i:s');
        $this->banner = $bannerModel->where("`show`=1 and `start_time`<'".$now."'and `end_time`>'".$now."'")->order('first asc')->select();
        $this->userInfo = $userInfoModel->alias('a')
            ->join('tb_live_bsn as b on b.user_id=a.user_id')
            ->join('tb_user as u on u.id=b.user_id')
            ->field('a.type,a.live_name,a.public_logo,a.qr_code,b.id as bsn_id,b.follow_count,u.state')
            ->where('u.state = 1')
            ->order('follow_count desc')
            ->select();
        S('room',null);
        if(!S('room')){
            $room = $roomModel->alias('a')
                ->join('tb_live_bsn as b on b.id=a.r_bsn_id')
                ->join('tb_user as u on u.id = b.user_id')
                ->field('u.state,a.*')
                ->where("`isshow` = 1 and `lock` = 1 and `audit_status` = 2 and u.state = 1")
                ->select();
            foreach($room as $key=>$value){
                $liveInfo = curlLiveInfo($value['live_id']);
                if($liveInfo){
                    $room[$key]['status'] = $liveInfo->playStatus;
                    $room[$key]['view_count'] = $liveInfo->online;
                }else{
                    $room[$key]['status'] = '3';
                    $room[$key]['view_count'] = '0';
                }
            }
            S('room',$room,120);
        }
        $this->assign('room',sortRoomByCount(S('room'),0));
        $this->display();
    }

    /*
     * 加载更多
     * **/
    public function reloadMore(){
        $info = OnemlaRequest::getVar('');

        if($info['tags']!=''){
            $relationModel = M('live_rt_relation');
            $roomIdList = $relationModel->where(array('livetags_id'=>$info['tags']))->field('liveroom_id')->select();
            foreach($roomIdList as $value){
                $roomId .= $value['liveroom_id'].',';
            }

            if($roomIdList){
                $roomId = substr($roomId,0,strlen($roomId)-1);
                $tagsWhere=" and `room_id` IN (".$roomId.")";
            }else{
                $tagsWhere=" and `room_id` =''";
            }
        }
        $model = M('live_room');
        $total = $model->alias('a')
            ->join('tb_live_bsn as b on b.id=a.r_bsn_id')
            ->join('tb_user as u on u.id = b.user_id')
            ->field('u.state,a.*')
            ->where($info['where'].' and u.state = 1'.$tagsWhere)->count();
        $dataArr = $model->alias('a')
            ->join('tb_live_bsn as b on b.id=a.r_bsn_id')
            ->join('tb_user as u on u.id = b.user_id')
            ->field('u.state,a.*')
            ->where($info['where'].' and u.state = 1'.$tagsWhere)->select();
        foreach($dataArr as $key=>$value){
            $liveInfo = curlLiveInfo($value['live_id']);
            if($liveInfo){
                $dataArr[$key]['status'] = $liveInfo->playStatus;
                $dataArr[$key]['view_count'] = $liveInfo->online;
            }else{
                $dataArr[$key]['status'] = '3';
                $dataArr[$key]['view_count'] = '0';
            }
        }
        $dataArr = sortRoomByCount($dataArr,$info['number']-1);
        $arr = array('total'=>$total,'dataArr'=>$dataArr);
        $this->ajaxReturn($arr,'json');
    }

    /**
     * 性质分类
     */
    public function character(){
        session('confirm',null);
        $characterId = OnemlaRequest::getVar('character_id');
        if($characterId!='') $character = " and `character_id`=".$characterId;
        $this->characterId=$characterId;
        $characterModel = M('live_character');
        $this->character = $characterModel->select();
        $roomModel = M('live_room');
        $room = $roomModel->alias('a')
            ->join('tb_live_bsn as b on b.id=a.r_bsn_id')
            ->join('tb_user as u on u.id = b.user_id')
            ->field('u.state,a.*')
            ->where("`lock`=1 and `audit_status`=2 and u.state = 1 ".$character)
            ->select();

        foreach($room as $key=>$value){
            $liveInfo = curlLiveInfo($value['live_id']);
            if($liveInfo){
                $room[$key]['status'] = $liveInfo->playStatus;
                $room[$key]['view_count'] = $liveInfo->online;
            }else{
                $room[$key]['status'] = '3';
                $room[$key]['view_count'] = '0';
            }
        }
        if(empty($room)) $this->isempty=1;
        $this->assign('room',sortRoomByCount($room,0));
        $this->display('character');
    }

//    /*
//     * 获取定位城市
//     * */
//    public function location(){
//        $model = M('live_room');
//        $title = OnemlaRequest::getVar('title');
//        $this->title = $title;
//        if($title != '')  $titleWhere = " and `title` LIKE '%".$title."%'";
//        $room = $model->alias('a')
//            ->join('tb_live_bsn as b on b.id=a.r_bsn_id')
//            ->join('tb_user as u on u.id = b.user_id')
//            ->field('u.state,a.*')
//            ->where("`lock`=1 and `audit_status`=2 and u.state=1".$titleWhere)
//            ->select();
//        foreach($room as $key=>$value){
//            $liveInfo = curlLiveInfo($value['live_id']);
//            if($liveInfo){
//                $room[$key]['status'] = $liveInfo->playStatus;
//                $room[$key]['view_count'] = $liveInfo->online;
//            }else{
//                $room[$key]['status'] = '3';
//                $room[$key]['view_count'] = '0';
//            }
//        }
//        if(empty($room)) $this->isempty=1;
//        $this->assign('room',sortRoomByCount($room,0));
//        $this->display('location');
//    }


    /**
     * 地区分类
     */
    public function region(){//地区页先显示所有地区的活动,用户可自行进行地区筛选

        $model = M('live_room');
        $regionModel = M('regions');
        $city = OnemlaRequest::getVar('city');
        if($city != ''){
            $city_id = $regionModel->where("`name` LIKE '%".$city."%' and `type`=2")->getField('id');
            $present_city_id = $city_id;
            $this->city_id = $city_id;
            if($city_id!='') $cityWhere = " and `city_id`=".$city_id;
            $confirmJudge = true;
        }else{
            if(session('?location')){
                $locationCity = session('location');
                $confirmJudge = true;
            }else{
                $locationCity = OnemlaRequest::getVar('locationCity');
                if($locationCity != '') $confirmJudge = true;
                else $confirmJudge = false;
                session('location',$locationCity);
            }
            $this->assign('location_city',$locationCity);
            $locationWhere = substr($locationCity,0,6);
            $this->assign('locationWhere',$locationWhere);
            $city_id = $regionModel->where("`name` LIKE '%".$locationWhere."%' and `type`=2")->getField('id');
            $this->city_id = $city_id;
            if($city_id!='') $cityWhere = " and `city_id`=".$city_id;
        }
        if(session('?confirm') && empty( $city)){
            $this->confirm = '选择城市';
            $cityWhere = '';
            $this->city_id = '';
        }
        $showAll = OnemlaRequest::getVar('confirm');
        if($showAll !=''){//浏览所有
            $this->confirm = '选择城市';
            $cityWhere = '';
            $this->city_id = '';
            session('confirm',true);
        }
        $this->present_city = getCityName($present_city_id);
        $title = OnemlaRequest::getVar('title');
        $this->title = $title;
        if($title != '')  $titleWhere = " and `title` LIKE '%".$title."%'";

        $room = $model->alias('a')
            ->join('tb_live_bsn as b on b.id=a.r_bsn_id')
            ->join('tb_user as u on u.id = b.user_id')
            ->field('u.state,a.*')
            ->where("`lock`=1 and `audit_status`=2 and u.state=1".$cityWhere.$titleWhere)
            ->select();
        foreach($room as $key=>$value){
            $liveInfo = curlLiveInfo($value['live_id']);
            if($liveInfo){
                $room[$key]['status'] = $liveInfo->playStatus;
                $room[$key]['view_count'] = $liveInfo->online;
            }else{
                $room[$key]['status'] = '3';
                $room[$key]['view_count'] = '0';
            }
        }

        if(empty($room)) $this->isempty=1;
        if($confirmJudge && empty($room) && empty($title)) $this->confirmJudge=1;
        if(session('?confirm') && empty($city) &empty($room)){
            $this->confirmJudge=0;
        }

        $this->assign('room',sortRoomByCount($room,0));
        $this->display('region');
    }

//    public function province(){
//        $location_city = IpLocation();//定位
//        $this->assign('location_city',$location_city);
//        $regionModel = M('regions');
//        $this->location_city_id = $regionModel->where(array('type'=>2,'name'=>array('like','%'.$location_city.'%')))->getField('id');
//        $this->provinceList = $regionModel->where(array('type'=>1))->field('id,name')->select();
//        $this->display('province');
//    }
//
//    public function city(){
//        $this->location_city = IpLocation();//定位
//        $pid = OnemlaRequest::getVar('pid');
//        $this->assign('province',getProvinceName($pid));
//        $search = OnemlaRequest::getVar('city_name');
//        $where['name']=array('like','%'.$search.'%');
//        $where['type']=2;
//        $search==''? $where['pid']=$pid:$where['pid']=array('like','%'.$pid.'%');
//        $regionModel = M('regions');
//        $cityList = $regionModel->where($where)->field('id,name')->select();
//        if ($search!='') $this->search_city = $search;
//        $this->assign('cityList',$cityList);
//        $this->display('city');
//    }

    /**
     * 直播商
     */
    public function live_bsn(){
        session('confirm',null);
        $userInfoModel = M('user_info');
        $live_name = OnemlaRequest::getVar('live_name');
        $this->assign('live_name',$live_name);
        $where['live_name'] = array('like','%'.$live_name.'%');
        $list = $userInfoModel->alias('a')
            ->join('tb_live_bsn as b on b.user_id=a.user_id')
            ->join('tb_user as u on b.user_id=u.id')
            ->field('a.type,a.live_name,a.public_logo,a.qr_code,b.id as bsn_id,b.follow_count,u.state')
            ->order('follow_count desc')
            ->where("`live_name` LIKE '%".$live_name."%' and u.state = 1")
            ->select();
        $followModel = M('live_bsn_follow');
        foreach($list as $key=>$value){
            $list[$key]['open_id']=$followModel->where(array('bsn_id'=>$value['bsn_id'],'open_id'=>session('open_id')))->getField('open_id');
        }
        $this->assign('list',$list);
        $this->session_open_id = session('open_id');
        $this->display('live_bsn');
    }
}
