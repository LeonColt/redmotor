<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 16/07/2017
 * Time: 18:04
 */
require_once standart_path.'core/MySQLAccess.php';
require_once standart_path.'dataobject/Pemesanan.php';
class GetBookings
{
    const INPUT_LOOP = "loop";

    const LOOP_FINISH = 1;
    const LOOP_UNFINISHED = 2;

    const OUTPUT_ID = "id";
    const OUTPUT_CUSTOMER = "name";
    const OUTPUT_CAR = "address";
    const OUTPUT_METHOD = "telephone";
    const OUTPUT_DATE = "date";
    const OUTPUT_COMMENT = "comment";
    const OUTPUT_DEAL_PRICE = "deal_price";
    const OUTPUT_LEASING = "leasing";

    private $loop;
    public function __construct(int $loop)
    {
        $this->loop = $loop;
    }
    public function execute() {
        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
        $pemesanan = new Pemesanan();
        if($this->loop === Pemesanan::LOOP_BY_FINISH) $pemesanan->appendLoopBy(Pemesanan::LOOP_BY_FINISH);
        else if($this->loop === Pemesanan::LOOP_BY_UNFINISHED) $pemesanan->appendLoadBy(Pemesanan::LOOP_BY_UNFINISHED);
        $pemesanan->appendLinkJoin(DataObjectConstant::TABLE_SELLING);
        $pemesanan->appendLinkJoin(DataObjectConstant::TABLE_LEASING);
        $pemesanan->setRunner($runner);
        $res = array();
        foreach ($pemesanan as $item) {
            $temp = array();
            $temp[GetBookings::OUTPUT_ID] = $item->getId();
            $temp[GetBookings::OUTPUT_CUSTOMER] = $item->getCustomer()->getId();
            $temp[GetBookings::OUTPUT_CAR] = $item->getMobil()->getId();
            $temp[GetBookings::OUTPUT_METHOD] = $item->getMethodPayment();
            $temp[GetBookings::OUTPUT_DATE] = $item->getTanggal();
            $temp[GetBookings::OUTPUT_COMMENT] = $item->getComment();
            $temp[GetBookings::OUTPUT_DEAL_PRICE] = $item->getComment();
            $temp[GetBookings::OUTPUT_DEAL_PRICE] = $item->getSelling()->getTotalPrice();
            $temp[GetBookings::OUTPUT_LEASING] = ($item->getSelling()->getLeasing() === null ) ? null : $item->getSelling()->getLeasing()->getId();
            array_push($res, $temp);
        }
        return $res;
    }
}