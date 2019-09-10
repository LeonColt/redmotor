<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 08/04/2017
 * Time: 12:51
 */
require_once standart_path.'dataobject/CustomDataObject.php';
require_once standart_path.'dataobject/Pemesanan.php';
class Penjualan extends CustomDataObject
{
    const TNAME = "penjualan";
    const C_ID = "id";
    const C_BOOK_FK = "id_pemesanan";
    const C_DATE = "tanggal";
    const C_TOTAL_PRICE = "total_harga";
    const C_LEASING_FK = "leasing";
    private $id, $booking, $date, $total_price, $leasing;
    public function __construct($id = null)
    {
        parent::__construct();
        $this->id = $id;
    }
    public function getId() : int{return $this->id;}
    public function getBooking() : ?Pemesanan{return $this->booking;}
    public function setBooking(Pemesanan $booking){$this->booking = $booking;}
    public function getDate() : string{return $this->date;}
    public function setDate(string $date){$this->date = $date;}
    public function getTotalPrice() : ?int{return $this->total_price;}
    public function setTotalPrice(int $total_price){$this->total_price = $total_price;}
    public function getLeasing() : ?Leasing{return $this->leasing;}
    public function setLeasing(Leasing $leasing){$this->leasing = $leasing;}

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current() : Penjualan
    {
        $penjualan = new Penjualan($this->getArr()[Penjualan::C_ID]);
        $penjualan->setBooking(new Pemesanan($this->getArr()[Penjualan::C_BOOK_FK]));
        $penjualan->setDate($this->getArr()[Penjualan::C_DATE]);
        $penjualan->setTotalPrice($this->getArr()[Penjualan::C_TOTAL_PRICE]);
        if($this->getArr()[Penjualan::C_LEASING_FK] !== null)
            $penjualan->setLeasing(new Leasing($this->getArr()[Penjualan::C_LEASING_FK]));
        return $penjualan;
    }

    protected function onLoad(): Select
    {
        $select = new Select(Penjualan::TNAME);
        $select->appendColumn(new Column(Penjualan::C_ID));
        $select->appendColumn(new Column(Penjualan::C_BOOK_FK));
        $select->appendColumn(new Column(Penjualan::C_DATE));
        $select->appendColumn(new Column(Penjualan::C_TOTAL_PRICE));
        $select->appendColumn(new Column(Penjualan::C_LEASING_FK));
        $select->append_where(new Column(Penjualan::C_ID)." = ?");
        $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->id));
        return $select;
    }

    protected function onPostLoad($data)
    {
        // TODO: Implement onPostLoad() method.
    }

    protected function onAdd(): Insert
    {
        $insert = new Insert(Penjualan::TNAME);
        $insert->appendColumnValue(new Column(Penjualan::C_ID), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getId()));
        $insert->appendColumnValue(new Column(Penjualan::C_BOOK_FK), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getBooking()->getId()));
        $insert->appendColumnValue(new Column(Penjualan::C_DATE), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getDate()));
        $insert->appendColumnValue(new Column(Penjualan::C_TOTAL_PRICE), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getTotalPrice()));
        if($this->getLeasing() !== null)
            $insert->appendColumnValue(new Column(Penjualan::C_LEASING_FK), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getLeasing()->getId()));
        return $insert;
    }

    protected function onUpdate(): Update
    {
        // TODO: Implement onUpdate() method.
    }

    protected function onDelete(): Delete
    {
        // TODO: Implement onDelete() method.
    }

    protected function onRewind(): Select
    {
        $select = new Select(Penjualan::TNAME);
        $select->appendColumn(new Column(Penjualan::C_ID));
        $select->appendColumn(new Column(Penjualan::C_BOOK_FK));
        $select->appendColumn(new Column(Penjualan::C_DATE));
        $select->appendColumn(new Column(Penjualan::C_TOTAL_PRICE));
        $select->appendColumn(new Column(Penjualan::C_LEASING_FK));
        return $select;
    }
}