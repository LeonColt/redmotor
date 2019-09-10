<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 08/04/2017
 * Time: 12:47
 */
require_once standart_path.'core/DataBaseObject.php';
require_once standart_path.'dataobject/Mobil.php';
require_once standart_path.'dataobject/User.php';
class Pembelian extends CustomDataObject
{
    const TNAME = "pembelian";

    const C_ID = "id";
    const C_DATE = "tanggal";
    const C_CAR_FK = "mobil";
    const C_CUSTOMER_FK = "customer";
    const C_PRICE = "harga";

    const CJA_CAR_ID = "car_id";
    const CJA_CAR_NAME = "car_name";
    const CJA_CAR_KIND_ID = "car_kind";
    const CJA_CAR_PRICE = "car_price";

    const CJA_USER_ID = "user_id";
    const CJA_USER_USERNAME = "user_username";

    const LOOP_BY_FINISH = 1;
    const LOOP_BY_UNFINISHED = 2;


    private $id, $date, $car, $customer, $price;

    /**
     * Pembelian constructor.
     * @param int $id
     */
    public function __construct($id = null){parent::__construct();$this->id = $id;}
    public function getId() : int{return (int)$this->id;}
    public function getDate() : ?string{return $this->date;}
    public function setDate(string $date){$this->date = $date;}
    public function getCar() : Mobil{return $this->car;}
    public function setCar(Mobil $car){$this->car = $car;}
    public function getCustomer() : User{return $this->customer;}
    public function setCustomer(User $customer){$this->customer = $customer;}
    public function getPrice() : int{return $this->price;}
    public function setPrice(int $price){$this->price = $price;}
    public function current() : Pembelian
    {
        $pembelian = new Pembelian($this->getArr()[Pembelian::C_ID]);
        if($this->getArr()[Pembelian::C_DATE] !== null) $pembelian->setDate($this->getArr()[Pembelian::C_DATE]);
        $car = new Mobil($this->getArr()[Pembelian::CJA_CAR_ID]);
        $car->setName($this->getArr()[Pembelian::CJA_CAR_NAME]);
        $car->setPrice((int)$this->getArr()[Pembelian::CJA_CAR_PRICE]);
        $pembelian->setCar($car);
        $user = new User($this->getArr()[Pembelian::CJA_USER_ID]);
        $user->setUsername($this->getArr()[Pembelian::CJA_USER_USERNAME]);
        $pembelian->setCustomer($user);
        $pembelian->setPrice($this->getArr()[Pembelian::C_PRICE]);
        return $pembelian;
    }

    protected function onLoad(): Select
    {
        $select = new Select(Pembelian::TNAME);
        $select->appendColumn(new Column(Pembelian::C_ID));
        $select->append_where(Pembelian::C_ID." = ?");
        $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->id));
        return $select;
    }

    protected function onPostLoad($data)
    {
        $this->id = $data[Pembelian::C_ID]; // kind of useless
    }

    protected function onAdd(): Insert
    {
        $insert = new Insert(Pembelian::TNAME);
        $insert->appendColumnValue(new Column(Pembelian::C_ID), new Parameter($insert->getParameterVariableIntegerOrder(), $this->id));
        $insert->appendColumnValue(new Column(Pembelian::C_CAR_FK), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getCar()->getId()));
        $insert->appendColumnValue(new Column(Pembelian::C_CUSTOMER_FK), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getCustomer()->getId()));
        $insert->appendColumnValue(new Column(Pembelian::C_PRICE), new Parameter($insert->getParameterVariableIntegerOrder(), $this->price));
        return $insert;
    }

    protected function onUpdate(): Update
    {
        // TODO: Implement onUpdate() method.
        $update = new Update(Pembelian::TNAME);
        $update->appendSet(new Column(Pembelian::C_PRICE), "?");
        $update->appendParameter(new Parameter($update->getParameterVariableIntegerOrder(), $this->getPrice()));
        $update->appendSet(new Column(Pembelian::C_DATE), '?');
        $update->appendParameter(new Parameter($update->getParameterVariableIntegerOrder(), $this->getDate()));
        $update->append_where(Pembelian::C_ID."=?");
        $update->appendParameter(new Parameter($update->getParameterVariableIntegerOrder(), $this->getId()));
        return $update;
    }

    protected function onDelete(): Delete
    {
        // TODO: Implement onDelete() method.
    }

    protected function onRewind(): Select
    {
        $select = new Select(Pembelian::TNAME);
        $select->appendColumn(new Column(Pembelian::C_ID, Pembelian::TNAME));
        $select->appendColumn(new Column(Pembelian::C_DATE));
        $select->appendColumn(new Column(Mobil::C_ID, Mobil::TNAME, Pembelian::CJA_CAR_ID));
        $select->appendColumn(new Column(Mobil::C_NAME, Mobil::TNAME, Pembelian::CJA_CAR_NAME));
        $select->appendColumn(new Column(Mobil::C_PRICE, Mobil::TNAME, Pembelian::CJA_CAR_PRICE));
        $select->appendColumn(new Column(User::C_ID, User::TNAME, Pembelian::CJA_USER_ID));
        $select->appendColumn(new Column(User::C_USERNAME, User::TNAME, Pembelian::CJA_USER_USERNAME));
        $select->appendColumn(new Column(Pembelian::C_PRICE, Pembelian::TNAME));
        $select->appendJoin(new JoinTable(Mobil::TNAME));
        $select->appendJoinOnEx(Pembelian::TNAME, Pembelian::C_CAR_FK, "=", Mobil::TNAME, Mobil::C_ID);
        $select->appendJoin(new JoinTable(User::TNAME));
        $select->appendJoinOnEx(Pembelian::TNAME, Pembelian::C_CUSTOMER_FK, "=", User::TNAME, User::C_ID);
        if($this->isLoopBy(Pembelian::LOOP_BY_FINISH))
            $select->append_where(Pembelian::C_DATE." IS NOT NULL");
        else if($this->isLoopBy(Pembelian::LOOP_BY_UNFINISHED))
            $select->append_where(Pembelian::C_DATE." IS NULL");
        return $select;
    }
}