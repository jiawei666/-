<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 2015/4/20
 * Time: 14:12
 */


class Aliocs extends \Think\Cache\Driver\Memcached{

    /**
     *
     * @param array $options
     */
    public function __construct($options = array()){
        parent::__construct($options);
        $this->handler->setOption(Memcached::OPT_COMPRESSION, false); //关闭压缩功能
        $this->handler->setOption(Memcached::OPT_BINARY_PROTOCOL, true); //使用binary二进制协议

        $options['host'] && $this->handler->addServer($options['host'], $options['port']);
        if($options['username'] && $options['password']){
            $this->handler->setSaslAuthData($options['username'], $options['password']);
        }
    }
}