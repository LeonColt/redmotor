<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 09/04/2017
 * Time: 11:48
 */
require_once standart_path.'dataobject/User.php';
require_once standart_path.'core/Session/Session.php';
require_once standart_path.'session_object/SessionLogin.php';
class Login
{
    const USERNAME = "username";
    const PASSWORD = "password";
    private $username, $password;
    /**
     * Login constructor.
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
    public function execute() {
        if(empty($this->username))
            throw new Exception("Username tidak boleh kosong");
        if(empty($this->password))
            throw new Exception("Password tidak boleh kosong");
        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT,
            GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME,
            GlobalConst\DBConst::DATABASE_PASSWORD);
        $user = new User();
        $user->setRunner($runner);
        $user->setUsername($this->username);
        $user->setPassword($this->password);
        if($user->login()) {
            $session = new Session();
            $session->startSession();
            $session->addSessionByKey($user->serialize(), GlobalConst\SessionKey::LOGIN);
            //$sl = new SessionLogin($user->getId());
            //$sl->setUsername($this->username);
            //$session->addSessionByKey(serialize($sl), GlobalConst\SessionKey::LOGIN);
        }
        else throw new Exception("Password Salah");
    }
}