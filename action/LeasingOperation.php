<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 16/07/2017
 * Time: 14:17
 */
require_once standart_path.'core/universal_methode.php';
require_once standart_path.'core/MySQLAccess.php';
require_once standart_path.'core/Random.php';
require_once standart_path.'dataobject/Leasing.php';
class LeasingOperation
{
    const INPUT_OPERATION = "operation";
    const INPUT_ID = "id";
    const INPUT_NAME = "name";
    const INPUT_ADDRESS = "address";
    const INPUT_TELEPHONE = "telephone";

    const OPERATION_GET = 1;
    const OPERATION_ARRAY = 2;
    const OPERATION_ADD = 3;
    const OPERATION_UPDATE = 4;
    const OPERATION_GET_NEW_ID = 5;

    const RETURN_ID = "id";
    const RETURN_NAME = "name";
    const RETURN_ADDRESS = "address";
    const RETURN_TELEPHONE = "telephone";

    private $operation, $id, $name, $address, $telephone;
    public function __construct(int $operation, $id, $name, $address, $telephone)
    {
        $this->operation = $operation;
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->telephone = $telephone;
    }
    public function execute() {
        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
        switch ($this->operation) {
            case LeasingOperation::OPERATION_GET: return $this->operationGet($runner);
            case LeasingOperation::OPERATION_ARRAY: return$this->operationArray($runner);
            case LeasingOperation::OPERATION_ADD: return $this->operationAdd($runner);
            case LeasingOperation::OPERATION_GET_NEW_ID: return $this->operationGetNewId($runner);
        }
        return null;
    }
    private function operationGet(Runner &$runner) : array {
        $leasing = new Leasing($this->id);
        $leasing->setRunner($runner);
        if(!$leasing->load()) throw new Exception("Leasing tidak ditemukan");
        $res = array();
        $res[LeasingOperation::RETURN_ID] = $leasing->getId();
        $res[LeasingOperation::RETURN_NAME] = $leasing->getName();
        $res[LeasingOperation::RETURN_ADDRESS] = $leasing->getAddress();
        $res[LeasingOperation::RETURN_TELEPHONE] = $leasing->getTelephone();
        return $res;
    }
    private function operationArray(Runner &$runner) : array {
        $leasing = new Leasing();
        $leasing->setRunner($runner);
        $res = array();
        foreach ($leasing as $item ) {
            $temp = array();
            $temp[LeasingOperation::RETURN_ID] = $item->getId();
            $temp[LeasingOperation::RETURN_NAME] = $item->getName();
            $temp[LeasingOperation::RETURN_ADDRESS] = $item->getAddress();
            $temp[LeasingOperation::RETURN_TELEPHONE] = $item->getTelephone();
            array_push($res, $temp);
        }
        return $res;
    }
    private function operationAdd(Runner &$runner) {
        $leasing = new Leasing($this->id);
        $leasing->setName($this->name);
        $leasing->setAddress($this->address);
        $leasing->setTelephone($this->telephone);
        $leasing->setRunner($runner);
        $leasing->add();
        return null;
    }
    private function operationUpdate(Runner &$runner) {

    }
    private function operationGetNewId(Runner &$runner) : int {
        $random = new Random();
        do {
            $runner->clearQueryArrayArray();
            $id = $random->random_number_int(0);
            $leasing = new Leasing($id);
            $leasing->setRunner($runner);
        } while($leasing->load());
        return $id;
    }

}