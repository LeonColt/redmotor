<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 17/07/2017
 * Time: 20:26
 */
require_once standart_path.'core/MySQLAccess.php';
require_once standart_path.'dataobject/Pemesanan.php';
class CloseBooking
{
    const INPUT_ID = "id";
    private $id;
    public function __construct($id)
    {
        $this->id = $id;
    }
    public function execute() {
        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
        $pemesanan = new Pemesanan($this->id);
        $pemesanan->setRunner($runner);
        $pemesanan->delete();
    }
}