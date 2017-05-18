<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2016/9/26
 * Time: 15:45
 */
namespace Components\User\Model;

use Onemla\OnemlaHelper;
use Onemla\OnemlaModel;
use Onemla\OnemlaRequest;
use Services\User\UserService;
use Org\Page\Page;
use Components\User\Model\UserModel;

class UserInfoModel extends OnemlaModel
{

    public function get_name_logo(){
        $info = $this -> where(array(
            'user_id' => OnemlaHelper::getUserId()
        )) -> field('nick_name,logo') -> find();

//        $info['logo'] = $info['logo'] == '' ? C('TMPL_PARSE_STRING.__IMAGES__').'def_logo.jpg' : C('TMPL_PARSE_STRING.__UPLOAD__').$info['logo'];

        return $info;
    }
    public function getpersonallist(){
        $user_name = OnemlaRequest::getVar("user_name"); //用户名称
        $status = OnemlaRequest::getVar('status');//审核状态
        $umodel = new UserModel();
        $uWhere['user_name'] = array("like", "%" . $user_name . "%");
        $ulist = $umodel->where($uWhere)->select();
        if(empty($ulist)){ //如果查询为空则输出空数组
            return $msg = array();
        } else {
            foreach ($ulist as $v){
                $user_id .= $v['id'].',';
            }
            $where['type'] = 1 ;
            $where['user_id'] = array("in",  $user_id );
            $where['status'] = array("like", "%" . $status . "%");
            $count = $this->where($where)->count();
            $Page = new Page($count, $listRows = 10, array('user_name'=>$user_name));
            $limit = '' . $Page->firstRow . ',' . $Page->listRows;

            $list = $this->alias("a")
                ->where($where)
                ->join("tb_user as u on u.id = a.user_id")

                ->field("u.user_name,a.*")
                ->limit($limit)
                ->order('update_time desc')
                ->select();
            $msg = array(
                'list' => $list,
                'count' => $count,
                'page_show' => $Page->AdminShow(),
                'user_name' => $user_name,
                'status' =>$status,
            );
            return $msg;
        }
    }
    public function getcompanylist(){
        $user_name = OnemlaRequest::getVar("user_name"); //用户名称
        $status = OnemlaRequest::getVar('status');//审核状态
        $umodel = new UserModel();
        $uWhere['user_name'] = array("like", "%" . $user_name . "%");
        $ulist = $umodel->where($uWhere)->select();
        if(empty($ulist)){
            return $msg=array();
        }else{
            foreach ($ulist as $v){
                $user_id .= $v['id'].',';
            }
            $where['user_id'] = array("in",  $user_id );
            $where['type'] = 2;
            $where['status'] = array("like", "%" . $status . "%");

            $count = $this->where($where)->count();
            $Page = new Page($count, $listRows = 10, array('user_name'=>$user_name));
            $limit = '' . $Page->firstRow . ',' . $Page->listRows;

            $list = $this->alias("a")
                ->where($where)
                ->join("tb_user as u on u.id = a.user_id")
                ->field("u.user_name,a.*")
                ->limit($limit)
                ->order('update_time desc')
                ->select();
            $msg = array(
                'list' => $list,
                'count' => $count,
                'page_show' => $Page->AdminShow(),
                'user_name' => $user_name,
                'status' => $status,
            );
            return $msg;
        }
    }

    public function delUserInfo($user_id){
        $info = $this->where(array('user_id'=>$user_id))->find();
        $type = $info['type']==1 ? 'personal' : 'company';
        $res = $this->where(array('user_id'=>$user_id))->delete();
        if($res){
            unlink(delAdminFile($info['card_image'],'Res/Uploads/certification/'.$type.'/'));
            unlink(delAdminFile($info['public_logo'],'Res/Uploads/certification/'.$type.'/'));
            unlink(delAdminFile($info['qr_code'],'Res/Uploads/certification/'.$type.'/'));
        }
    }

}