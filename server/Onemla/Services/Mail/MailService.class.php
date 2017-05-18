<?php
/**
 * Created by PhpStorm.
 * User: bo
 * Date: 2015/9/7
 * Time: 15:34
 */
namespace Services\Mail;

use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\OnemlaService;

class MailService extends OnemlaService {

    const MAIL_HOST = 'smtp.163.com';
    const MAIL_NAME = 'bo13798967543@163.com';
    const MAIL_PWD = 'whenis679';
    const MAIL_FORM_NAME = 'large';

    public function sendFindPWDMail($mail){

        $mail_code = generate_code(6);
        $_SESSION['mail_code'] = $mail_code;
        $_SESSION['mail'] = $mail;
        $_SESSION['mail_code_timeout'] = time()+60;

        return $this -> sendMail($mail,'密码找回','验证码：'.$mail_code.'。');
    }

    public function sendMail($to,$title,$content,$file = array()){
        $str = substr(dirname(__FILE__),0,-13);
        require $str.'/Libraries/Onemla/PHPMailer/PHPMailer.class.php';
        $mail = new \PHPMailer();
        $mail->IsSMTP(); // 启用SMTP
        $mail->Host 	= self::MAIL_HOST;//C('MAIL_HOST'); //smtp服务器的名称（这里以QQ邮箱为例）
        $mail->SMTPAuth = true;//C('MAIL_SMTPAUTH'); //启用smtp认证
        $mail->Username = self::MAIL_NAME;// C('MAIL_USERNAME'); //你的邮箱名
        $mail->Password = self::MAIL_PWD;//C('MAIL_PASSWORD') ; //邮箱密码kvshlihdssjubhij
        $mail->From 	= self::MAIL_NAME;//C('MAIL_FROM'); //发件人地址（也就是你的邮箱地址）
        $mail->FromName = self::MAIL_FORM_NAME;//C('MAIL_FROMNAME'); //发件人姓名

        $mail->AddAddress($to,$to);
        $mail->WordWrap = 50; //设置每行字符长度
        $mail->IsHTML(true);//(C('MAIL_ISHTML')); // 是否HTML格式邮件
        $mail->CharSet	= 'utf-8';//C('MAIL_CHARSET'); //设置邮件编码
        $mail->Subject 	= $title; //邮件主题
        $mail->Body 	= $content; //邮件内容
        if($file){
            $mail->AddAttachment($file['url'],$file['name']);
        }
        //$mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
        $mail->Send();
        return $mail->ErrorInfo;
    }

}