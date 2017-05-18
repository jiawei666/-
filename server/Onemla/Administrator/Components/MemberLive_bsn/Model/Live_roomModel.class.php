<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/11 0011
 * Time: 下午 1:34
 */

namespace Components\MemberLive_bsn\Model;

use Onemla\OnemlaHelper;
use Onemla\OnemlaModel;
use Onemla\OnemlaRequest;
use Org\Page\Page;
use Components\User\Model\UserModel;
use Components\MemberLive_bsn\Controller\LiveroomController;

class Live_roomModel extends OnemlaModel
{
    /**
     * 所有直播间列表
     */
    public function getList() {
        $search = OnemlaRequest::getVar("");

        $user_infoModel = M('user_info');
        $live_bsnModel = M('live_bsn');
        $userModel = M('user');
        $lockIdArr = $userModel->where('`state`=2')->field('id')->select();
        foreach($lockIdArr as $value){
            $lockBsnStr .= $live_bsnModel->where('`user_id` = "'.$value['id'].'"')->getField('id').',';
        }

        $lockBsnStr = substr($lockBsnStr,0,-1);

        if($lockBsnStr !='') $where['r_bsn_id'] = array('NOT IN',$lockBsnStr);

        if($search['live_name'] != '' ){
            $userinfoWhere['live_name'] = array("like", "%" . $search['live_name'] . "%");
            $bsnIdArr = $user_infoModel->alias('a')
                    ->join('tb_live_bsn as b on b.user_id = a.user_id')
                    ->join('tb_user as u on u.id=a.user_id')
                    ->field('b.id')->where("`live_name` LIKE '%".$search['live_name']."%' and u.state=1")->select();
            foreach($bsnIdArr as $key=>$value){
                $r_bsn_id .= $value['id'].',';
            }
            $r_bsn_id = substr($r_bsn_id,0,-1);
            if($r_bsn_id=='') $where['r_bsn_id']='';
            else $where['r_bsn_id'] = array('IN',$r_bsn_id);
        }
        $where['isshow'] = array("like", "%" . $search['isshow'] . "%");
        $where['title'] = array("like", "%" . $search['title'] . "%");
        if(empty($search['city_id'])) $where['city_id'] = array("like", "%" . $search['city_id'] . "%");
        else $where['city_id'] =  $search['city_id'];
        if(empty($search['character_id'])) $where['character_id'] = array("like", "%" . $search['character_id'] . "%");
        else $where['character_id'] =  $search['character_id'];
        $where['audit_status'] = array("like", "%" . $search['audit_status'] . "%");


        $count = $this->where($where)->count();
        $Page = new Page($count, $listRows = 10,
            array(
                'isshow'=>$search['isshow'],
                'title'=>$search['title'],
                'character_id'=>$search['character_id'],
                'city_id'=>$search['city_id'],
                'province_id'=>$search['province_id'],
                'live_name'=>$search['live_name'],
                'audit_status'=>$search['audit_status'],
            ));
        $limit = '' . $Page->firstRow . ',' . $Page->listRows;


        $list = $this->field('room_id,title,introduction,image,bg_image,logo,character_id,city_id,isshow,lock,audit_status,serial_number,r_bsn_id,alink,live_id')->where($where)->limit($limit)->order('create_time desc')->select();

        $characterModel = M('live_character');

        foreach($list as $key => $val1){
            $live_bsnArr = $live_bsnModel->where(array('id'=>$val1['r_bsn_id']))->find();
            $list[$key]['live_name'] = $user_infoModel->where(array('user_id'=>$live_bsnArr['user_id']))->getField('live_name');
            $list[$key]['character']=$characterModel->where(array('id'=>$val1['character_id']))->getField('character');
        }

        $msg = array(
            'list' => $list,
            'live_name' => $search['live_name'],
            'isshow' => $search['isshow'],
            'title' => $search['title'],
            'character_id' => $search['character_id'],
            'city_id' => $search['city_id'],
            'province_id' => $search['province_id'],
            'audit_status' => $search['audit_status'],
            'count' => $count,
            'page_show' => $Page->AdminShow(),
        );
        return $msg;

    }

