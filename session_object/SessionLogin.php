<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 09/04/2017
 * Time: 12:00
 */
require_once standart_path.'dataobject/User.php';
//deprecated
class SessionLogin
{
    const SUPER_ADMIN = 1;
    const ADMIN = 2;
    const CUSTOMER = 3;
    CONST CUSTOMER_SERVICE = 4;
    private $user_id, $username,$level, $login_time;
    public function __construct(int $user_id)
    {
        $this->user_id = $user_id;
        $this->login_time = time();
    }
    public function getUserID() : int {return $this->user_id;}
    public function getUsername() : string{return $this->username;}
    public function setUsername(string $username){$this->username = $username;}
    public function getLevel() : int{return $this->level;}
    public function setLevel(int $level){$this->level = $level;}
    public function getLoginTime() : int{return $this->login_time;}
    public function is(int $level) {return $level === $this->level;}
}