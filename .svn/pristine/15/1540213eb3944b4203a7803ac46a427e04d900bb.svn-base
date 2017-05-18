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

class UserInfoModel extends OnemlaModel
{

    public function get_name_logo(){
        $info = $this -> where(array(
            'user_id' => OnemlaHelper::getUserId()
        )) -> field('nick_name,logo') -> find();

//        $info['logo'] = $info['logo'] == '' ? C('TMPL_PARSE_STRING.__IMAGES__').'def_logo.jpg' : C('TMPL_PARSE_STRING.__UPLOAD__').$info['logo'];

        return $info;
    }

}