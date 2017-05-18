<?php
/**
 * Created by PhpStorm.
 * User: bo
 * Date: 2015/9/7
 * Time: 15:34
 */
namespace Services\SMS;

use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\OnemlaService;

class SMSService extends OnemlaService {

    const CONFIG_NAME = 'SMS_CONFIG';

    /**
     * 对应短信模板key
     **/
    const TEMP_REG = 'TEMP_REG';//注册验证码

    private static $SMS_DATA = array(
        'ACCOUNT_SID' => 'aaf98f89528c219c0152906ee55606a8',
        'ACCOUNT_TOKEN' => '6a866143de4943948b5d241e86ee45cc',
        'APP_ID' => '8a216da85afaadec015afdff15cd00a7',

        //请求地址
        //沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
        //生产环境（用户应用上线使用）：app.cloopen.com
        'SERVER_IP' => 'app.cloopen.com',

        //请求端口，生产环境和沙盒环境一致
        'SERVER_PORT' => '8883',

        //REST版本号，在官网文档REST介绍中获得。
        'SOFT_VERSION' => '2013-12-26',

        //短信模板
        'TEMP_REG' => '45255'
    );


    public function sendRegSMS($phone){
        $data = array(
            generate_code(),
            '1'
        );
        $result = $this -> sendSMS($phone,$data,self::TEMP_REG);

        return trim($result) == '' ? true : false;
    }


    /**
     * $phone 接受短信的手机号
     * $tempId 模板id
     * $datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
     **/
    private function sendSMS($phone,$datas,$temp_key){
        include_once("../Libraries/Onemla/SMSSDK/CCPRestSmsSDK.php");

        $data = $this -> get_data();

        $rest = new \REST($data['SERVER_IP'],$data['SERVER_PORT'],$data['SOFT_VERSION']);
        $rest->setAccount($data['ACCOUNT_SID'],$data['ACCOUNT_TOKEN']);
        $rest->setAppId($data['APP_ID']);

        // 发送模板短信
        $result = $rest->sendTemplateSMS($phone,$datas,$data[$temp_key]);
        if($result == NULL ) {
            return 'result error!';
        }
        if($result->statusCode!=0) {
//            echo "error code :" . $result->statusCode . "<br>";
//            echo "error msg :" . $result->statusMsg . "<br>";
            //TODO 添加错误处理逻辑

            return $result->statusMsg;
        }else{
            echo "Sendind TemplateSMS success!<br/>";
            // 获取返回信息
            $smsmessage = $result->TemplateSMS;
//            echo "dateCreated:".$smsmessage->dateCreated."<br/>";
//            echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
//            TODO 添加成功处理逻辑
            return '';
        }
    }

//    private function get_data(){
//        $config = M('config');
//        $info = $config -> where(array('config_name' => self::CONFIG_NAME)) -> find();
//        if(empty($info)){
//            $config -> add(array(
//                'config_name' => self::CONFIG_NAME,
//                'config_data' => json_encode(self::$SMS_DATA),
//                'update_time' => time(),
//                'create_time' => time()
//            ));
//            $data = self::$SMS_DATA;
//        }else{
//            $data = json_decode($info['config_data'],true);
//        }
//
//        return $data;
//    }

}