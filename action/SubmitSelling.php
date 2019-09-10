<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 17/07/2017
 * Time: 17:43
 */
require_once standart_path.'core/MySQLAccess.php';
require_once standart_path.'core/Random.php';
require_once standart_path.'dataobject/Penjualan.php';
require_once standart_path.'dataobject/Pemesanan.php';
require_once standart_path.'dataobject/User.php';
class SubmitSelling
{
    const INPUT_BOOK_ID = "book_id";
    const INPUT_TOTAL_PRICE = "total_price";
    const INPUT_LEASING_ID = "leasing_id";

    private $book_id, $total_price, $leasing_id;
    public function __construct(int $book_id, int $total_price, $leasing_id)
    {
        $this->book_id = $book_id;
        $this->total_price = $total_price;
        $this->leasing_id = $leasing_id;
    }
    public function execute() {
        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);

        $pemesanan = new Pemesanan($this->book_id);
        $pemesanan->setRunner($runner);
        if(!$pemesanan->load()) throw new Exception("Pemesanan tidak ditemukan");

        $runner->clearQueryArrayArray();
        $buyer = $pemesanan->getCustomer();
        $buyer->setRunner($runner);
        if(!$buyer->load()) throw new Exception("Customer tidak ditemukan");

        $runner->clearQueryArrayArray();

        $random = new Random();
        do {
            $runner->clearQueryArrayArray();
            $id = $random->random_number_int(0);
            $penjualan = new Penjualan($id);
            $penjualan->setRunner($runner);
        } while($penjualan->load());

        $penjualan = new Penjualan($id);
        $penjualan->setBooking(new Pemesanan($this->book_id));
        $penjualan->setDate(date("Y-m-d H:i:s"));
        $penjualan->setTotalPrice($this->total_price);
        if($this->leasing_id !== null && $this->leasing_id !== 0) $penjualan->setLeasing(new Leasing($this->leasing_id));
        $penjualan->setRunner($runner);
        if(!$penjualan->add()) throw new Exception("Gagal Input Penjualan, Silakan Coba Lagi");

        //output struct
        $to = $buyer->getEmail();
        $subject = "Pembelian";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <no-reply@redmotor.xyz>' . "\r\n";
        $message = "<link rel=\"stylesheet\" href=\"\">
<h1 align=\"center\">Pembelian</h1 align=\"center\">
<table border=\"2\" align=\"center\">
    <tr>
        <th>ID</th>
        <th>:</th>
        <th>".$id."</th>
    </tr>
    <tr>
        <th>ID Pemesanan</th>
        <th>:</th>
        <th>".$pemesanan->getId()."</th>
    </tr>
    <tr>
        <th>Tanggal</th>
        <th>:</th>
        <th>".$penjualan->getDate()."</th>
    </tr>
    <tr>
        <th>ID Mobil</th>
        <th>:</th>
        <th>".$pemesanan->getMobil()->getId()."</th>
    </tr>
    <tr>
        <th>Total</th>
        <th>:</th>
        <th>".$penjualan->getTotalPrice()."</th>
    </tr>
    <tr>
        <th>Metode Pembayaran</th>
        <th>:</th>
        <th>".(($pemesanan->getMethodPayment() === Pemesanan::METHOD_CASH) ? "Tunai" : "Kredit")."</th>
    </tr>
    <tr>
        <th>Leasing ID</th>
        <th>:</th>
        <th>".(($this->leasing_id !== null && $this->leasing_id !== 0) ? "-" : $this->leasing_id)."</th>
    </tr>
</table>";
        if(!mail($to, $subject, $message, $headers)) throw new Exception("Email Pembayaran Gagal Dikirim");
    }
}