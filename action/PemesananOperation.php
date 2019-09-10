<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 16/07/2017
 * Time: 17:48
 */
class PemesananOperation
{
    const INPUT_OPERATION = "operation";
    const INPUT_ID = "id";
    const INPUT_CUSTOMER = "name";
    const INPUT_CAR = "address";
    const INPUT_METHOD = "telephone";
    const INPUT_COMMENT = "comment";

    const OPERATION_GET = 1;
    const OPERATION_ARRAY = 2;
    const OPERATION_ADD = 3;
    const OPERATION_UPDATE = 4;
    const OPERATION_GET_NEW_ID = 5;

    const OUTPUT_ID = "id";
    const OUTPUT_CUSTOMER = "name";
    const OUTPUT_CAR = "address";
    const OUTPUT_METHOD = "telephone";
    const OUTPUT_DATE = "date";
    const OUTPUT_COMMENT = "comment";

    private $operation, $id, $customer, $car, $method, $comment;

    public function __construct(
        int $operation,
        $id,
        $customer,
        $car,
        $method,
        $comment)
    {
        $this->operation = $operation;
        $this->id = $id;
        $this->customer = $customer;
        $this->car = $car;
        $this->method = $method;
        $this->comment = $comment;
    }
    public function execute() {
        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
        switch ($this->operation) {
            case PemesananOperation::OPERATION_ADD : return $this->operationAdd($runner);
            case PemesananOperation::OPERATION_ARRAY: return $this->operationArray($runner);
        }
    }
    private function operationAdd(Runner &$runner) {

        return null;
    }
    private function operationArray(Runner &$runner) {
        $pemesanan = new Pemesanan();
        $pemesanan->setRunner($runner);
        $res = array();
        foreach ($pemesanan as $item) {
            $temp = array();
            $temp[PemesananOperation::OUTPUT_ID] = $item->getId();
            $temp[PemesananOperation::OUTPUT_CUSTOMER] = $item->getCustomer()->getId();
            $temp[PemesananOperation::OUTPUT_CAR] = $item->getMobil()->getId();
            $temp[PemesananOperation::OUTPUT_METHOD] = $item->getMethodPayment();
            $temp[PemesananOperation::OUTPUT_DATE] = $item->getTanggal();
            $temp[PemesananOperation::OUTPUT_COMMENT] = $item->getComment();
            array_push($res, $temp);
        }
        return $res;
    }
}