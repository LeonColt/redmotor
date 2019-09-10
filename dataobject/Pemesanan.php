<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 05/07/2017
 * Time: 16:41
 */
require_once standart_path.'dataobject/CustomDataObject.php';
require_once standart_path.'dataobject/Penjualan.php';
require_once standart_path.'dataobject/User.php';
require_once standart_path.'dataobject/Mobil.php';
require_once standart_path.'dataobject/Leasing.php';
class Pemesanan extends CustomDataObject
{
    const TNAME = "pemesanan";
    const C_ID = "id";
    const C_CUSTOMER_FK = "customer";
    const C_CAR_FK = "mobil";
    const C_METHOD = "metode_pay_NULL_CA_EIN_CR"; // it either zero or one
    const C_DATE = "tanggal";
    const C_COMMENT = "komentar";

    const CJA_SELLING_ID = "id_penjualan";
    const CJA_SELLING_TOTAL_PRICE = "total_harga";
    const CJA_LEASING_ID= "leasing_id";

    const METHOD_CASH = 0;
    const METHOD_CREDIT = 1;
    const LOAD_WITH_CUSTOMER = 1;
    const LOAD_WITH_CAR = 2;
    const LOAD_WITH_DATE = 3;
    const LOAD_WITH_PROPOSED_PRICE = 4;

    const LOOP_BY_FINISH = 10;
    const LOOP_BY_UNFINISHED = 11;

    private $load_with;

    private $id, $customer, $mobil, $tanggal, $comment, $method_payment, $selling;
    /**
     * Pemesanan constructor.
     * @param int $id
     */
    public function __construct($id = null){parent::__construct();$this->id = $id; $this->load_with = array();}
    public function getId() : int{return $this->id;}
    public function getCustomer() : User{return $this->customer;}
    public function setCustomer(User $customer){$this->customer = $customer;}
    public function getMobil() : Mobil{return $this->mobil;}
    public function setMobil(Mobil $mobil){$this->mobil = $mobil;}
    public function setMethodPayment(int $method_payment){$this->method_payment = $method_payment;}
    public function getMethodPayment() : int {return $this->method_payment;}
    public function getTanggal() : string{return $this->tanggal;}
    public function setTanggal(string $tanggal){$this->tanggal = $tanggal;}
    public function setComment(?string $comment) {$this->comment = $comment;}
    public function getComment() : ?string {return $this->comment;}
    public function getSelling() : Penjualan {return $this->selling;}
    public function current() : Pemesanan
    {
        $pemesanan = new Pemesanan($this->getArr()[Pemesanan::C_ID]);
        $pemesanan->setCustomer(new User($this->getArr()[Pemesanan::C_CUSTOMER_FK]));
        $pemesanan->setMobil(new Mobil($this->getArr()[Pemesanan::C_CAR_FK]));
        $pemesanan->setMethodPayment($this->getArr()[Pemesanan::C_METHOD]);
        $pemesanan->setTanggal($this->getArr()[Pemesanan::C_DATE]);
        $pemesanan->setComment($this->getArr()[Pemesanan::C_COMMENT]);
        $selling = new Penjualan($this->getArr()[Pemesanan::CJA_SELLING_ID]);
        if($this->getArr()[Pemesanan::CJA_SELLING_TOTAL_PRICE] !== null) $selling->setTotalPrice($this->getArr()[Pemesanan::CJA_SELLING_TOTAL_PRICE]);
        if($this->getArr()[Pemesanan::CJA_LEASING_ID] !== null) {
            $leasing = new Leasing($this->getArr()[Pemesanan::CJA_LEASING_ID]);
            $selling->setLeasing($leasing);
        }
        $pemesanan->selling = $selling;
        return $pemesanan;
    }

