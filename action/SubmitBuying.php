<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 10/07/2017
 * Time: 18:41
 */
require_once standart_path.'dataobject/Mobil.php';
require_once standart_path.'dataobject/Pembelian.php';
class SubmitBuying
{
    const INPUT_ID = "id";
    const INPUT_CAR_ID = "car_id";
    const INPUT_DEAL_PRICE = "price";
    const INPUT_SELL_PRICE = "sell_price";

    private $id, $car_id, $deal_price, $sell_price;
    public function __construct($id, $car_id, $deal_price, $sell_price) {
        $this->id = $id;
        $this->car_id = $car_id;
        $this->deal_price = $deal_price;
        $this->sell_price = $sell_price;
    }

    public function execute() {
        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);

        $car = new Mobil($this->car_id);
        $car->setPrice($this->sell_price);
        $car->setRunner($runner);

        $pembelian = new Pembelian($this->id);
        $pembelian->setDate(date("Y-m-d"));
        $pembelian->setPrice($this->deal_price);

        $car->appendLinkTransaction($pembelian, Mobil::LINK_TRANSACTION_UPDATE, Pembelian::LINK_TRANSACTION_UPDATE);

        if( !$car->update() ) throw new Exception("Harga Deal Mobil Gagal Diinput");
    }
}