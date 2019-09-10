<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 16/07/2017
 * Time: 17:40
 */
require_once standart_path.'core/MySQLAccess.php';
require_once standart_path.'dataobject/User.php';
class UserOperation
{
    const INPUT_OPERATION = "operation";
    const INPUT_ID = "id";
    const INPUT_USERNAME = "username";
    const INPUT_PASSWORD = "password";
    const INPUT_NAME = "name";
    const INPUT_KTP = "no_ktp";
    const INPUT_BIRTH_DATE = "birth_date";
    const INPUT_SEX = "sex";
    const INPUT_ADDRESS = "address";
    const INPUT_TELEPHONE = "phone";
    const INPUT_EMAIL = "email";
    const INPUT_LEVEL = "level";

    const OPERATION_GET = 1;
    const OPERATION_ARRAY = 2;
    const OPERATION_ADD = 3;
    const OPERATION_UPDATE = 4;
    const OPERATION_GET_NEW_ID = 5;

    const OUTPUT_ID = "id";
    const OUTPUT_USERNAME = "username";
    const OUTPUT_PASSWORD = "password";
    const OUTPUT_NAME = "name";
    const OUTPUT_KTP = "no_ktp";
    const OUTPUT_BIRTH_DATE = "birth_date";
    const OUTPUT_SEX = "sex";
    const OUTPUT_ADDRESS = "address";
    const OUTPUT_TELEPHONE = "phone";
    const OUTPUT_EMAIL = "email";
    const OUTPUT_LEVEL = "level";

    private $operation, $id, $username, $password, $name, $ktp, $birth_date, $sex, $address, $telephone, $email, $level;
    public function __construct(
        int $operation,
        $id,
        $username,
        $password,
        $name,
        $ktp,
        $birth_Date,
        $sex,
        $address,
        $telephone,
        $email,
        $level)
    {
        $this->operation = $operation;
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->name = $name;
        $this->ktp = $ktp;
        $this->birth_date = $birth_Date;
        $this->sex = $sex;
        $this->address = $address;
        $this->telephone = $telephone;
        $this->email = $email;
        $this->level = $level;
    }

    public function execute() {
        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
        switch ($this->operation){
            case UserOperation::OPERATION_GET : return $this->operationGet($runner);
            case UserOperation::OPERATION_UPDATE : return $this->operationUpdate($runner);
        }
    }
    private function operationGet(Runner &$runner) {
        $user = new User($this->id);
        $user->setRunner($runner);
        if( !$user->load() ) throw new Exception("User Not Found");
        $temp = array();
        $temp[UserOperation::OUTPUT_ID] = $user->getId();
        $temp[UserOperation::OUTPUT_USERNAME] = $user->getUsername();
        $temp[UserOperation::OUTPUT_NAME] = $user->getName();
        $temp[UserOperation::OUTPUT_KTP] = $user->getKtp();
        $temp[UserOperation::OUTPUT_BIRTH_DATE] = $user->getBirthDate();
        $temp[UserOperation::OUTPUT_SEX] = $user->getSex();
        $temp[UserOperation::OUTPUT_ADDRESS] = $user->getAddress();
        $temp[UserOperation::OUTPUT_TELEPHONE] = $user->getTelp();
        $temp[UserOperation::OUTPUT_EMAIL] = $user->getEmail();
        $temp[UserOperation::OUTPUT_LEVEL] = $user->getLevel();
        return $temp;
    }
    private function operationUpdate(Runner &$runner) {
        $user = new User($this->id);
        $user->setUsername($this->username);
        $user->setName($this->name);
        $user->setKtp($this->ktp);
        $user->setBirthDate($this->birth_date);
        $user->setSex($this->sex);
        $user->setAddress($this->address);
        $user->setTelp($this->telephone);
        $user->setEmail($this->email);
        $user->setLevel($this->level);
        $user->setRunner($runner);
        $user->update();
        return null;
    }
}