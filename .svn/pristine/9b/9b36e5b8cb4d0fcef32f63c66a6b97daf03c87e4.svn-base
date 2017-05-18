<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 2015/4/16
 * Time: 10:37
 */
namespace Onemla\Behaviors;

use Onemla\OnemlaHelper;
use Onemla\UCenter\UCenterHelper;
use Think\Behavior;
use Think\Route;

class OnemlaKeyWorldsBehavior extends Behavior{
    //行为执行入口
    public function run(&$param){
        $this -> keyWords();
    }

    private function keyWords(){
        $all = OnemlaHelper::lang("KeyWords.All");
        $keywords = OnemlaHelper::lang("KeyWords.".COMPONENTS_INPUT.".".CONTROLLER_NAME.".".ACTION_NAME);
        $titles = array();

        if($all){
            OnemlaHelper::getDocument()->addKeyword(join(',', $all));
            if(!$keywords['replace'])$titles = $all;
        }
        if($keywords){
            OnemlaHelper::getDocument()->addKeyword($keywords['key']);
            $titles[] = $keywords['key'];
        }
        if(count($titles)){
            OnemlaHelper::getDocument()->title = join('-', $titles);
        }
    }
}