    /**
     *
     * @return string
     * 直播商直播间列表
     */
    public function getMemberList() {
        $relationModel = M('live_rt_relation');
        $tagsModel = M('live_tags');

        $search = OnemlaRequest::getVar("");

        $where['title'] = array("like", "%" . $search['title'] . "%");
        if(empty($search['channel_id'])) $where['live_channel_id'] = array("like", "%" . $search['channel_id'] . "%");
        else $where['live_channel_id'] =  $search['channel_id'];

        if(empty($search['character_id'])) $where['character_id'] = array("like", "%" . $search['character_id'] . "%");
        else $where['character_id'] =  $search['character_id'];

        $where['audit_status'] = array("like", "%" . $search['audit_status'] . "%");
        $where['r_bsn_id'] = getLive_bsn_id();

        if($search['tags_id'] != ''){
            $relation_room_id = $relationModel->where(array('livetags_id'=>$search['tags_id']))->field('liveroom_id')->select();
            $str='';
            foreach($relation_room_id as $value){
                $str.=$value['liveroom_id'].',';
            }
            $str = substr($str,0,-1);
            if(!$str) $str='';
            $where['room_id'] = array('in',$str);
        }

        $count = $this->alias('a')
            ->join("tb_live_channel as cn on cn.id = a.live_channel_id")
            ->join("tb_live_character as ca on ca.id = a.character_id")
            ->where($where)
            ->count();
        $Page = new Page($count, $listRows = 10,
            array(
                'title'=>$search['title'],
                'live_channel_id'=>$search['channel_id'],
                'character_id'=>$search['character_id'],
                'tags_id'=>$search['tags_id'],
                'audit_status'=>$search['audit_status'],
            ));
        $limit = '' . $Page->firstRow . ',' . $Page->listRows;

        $list = $this->alias("a")
            ->join("tb_live_channel as cn on cn.id = a.live_channel_id")//频道
            ->join("tb_live_character as ca on ca.id = a.character_id")//性质
            ->field("a.room_id,a.serial_number,a.title,a.introduction,a.image,a.bg_image,a.logo,a.live_channel_id,a.character_id,a.city_id,a.audit_status,cn.channel,ca.character")
            ->order('a.create_time desc')
            ->where($where)
            ->limit($limit)
            ->select();
        foreach($list as $key => $val1){
            $relationArr = $relationModel->where(array('liveroom_id'=>$val1['room_id']))->select();
            foreach($relationArr as $val2){
                $list[$key]['tags'] .= $tagsModel->where(array('id'=>$val2['livetags_id']))->getField('tags').'|';
            }
        }

        $msg = array(
            'list' => $list,
            'count' => $count,
            'page_show' => $Page->AdminShow(),
            'title' => $search['title'],
            'channel_id' => $search['channel_id'],
            'tags_id' => $search['tags_id'],
            'character_id' => $search['character_id'],
            'audit_status' => $search['audit_status'],
        );
        return $msg;
    }

