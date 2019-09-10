<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 08/08/2017
 * Time: 11:34
 */
require_once standart_path.'core/MySQLAccess.php';
require_once standart_path.'dataobject/User.php';
class ChangePassword
{
    const TOKEN = "token";
    const PASSWORD = "passoword";
    private $token, $id_username_email, $password;
    public function __construct($token, $password)
    {
        $this->token = $token;
        $this->password = $password;
    }
    public function execute() {
        $file = fopen(standart_path."assets/change_password/".md5($this->token), 'r');
        $this->id_username_email = "";
        while(!feof($file)) $this->id_username_email .= fread($file, 4096);
        fclose($file);

        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
        $user = new User($this->id_username_email);
        $user->setRunner($runner);
        $user->setUsername($this->id_username_email);
        $user->setEmail($this->id_username_email);
        if(!$user->load()) throw new Exception("User Tidak Ditemukan");
        $runner->clearQueryArrayArray();
        $user->setHashedPassword(password_hash($this->password, PASSWORD_DEFAULT));
        if(!$user->changePassword()) throw new Exception("Password Gagal Diubah");
    }
}