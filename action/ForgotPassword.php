<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 08/08/2017
 * Time: 13:29
 */
require_once standart_path.'core/universal_methode.php';
require_once standart_path.'core/RequestPageConstant.php';
require_once standart_path.'core/MySQLAccess.php';
require_once standart_path.'core/Random.php';
require_once standart_path.'action/ChangePassword.php';
class ForgotPassword
{
    const ID_USERNAME = "id_username";
    private $id_username;
    public function __construct($id_username)
    {
        $this->id_username = $id_username;
    }
    public function execute() {
        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
        $user = new User($this->id_username);
        $user->setRunner($runner);
        $user->setUsername($this->id_username);
        if(!$user->load()) throw new Exception("User Tidak Ditemukan");
        $random = new Random();
        $token = $random->alphaNumeric(128);
        $path = standart_path."assets/change_password/".md5($token);
        $file = fopen($path, 'w');
        fwrite($file, $this->id_username);
        fclose($file);

        $link = addGetToUrl(getUrlByRequest(RequestPageConstant::CHANGE_PASSWORD), ChangePassword::TOKEN, $token );
        $to = $user->getEmail();
        $subject = "Red Motor Lupa Password";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <no-reply@redmotor.xyz>' . "\r\n";
        $message = '<h1 align="center">Link Lupa Password</h1 align="center">
<br>
<a href="'.$link.'">'.$link.'</a>';
        if(!mail($to, $subject, $message, $headers))
            throw new Exception("Konfirmasi gagal dikirim");
    }
}