    public function edit() {
        //自动生成HD190316001编号
        $time = date(Ymd) ;
        $time=substr($time,2);
        $last_num = $this->getfield('max(serial_number)');
        if( $last_num == ''){
            $serial_num = $last_num = 'HD'.$time.'001';
        }else{
            $daytime = substr($last_num,2,6);
            if($time == $daytime){
                $number=substr($last_num,2)+1;
                $serial_num = 'HD'.$number ;
            } else{
                $serial_num = 'HD'.$time.'001';
            }
        }

        $relationModel = M('live_rt_relation');
        $info = OnemlaRequest::getVar("");

        $exts_limit=array(
            'exts' =>  array('jpg','gif', 'png', 'jpeg'),
            'maxSize' => 2097152,
        );
        $file = upload_img($exts_limit, $rootPath = "Res/Uploads/live_room");
        $image = $file['info']['image']['savename'];
        $bg_image = $file['info']['bg_image']['savename'];
        $logo = $file['info']['logo']['savename'];

        //新增
        if (empty($info['id'])) {
            if($file['error'] != '') {//上传错误则直接return错误信息
                return  $msg = array("code" => 0, "msg" => $file['error']);
                exit;
            }

            $data = array(
                'serial_number' =>$serial_num,
                'title' =>$info['title'],
                'introduction' =>$info['introduction'],
                'image' => $image,
                'bg_image' => $bg_image,
                'logo' => $logo,
                'audit_status' => 1,
                'start_time' => $info['start_time'],
                'r_bsn_id' => getLive_bsn_id(),
                'live_channel_id' => $info['channel_id'],
                'province_id' => $info['province_id'],
                'city_id' => $info['city_id'],
                'character_id' => $info['character_id'],
                "allowChat" => $info["allowChat"],
                "allowOrder" =>  $info["allowOrder"],
                "allowShare" =>  $info["allowShare"],
                "allowImoji" =>  $info["allowImoji"],
                "allowBarrage" =>  $info["allowBarrage"],
                "allowCollect" =>  $info["allowCollect"],
                "allowReward" =>  $info["allowReward"],
                "chatMonitor" =>  $info["chatMonitor"],
                "allowGood" =>  $info["allowGood"],
                "allowRp" =>  $info["allowRp"],
                'create_time' => date('Y-m-d H:i:s'),
            );

            $id = $this->add($data);
            if ($id) {
                if($info['tags0_id'] != ''){
                    $res1 = $relationModel->add(array('liveroom_id'=>$id,'livetags_id'=>$info['tags0_id']));
                }else{ $res1 = true; }
                if($info['tags1_id'] != ''){
                    $findRelation = $relationModel->where(array('liveroom_id'=>$id,'livetags_id'=>$info['tags1_id']))->find();
                    if($findRelation){
                        $this->delete($id);
                        $msg = array("code" => 0, "msg" => "话题不能重复");
                        return $msg;
                    }
                    $res2 = $relationModel->add(array('liveroom_id'=>$id,'livetags_id'=>$info['tags1_id']));
                }else{ $res2 = true; }
                if($info['tags2_id'] != ''){
                    $findRelation = $relationModel->where(array('liveroom_id'=>$id,'livetags_id'=>$info['tags2_id']))->find();
                    if($findRelation){
                        $this->delete($id);
                        $msg = array("code" => 0, "msg" => "话题不能重复");
                        return $msg;
                    }
                    $res3 = $relationModel->add(array('liveroom_id'=>$id,'livetags_id'=>$info['tags2_id']));
                }else{ $res3 = true; }
                if($res1 && $res2 && $res3){
                    $msg = array("code" => 1, "msg" => "新增成功,等待管理员审核");
                    return $msg;
                }else{
                    $this->where(array('room_id'=>$id))->delete();
                    $msg = array("code" => 0, "msg" => "新增失败");
                    return $msg;
                }
            } else {
                $msg = array("code" => 0, "msg" => "新增失败");
                return $msg;
            }
        }
        //修改
        else {

            if($file['error'] != '' && $file['error'] != '没有文件被上传！') {//上传错误则直接return错误信息
                return  $msg = array("code" => 0, "msg" => $file['error']);
                exit;
            }

            $rInfo = $this->where(array("room_id" => $info['id']))->find();

            if($image ==''){//如果上传为空同时数据库有值,则可以上传修改
                if($rInfo['image'] == ''){
                    return  $msg = array("code" => 0, "msg" => '上传文件为空');
                    exit;
                }else{
                    $image = $rInfo['image'];
                }
            }else{
                if($file['error'] != '' && $file['error'] != '没有文件被上传！'){
                    return  $msg = array("code" => 0, "msg" => $file['error']);
                    exit;
                }
                $image_url = delAdminFile($rInfo['image'],"Res/Uploads/live_room/");
            }

            if( $bg_image == ''){//如果上传为空同时数据库有值,则可以上传修改
                if($rInfo['bg_image'] == ''){
                    return  $msg = array("code" => 0, "msg" => '上传文件为空');
                    exit;
                }else{
                    $bg_image = $rInfo['bg_image'];
                }
            }else{
                if($file['error'] != '' && $file['error'] != '没有文件被上传！'){
                    return  $msg = array("code" => 0, "msg" => $file['error']);
                    exit;
                }
                $bg_image_url = delAdminFile($rInfo['bg_image'],"Res/Uploads/live_room/");
            }
            if($logo == ''){//如果上传为空同时数据库有值,则可以上传修改
                if($rInfo['logo'] ==''){
                    return  $msg = array("code" => 0, "msg" => '上传文件为空');
                    exit;
                }else{
                    $logo = $rInfo['logo'];
                }
            }else{
                if($file['error'] != '' && $file['error'] != '没有文件被上传！'){
                    return  $msg = array("code" => 0, "msg" => $file['error']);
                    exit;
                }
                $logo_url = delAdminFile($rInfo['logo'],"Res/Uploads/live_room/");
            }

            $data = array(
                'title' =>$info['title'],
                'introduction' =>$info['introduction'],
                'image' => $image,
                'bg_image' => $bg_image,
                'logo' => $logo,
                'audit_status' => 1,
                'start_time' => $info['start_time'],
                'r_bsn_id' => getLive_bsn_id(),
                'live_channel_id' => $info['channel_id'],
                'province_id' => $info['province_id'],
                'city_id' => $info['city_id'],
                'character_id' => $info['character_id'],
                "allowChat" => $info["allowChat"],
                "allowOrder" =>  $info["allowOrder"],
                "allowShare" =>  $info["allowShare"],
                "allowImoji" =>  $info["allowImoji"],
                "allowBarrage" =>  $info["allowBarrage"],
                "allowCollect" =>  $info["allowCollect"],
                "allowReward" =>  $info["allowReward"],
                "chatMonitor" =>  $info["chatMonitor"],
                "allowGood" =>  $info["allowGood"],
                "allowRp" =>  $info["allowRp"],
            );
            $relationModel->where(array('liveroom_id'=>$info['id']))->delete();//删除直播活动跟话题的对应关系
            if($info['tags0_id'] != ''){
                $res1 = $relationModel->add(array('liveroom_id'=>$info['id'],'livetags_id'=>$info['tags0_id']));
            }else{ $res1 = true; }

            if($info['tags1_id'] != ''){
                $findRelation = $relationModel->where(array('liveroom_id'=>$info['id'],'livetags_id'=>$info['tags1_id']))->find();
                if($findRelation){
                    $msg = array("code" => 0, "msg" => "话题不能重复");
                    return $msg;
                }
                $res2 = $relationModel->add(array('liveroom_id'=>$info['id'],'livetags_id'=>$info['tags1_id']));
            }else{ $res2 = true; }

            if($info['tags2_id'] != ''){
                $findRelation = $relationModel->where(array('liveroom_id'=>$info['id'],'livetags_id'=>$info['tags2_id']))->find();
                if($findRelation){
                    $msg = array("code" => 0, "msg" => "话题不能重复");
                    return $msg;
                }
                $res3 = $relationModel->add(array('liveroom_id'=>$info['id'],'livetags_id'=>$info['tags2_id']));
            }else{ $res3 = true; }

            if($res1 && $res2 && $res3){
                $id = $this->where(array("room_id" => $info['id']))->save($data);
                if($id or $id==0){//只修改话题的话,id=0
                    unlink($image_url);
                    unlink($bg_image_url);
                    unlink($logo_url);
                    $msg = array("code" => 1, "msg" => "修改成功,等待管理员审核");
                    return $msg;
                }else{
                    $msg = array("code" => 0, "msg" => "修改失败,请重试");
                    return $msg;
                }
            }else{
                $this->where(array('room_id'=>$info['id']))->delete();
                $msg = array("code" => 0, "msg" => "修改失败,话题错误");
                return $msg;
            }

        }
    }


