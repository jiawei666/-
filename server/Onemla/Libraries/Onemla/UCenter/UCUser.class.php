<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 2015/4/24
 * Time: 0:35
 */
namespace Onemla\UCenter;

use Onemla\Access\OnemlaAccess;
use Onemla\OnemlaRequest;

class UCUser{
    //session信息
    protected $_sessionSet;

    /**
     * @var null
     */
    protected $_authGroups = null;

    protected static $instances = array();

    //初始化
    public function __construct($identifier = 0){
        $this->_sessionSet=new \stdClass();
        $this->_sessionSet->guest = 1;
        $this->_sessionSet->id = $identifier;
    }

    //设置用户
    public function setUser(ResultSet $resultSet){
        $this->_sessionSet->id = $resultSet->id;
        $this->_sessionSet->guest = $this->_sessionSet->id == 0;
        $this->_sessionSet->username = $resultSet->username;
        $this->_sessionSet->email = $resultSet->email;
        $this->_sessionSet->phone = $resultSet->phone;
        $this->_sessionSet->password = $resultSet->password;
        $this->_sessionSet->logo = $resultSet->logo;
    }

    //获取变量值
    public function get($property, $default = null){
        if (isset($this->_sessionSet->$property)) {
            return $this->_sessionSet->$property;
        }

        return $default;
    }

    //Method to set a parameter
    public function set($key, $value){
        $this->_sessionSet->$key = $value;
    }

    public function getUser(){
        return $this->_sessionSet;
    }

    public static function getInstance($identifier = 0){
        if ($identifier === 0) {
            return new UCUser();
        }

        // Check if the user ID is already cached.
        if (empty(self::$instances[$identifier])) {
            $user = new UCUser($identifier);
            self::$instances[$identifier] = $user;
        }

        return self::$instances[$identifier];
    }

    //更新信息
    public function updateLogin(){
        $data['last_ip'] = get_client_ip(1);
        $data['last_time'] = OnemlaRequest::requestTime();
        $data['login_count'] = $this->get('loginCount', 1);
        if($this->get('password')){
            $data['password']=$this->_sessionSet->password;
        }
        return M('user')->where("user_id = '%d'", $this->_sessionSet->id)->save($data);
    }

    public function register($credentials, $activation='', $defaultGroup=true){
        $data['username'] = isset($credentials['username']) ? $credentials['username'] : '';
        $data['name_crc'] = isset($credentials['username']) ? string_to_crc($credentials['username']) : 0;
        $data['email'] = isset($credentials['email']) ? $credentials['email'] : '';
        $data['phone'] = isset($credentials['phone']) ? $credentials['phone'] : 0;
        $password = isset($credentials['password']) ? $credentials['password'] : '';
        $data['password'] = UCenterHelper::hashPassword($credentials['secretkey'].$password);
        $data['pass_stren'] = password_strength($password);
        $data['pay_pass'] = UCenterHelper::hashPassword($credentials['secretkey'].$password);;
        $data['payass_stren'] = $data['pass_stren'];
        $data['activation'] = $activation;
        $data['acti_time'] = OnemlaRequest::requestTime();
        $data['last_ip'] = get_client_ip(1);
        $data['last_time'] = OnemlaRequest::requestTime();
        $data['login_count'] = 1;
        $data['reg_time'] = OnemlaRequest::requestTime();
        $data['logo'] = '/User/default/user.jpg';
        $data['birthday'] = '';
        $data['realname'] = '';
        $data['qq'] = '';
        $data['wechat'] = '';
        $data['address'] = '';

        if($iUserId = M('user')->add($data)){
            if($defaultGroup)
                $this->registerGroup($iUserId);
        }
        return $iUserId;
    }

    /**
     * @param $iUserId
     * @param null $defaultGroup
     * @return bool|mixed|string
     */
    public function registerGroup($iUserId, $defaultGroup = null){//
        if(is_null($defaultGroup) || is_string($defaultGroup)){
            return M('usergroup_map')->add(array(
                'user_id' => $iUserId,
                'group_id' => C('NEW_USERTYPE', null, 3)
            ));
        }elseif(is_array($defaultGroup)){
            $data = array();
            foreach($defaultGroup as $group){
                $data[] = array(
                    'user_id' => $iUserId,
                    'group_id' => $group
                );
            }
            return M('usergroup_map')->addAll($data, array(), true);
        }
        return false;
    }

    /**
     * @param $sActivation
     * @return bool
     */
    public function Resend($sActivation){
        $data['activation'] = $sActivation;
        $data['rese_time'] = OnemlaRequest::requestTime();

        return M('user')->where('user_id = "%d"', $this->_sessionSet->id)->save($data);
    }

    /**
     * @param $action
     * @return bool
     */
    public function authorise($action){
        if($this->_sessionSet->isRoot == null){//判断是否是超级用户
            $this->_sessionSet->isRoot = false;
            if(OnemlaAccess::check($this->_sessionSet->id, array(
                'group' => 1,
                'action' => 'onemla.admin'
            ))){
                $this->_sessionSet->isRoot = true;

                return true;
            }
        }
        return $this->isRoot ? true : OnemlaAccess::check($this->_sessionSet->id, $action);
    }
}