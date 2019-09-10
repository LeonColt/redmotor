<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 15/07/2017
 * Time: 20:22
 */
require_once standart_path.'core/MySQLAccess.php';
require_once standart_path.'dataobject/Pemesanan.php';
require_once standart_path.'dataobject/Mobil.php';
require_once standart_path.'dataobject/User.php';
require_once standart_path.'core/universal_methode.php';
class SubmitPesan
{
    const INPUT_ID = "id";
    const INPUT_CAR = "mobil";
    const INPUT_METHOD = "method";
    const INPUT_COMMENT = "comment";
    private $id, $customer, $car, $method, $comment;
    public function __construct(int $id, int $car, int $method, $comment)
    {
        $this->id = $id;
        $this->method = $method;
        $this->car = $car;
        $this->comment = $comment;
        $this->customer = GetCurrentUser();
        if($this->customer === null) throw new Exception("Pembeli harus Login dulu");
    }
    public function execute() {
        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
        $pemesanan = new Pemesanan($this->id);
        $pemesanan->setCustomer($this->customer);
        $car = new Mobil($this->car);
        $pemesanan->setMobil($car);
        $pemesanan->setMethodPayment(($this->method === Pemesanan::METHOD_CASH) ? Pemesanan::METHOD_CASH : Pemesanan::METHOD_CREDIT);
        $pemesanan->setTanggal(date('Y-m-d'));
        $pemesanan->setComment($this->comment);
        $pemesanan->setRunner($runner);
        $pemesanan->add();

        $message = "ID Pemesanan : ".$this->id;

        if(!mail($this->customer->getEmail(), "Konfirmasi Pemesanan Mobil", $message)) throw new Exception("Email Gagal Dikirim");
    }
}