    /*
     * 删除直播间
     * */
    public function delRoom($id){
        $model = M('live_room');
        $info = $model->where(array('room_id'=>$id))->find();
        $res = $model->where(array('room_id'=>$id))->delete();
        if($res){
            unlink($_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Res/Uploads/live_room/'.$info['image']);//同时删除图片
            unlink($_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Res/Uploads/live_room/'.$info['bg_image']);//同时删除图片
            unlink($_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Res/Uploads/live_room/'.$info['logo']);//同时删除图片
            $relationModel = M('live_rt_relation');
            $relationModel->where(array('liveroom_id'=>$id))->delete();
            return 1;
        }else{
            return 2;
        }
    }

    /*
     * 删除banner
     * **/
    public function delBanner($id){
        $bannerModel = M('live_banner');
        $image = $bannerModel->where(array('id'=>$id))->getField('image');
        $res = $bannerModel->where(array('id'=>$id))->delete();
        if($res){
            unlink(delAdminFile($image,'Res/Uploads/live_banner/'));
        }
        return $res;
    }

    /*
     * 删除性质
     * **/
    public function delCharacter($id){
        $model = M('live_character');
        $roomModel = M('live_room');
        $find = $roomModel->where(array('character_id'=>$id))->find();
        if(!empty($find)) return 'roomFalse';
        $res = $model->where(array('id'=>$id))->delete();
        if($res){
            $roomArr = $roomModel->where(array('character_id'=>$id))->field('room_id')->select();
            foreach($roomArr as $value){
                self::delRoom($value['room_id']);
            }
        }
        return $res;
    }

    /*
     * 删除频道
     * */
    public function delChannel($id){
        $model = M('live_channel');
        $roomModel = M('live_room');
        $channelImage = $model->where(array('id'=>$id))->getField('image');
        $channelItdImage = $model->where(array('id'=>$id))->getField('itd_image');

        $find = $roomModel->where(array('live_channel_id'=>$id))->find();
        if(!empty($find)) return 'roomFalse';
        $roomImageArr = $roomModel->where(array('live_channel_id'=>$id))->field('image,bg_image,logo')->select();
        $res = $model->where(array('id'=>$id))->delete();
        $res2 = $roomModel->where(array('live_channel_id'=>$id))->delete();

        if($res){
            unlink(delAdminFile($channelImage,'Res/Uploads/live_channel/'));
            unlink(delAdminFile($channelItdImage,'Res/Uploads/live_channel/'));
            if($res2){
                foreach($roomImageArr as $key=>$value){
                    unlink(delAdminFile($value['image'],'Res/Uploads/live_room/'));
                    unlink(delAdminFile($value['bg_image'],'Res/Uploads/live_room/'));
                    unlink(delAdminFile($value['logo'],'Res/Uploads/live_room/'));
                }
            }
        }
        return $res ;
    }

    /*
     * 删除话题
     * */
    public function delTags($id){
        $relationModel = M('live_rt_relation');
        $relationModel->where(array('livetags_id'=>$id))->delete();

        $model = M('live_tags');
        $res = $model->where(array('id'=>$id))->delete();

        return $res;
    }
}