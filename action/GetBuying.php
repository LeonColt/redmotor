<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 10/07/2017
 * Time: 12:29
 */
require_once standart_path.'dataobject/Pembelian.php';
class GetBuying
{
    const INPUT_DATE_FINISH_UNFINISHED = "finish_or_unfinished";

    const RETURN_ID = "id";
    const RETURN_DATE = "tanggal";
    const RETURN_CAR_ID = "car_id";
    const RETURN_CAR_NAME = "car_name";
    const RETURN_USER_ID = "user_id";
    const RETURN_USER_USERNAME = "user_username";
    const RETURN_PRICE = "price";
    const RETURN_DEAL_PRICE = "deal_price";
    const RETURN_SELL_PRICE = "sell_price";

    private $finish_or_unfinished;
    public function __construct(int $finish_or_unfinished)
    {
        $this->finish_or_unfinished = $finish_or_unfinished;
    }
    public function execute() {
        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
        $pembelian = new Pembelian();
        $pembelian->setRunner($runner);
        if($this->finish_or_unfinished === Pembelian::LOOP_BY_FINISH) $pembelian->appendLoopBy(Pembelian::LOOP_BY_FINISH);
        else if($this->finish_or_unfinished === Pembelian::LOOP_BY_UNFINISHED) $pembelian->appendLoopBy(Pembelian::LOOP_BY_UNFINISHED);

        $res = array();
        foreach ($pembelian as $item) {
            $temp = array();
            $temp[GetBuying::RETURN_ID] = $item->getId();
            $temp[GetBuying::RETURN_DATE] = $item->getDate();
            $temp[GetBuying::RETURN_CAR_ID] = $item->getCar()->getId();
            $temp[GetBuying::RETURN_CAR_NAME] = $item->getCar()->getName();
            $temp[GetBuying::RETURN_USER_ID] = $item->getCustomer()->getId();
            $temp[GetBuying::RETURN_USER_USERNAME] = $item->getCustomer()->getUsername();
            $temp[GetBuying::RETURN_PRICE] = $item->getPrice();
            $temp[GetBuying::RETURN_DEAL_PRICE] = $item->getPrice();
            $temp[GetBuying::RETURN_SELL_PRICE] = $item->getCar()->getPrice();
            array_push($res, $temp);
        }
        return $res;
    }
}