    protected function onLoad(): Select
    {
        $select = new Select(Pemesanan::TNAME);
        $select->appendColumn(new Column(Pemesanan::C_ID, Pemesanan::TNAME));
        $select->appendColumn(new Column(Pemesanan::C_CUSTOMER_FK));
        $select->appendColumn(new Column(Pemesanan::C_CAR_FK));
        $select->appendColumn(new Column(Pemesanan::C_METHOD));
        $select->appendColumn(new Column(Pemesanan::C_DATE, Pemesanan::TNAME));
        $select->appendColumn(new Column(Pemesanan::C_COMMENT));
        if(in_array(Pemesanan::LOAD_WITH_CUSTOMER, $this->load_with)) {
            $select->appendColumn(new Column(Pemesanan::C_CUSTOMER_FK));
        }
        $select->append_where(Pemesanan::C_ID."=?");
        $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->id));
        return $select;
    }

    protected function onPostLoad($data)
    {
        $this->id = $data[Pemesanan::C_ID];
        $this->setCustomer(new User($data[Pemesanan::C_CUSTOMER_FK]));
        $this->setMobil(new Mobil($data[Pemesanan::C_CAR_FK]));
        $this->setMethodPayment($data[Pemesanan::C_METHOD]);
        $this->setTanggal($data[Pemesanan::C_DATE]);
        $this->setComment($data[Pemesanan::C_COMMENT]);
    }
    protected function onAdd(): Insert
    {
        $insert = new Insert(Pemesanan::TNAME);
        $insert->appendColumnValue(new Column(Pemesanan::C_ID), new Parameter($insert->getParameterVariableIntegerOrder(), $this->id));
        $insert->appendColumnValue(new Column(Pemesanan::C_CUSTOMER_FK), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getCustomer()->getId()));
        $insert->appendColumnValue(new Column(Pemesanan::C_CAR_FK), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getMobil()->getId()));
        $insert->appendColumnValue(new Column(Pemesanan::C_METHOD), new Parameter($insert->getParameterVariableIntegerOrder(), $this->method_payment));
        $insert->appendColumnValue(new Column(Pemesanan::C_DATE), new Parameter($insert->getParameterVariableIntegerOrder(), $this->tanggal));
        $insert->appendColumnValue(new Column(Pemesanan::C_COMMENT), new Parameter($insert->getParameterVariableIntegerOrder(), $this->comment));
        return $insert;
    }

    protected function onUpdate(): Update
    {
        // TODO: Implement onUpdate() method.
    }

    protected function onDelete(): Delete
    {
        // TODO: Implement onDelete() method.
        $delete = new Delete(Pemesanan::TNAME);
        $delete->append_where(new Column(Pemesanan::C_ID)."=?");
        $delete->appendParameter(new Parameter($delete->getParameterVariableIntegerOrder(), $this->id));
        return $delete;
    }

    protected function onRewind(): Select
    {
        $select = new Select(Pemesanan::TNAME);
        $select->appendColumn(new Column(Pemesanan::C_ID, Pemesanan::TNAME));
        $select->appendColumn(new Column(Pemesanan::C_CUSTOMER_FK));
        $select->appendColumn(new Column(Pemesanan::C_CAR_FK));
        $select->appendColumn(new Column(Pemesanan::C_METHOD));
        $select->appendColumn(new Column(Pemesanan::C_DATE, Pemesanan::TNAME));
        $select->appendColumn(new Column(Pemesanan::C_COMMENT));
        $select->appendColumn(new Column(Penjualan::C_ID, Penjualan::TNAME, Pemesanan::CJA_SELLING_ID));
        $select->appendColumn(new Column(Penjualan::C_TOTAL_PRICE, Penjualan::TNAME, Pemesanan::CJA_SELLING_TOTAL_PRICE));
        $select->appendColumn(new Column(Leasing::C_ID, Leasing::TNAME, Pemesanan::CJA_LEASING_ID));
        if($this->isJoinBy(DataObjectConstant::TABLE_SELLING)) {
            $select->appendColumn(new Column(Penjualan::C_TOTAL_PRICE, Penjualan::TNAME, Pemesanan::CJA_SELLING_TOTAL_PRICE));
        }
        if($this->isJoinBy(DataObjectConstant::TABLE_LEASING)) {
            $select->appendColumn(new Column(Leasing::C_ID, Leasing::TNAME, Pemesanan::CJA_LEASING_ID));
        }
        if($this->isJoinBy(DataObjectConstant::TABLE_SELLING)) {
            if($this->isLoopBy(Pemesanan::LOOP_BY_FINISH))
                $select->appendJoin(new JoinTable(Penjualan::TNAME));
            else $select->appendJoin(new JoinTable(Penjualan::TNAME, JoinTable::LEFT_JOIN));
            $select->appendJoinOnEx(Pemesanan::TNAME, Pemesanan::C_ID, "=", Penjualan::TNAME, Penjualan::C_BOOK_FK);
            if($this->isLoopBy(Pemesanan::LOOP_BY_UNFINISHED))
                $select->append_where(new Column(Penjualan::C_BOOK_FK, Penjualan::TNAME)." IS NULL");
        }
        if($this->isJoinBy(DataObjectConstant::TABLE_LEASING)) {
            $select->appendJoin(new JoinTable(Leasing::TNAME, JoinTable::LEFT_JOIN));
            $select->appendJoinOnEx(Leasing::TNAME, Leasing::C_ID, "=", Penjualan::TNAME, Penjualan::C_LEASING_FK);
        }
        return $select;
    }
}