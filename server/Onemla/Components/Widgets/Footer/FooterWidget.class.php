<?php
/**
 * Created by PhpStorm.
 */
namespace Widgets\Footer;
use Onemla\OnemlaHelper;
use Onemla\ViewController;

class FooterWidget extends ViewController{
    public function Index(){

        $this -> display('Footer/Footer',  'Widgets');
    }
}