<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 09/04/2017
 * Time: 12:17
 */
require_once standart_path.'core/Random.php';
require_once standart_path.'action/Registration.php';
require_once standart_path.'core/universal_methode.php';
require_once standart_path.'core/RequestPageConstant.php';
class PreRegistration
{
    const USERNAME = "username";
    const PASSWORD = "password";
    const NAME = "name";
    const NO_KTP = "no_ktp";
    const BIRTH_DATE = "birth_date";
    const SEX = "sex";
    const ADDRESS = "address";
    const TELEPHONE = "phone";
    const EMAIL = "email";
    const LEVEL = "level";

    private $arr;
    public function __construct($username,
                                $password,
                                $name,
                                $ktp,
                                $birth_date,
                                $sex,
                                $address,
                                $phone,
                                $email,
                                $level)
    {
        $this->arr = array();
        $this->arr[PreRegistration::USERNAME] = $username;
        $this->arr[PreRegistration::PASSWORD] = password_hash($password, PASSWORD_DEFAULT);
        $this->arr[PreRegistration::NAME] = $name;
        $this->arr[PreRegistration::NO_KTP] = $ktp;
        $this->arr[PreRegistration::BIRTH_DATE] = $birth_date;
        $this->arr[PreRegistration::SEX] = $sex;
        $this->arr[PreRegistration::ADDRESS] = $address;
        $this->arr[PreRegistration::TELEPHONE] = $phone;
        $this->arr[PreRegistration::EMAIL] = $email;
        $this->arr[PreRegistration::LEVEL] = $level;
    }
    public function execute() : string {
        $random = new Random();
        $token = $random->alphaNumeric(128);
        //$this->arr[Registration::REGISTRATION_TOKEN] = $token;
        $path = standart_path."assets/registration/".md5($token);
        $file = fopen($path, 'w');
        fwrite($file, json_encode($this->arr));
        fclose($file);

        $link = addGetToUrl(getUrlByRequest(RequestPageConstant::CONFIRMATION_REGISTRATION), Registration::REGISTRATION_TOKEN, $token);
        $to = $this->arr[PreRegistration::EMAIL];
        $subject = "Tagihan Service";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <no-reply@kepuhmobil.xyz>' . "\r\n";
        $message = "<link rel=\"stylesheet\" href=\"\">
<h1 align=\"center\">Tagihan Service</h1 align=\"center\">
<table border=\"2\" align=\"center\">
    <tr>
        <th>link</th>
        <th>:</th>
        <th>".$link."</th>
    </tr>
</table>";
        if(!mail($to, $subject, $message, $headers))
            throw new Exception("Konfirmasi gagal dikirim");
        return $token;
    }
}