<?php

namespace Onemla;
use Onemla\UCenter\UCUser;
use Onemla\ViewController;

class ServeViewController extends  SessionViewController{
    public function __construct() {
        parent::__construct();

//        !OnemlaHelper::isLogin() && $this->redirect('Admin/Index/index');
    }
}