<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/18 0018
 * Time: 上午 9:15
 */

namespace Components\WechatIndex\Controller;

use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Components\User\Model\UserModel;
use Components\User\Model\UserInfoModel;
use Onemla\SessionViewController;
class LiveBsnController extends ViewController
{
    /*
     * 关注直播商
     * */
    protected function _initialize() {
        $mid = session('wcid');
        if (empty($mid)) {
            $arr = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid=
' . C('WxPayConf_pub.APPID') .'&secret=' . C('WxPayConf_pub.APPSECRET') .'&code=' . $_GET['code'] . '&grant_type=authorization_code');
            $r = json_decode($arr, 1);
            if (!empty($r['openid'])) {
                $u = D('member')->field('id')->where('openid=\'' . $r['openid'] . '\'')->find();
                $arr2 = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=
" . C('WxPayConf_pub.APPID') . "&secret=" . C('WxPayConf_pub.APPSECRET') . "");
                $r2 = json_decode($arr2, 1);
                $user_wc = file_get_contents('https://api.weixin.qq.com/cgi-bin/user/info?access_token=
' . $r2['access_token'] . '&openid=' . $r['openid'] . '&lang=zh_CN');
                $r3 = json_decode($user_wc, 1);
                if (!$u) {
                    $data['openid'] = $r['openid'];
                    $data['nickname'] = $r3['nickname'];
                    $data['headimgurl'] = $r3['headimgurl'];
                    $data['ctime'] = now();
                    $id = D('member')->add($data);
                } else {
                    $u['id'] = $u['id'];
                    $u['nickname'] = $r3['nickname'];
                    $u['headimgurl'] = $r3['headimgurl'];
                    D('member')->save($u);
                    $id = $u['id'];
                }
                session('mid', $id);
            }
        }
    }

    /*
     * 直播商简介
     * */
    public function index(){
        $bsnId = OnemlaRequest::getVar('bsn_id');
        $bsnModel = M('live_bsn');
        $this->bsnInfo = $bsnModel->where(array('id'=>$bsnId))->find();
        $this->display();
    }

    /*
     *直播商频道
     * */
    public function channel(){
        $bsnId = OnemlaRequest::getVar('bsn_id');
        $bsnModel = M('live_bsn');
        $this->bsnInfo = $bsnModel->where(array('id'=>$bsnId))->find();
        $channelModel = M('live_channel');
        $this->list = $channelModel->where(array('bsn_id'=>$bsnId))->order('create_time desc')->select();
        $this->display('channel-home');
    }

    /*
     *直播商话题
     * */
    public function tags(){
        $bsnId = OnemlaRequest::getVar('bsn_id');

        $bsnModel = M('live_bsn');
        $this->bsnInfo = $bsnModel->where(array('id'=>$bsnId))->find();
        
        $tagsModel = M('live_tags');
        $this->tagsList = $tagsModel->where(array('bsn_id'=>$bsnId))->order('create_time desc')->select();

        $tagsId = OnemlaRequest::getVar('tags_id');
        $this->assign('tags_id',$tagsId);
        if($tagsId !=''){
            $relationModel = M('live_rt_relation');
            $roomIdList = $relationModel->where(array('livetags_id'=>$tagsId))->field('liveroom_id')->select();
            foreach($roomIdList as $value){
                $roomId .= $value['liveroom_id'].',';
            }
            if($roomIdList){
                $where['room_id']=array('in',$roomId);

            }else{
                $where['room_id']='' ;
            }
        }
        $where['r_bsn_id'] = $bsnId;
        $where['lock'] = 1;
        $where['audit_status'] =2;
        $roomModel = M('live_room');
        $room = $roomModel->where($where)
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
        $this->display('follow-home');
    }

    /*
     * 频道简介
     * */
    public function channel_brief(){
        $channelId = OnemlaRequest::getVar('channel_id');
        $this->assign('channel_id',$channelId);
        $bsnId = OnemlaRequest::getVar('bsn_id');
        $this->assign('bsn_id',$bsnId);
        $channelModel = M('live_channel');
        $this->channelInfo = $channelModel->where(array('id'=>$channelId,'bsn_id'=>$bsnId))->find();
        $this->display('channel-details-brief');
    }

    /*
     * 频道活动
     * */
    public function channel_activity(){
        $channelId = OnemlaRequest::getVar('channel_id');
        $this->assign('channel_id',$channelId);
        $bsnId = OnemlaRequest::getVar('bsn_id');
        $this->assign('bsn_id',$bsnId);
        $roomModel = M('live_room');
        $room = $roomModel
            ->where(array('live_channel_id'=>$channelId,'r_bsn_id'=>$bsnId,'lock'=>1,'audit_status'=>2))
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
        $this->assign('roomList',sortRoomByCount($room,0));
        $this->display('channel-details-activity');
    }

    /*
     *微信游客关注
     ***/
    public function follow(){
        if(session('open_id')==''){
            $this->httpReturn(2,'','开放于微信用户');
            exit;
        }
        $bsn_id = OnemlaRequest::getVar('bsn_id');
        $type = OnemlaRequest::getVar('type');
        $followModel = M('live_bsn_follow');
        $bsnModel = M('live_bsn');
        if($type==1){//关注
            $isFollow = $followModel->where(array('bsn_id'=>$bsn_id,'open_id'=>session('open_id')))->find();
            if($isFollow){
                $this->httpReturn(2,'','已关注');
                exit;
            }
            $data['bsn_id'] = $bsn_id;
            $data['open_id'] = session('open_id');
            $data['follow_time'] = date('Y-m-d H:i:s');
            $res = $followModel->add($data);

            $follow_count = $bsnModel->where(array('id'=>$bsn_id))->getField('follow_count');
            $res1 = $bsnModel->where(array('id'=>$bsn_id))->save(array('follow_count'=>$follow_count+1));
            if($res && $res1){
                $this->httpReturn(1);
            }else{
                $this->httpReturn(2,'','关注失败');
            }
        }else{//取消关注
            $res = $followModel->where(array('bsn_id'=>$bsn_id,'open_id'=>session('open_id')))->delete();

            $follow_count = $bsnModel->where(array('id'=>$bsn_id))->getField('follow_count');
            $res1 = $bsnModel->where(array('id'=>$bsn_id))->save(array('follow_count'=>$follow_count-1));
            if($res && $res1){
                $this->httpReturn(1);
            }else{
                $this->httpReturn(2,'','取消关注失败');
            }
        }

    }

//    /*
//     * 直播间观看数量
//     * */
//    public function view(){
//        $roomId = OnemlaRequest::getVar('roomId');
//        $roomModel = M('live_room');
//        $view_count = $roomModel->where(array('room_id'=>$roomId))->getField('view_count');
//        $res = $roomModel->where(array('room_id'=>$roomId))->save(array('view_count'=>$view_count+1));
//        if($res){
//            $this->httpReturn(1);
//        }else{
//            $this->httpReturn(2);
//        }
//    }
}