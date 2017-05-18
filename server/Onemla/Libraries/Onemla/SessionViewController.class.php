<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 2015/5/13
 * Time: 14:51
 */
namespace Onemla;
use Onemla\OnemlaHelper;

class SessionViewController extends  ViewController{

    protected $isRefer = true;

    public function __construct() {
        parent::__construct();
        
        !OnemlaHelper::isLogin() && $this->redirect('User/Index/login_page');

    }

}