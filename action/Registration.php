<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 09/04/2017
 * Time: 12:21
 */
require_once standart_path.'action/PreRegistration.php';
require_once standart_path.'core/MySQLAccess.php';
require_once standart_path.'dataobject/User.php';
class Registration
{
    const REGISTRATION_TOKEN = "rt";
    private $rt;
    public function __construct($rt){$this->rt = $rt;}
    public function execute() {
        $file = fopen(standart_path."assets/registration/".md5($this->rt), 'r');
        $temp = "";
        while(!feof($file)) $temp .= fread($file, 4096);
        fclose($file);
        $arr = json_decode($temp, true);


        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);

        $user = new User();
        $user->setRunner($runner);
        $user->setUsername($arr[PreRegistration::USERNAME]);
        $user->setHashedPassword($arr[PreRegistration::PASSWORD]);
        $user->setName($arr[PreRegistration::NAME]);
        $user->setKtp($arr[PreRegistration::NO_KTP]);
        $user->setBirthDate($arr[PreRegistration::BIRTH_DATE]);
        ((int)$arr[PreRegistration::SEX] === User::SEX_FEMALE) ? $user->asFemale() : (((int)$arr[PreRegistration::SEX] === User::SEX_MALE) ? $user->asMale() : $user->asSexOther());
        $user->setAddress($arr[PreRegistration::ADDRESS]);
        $user->setTelp($arr[PreRegistration::TELEPHONE]);
        $user->setEmail($arr[PreRegistration::EMAIL]);
        $user->asCustomer();

        $user->add();
    }
}