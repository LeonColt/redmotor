<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 16/07/2017
 * Time: 13:36
 */
require_once standart_path.'dataobject/CustomDataObject.php';
class Leasing extends CustomDataObject
{
    const TNAME = "leasing";
    const C_ID = "id";
    const C_NAME = "nama";
    const C_ADDRESS = "alamat";
    const C_TELEPHONE = "telepon";

    private $id, $name, $address, $telephone;

    public function __construct($id = null){parent::__construct();$this->id = $id;}
    public function getId() : int{return $this->id;}
    public function getName() : string{return $this->name;}
    public function setName(string $name){$this->name = $name;}
    public function getAddress() : string{return $this->address;}
    public function setAddress(string $address){$this->address = $address;}
    public function getTelephone() : string{return $this->telephone;}
    public function setTelephone(string $telephone){$this->telephone = $telephone;}
    public function current() : Leasing
    {
        $leasing = new Leasing($this->getArr()[Leasing::C_ID]);
        $leasing->setName($this->getArr()[Leasing::C_NAME]);
        $leasing->setAddress($this->getArr()[Leasing::C_ADDRESS]);
        $leasing->setTelephone($this->getArr()[Leasing::C_TELEPHONE]);
        return $leasing;
    }

    protected function onLoad(): Select
    {
        $select = new Select(Leasing::TNAME);
        $select->appendColumn(new Column(Leasing::C_ID));
        $select->appendColumn(new Column(Leasing::C_NAME));
        $select->appendColumn(new Column(Leasing::C_ADDRESS));
        $select->appendColumn(new Column(Leasing::C_TELEPHONE));
        $select->append_where(Leasing::C_ID."=?");
        $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->id));
        return $select;
    }

    protected function onPostLoad($data)
    {
        $this->id = $data[Leasing::C_ID];
        $this->setName($data[Leasing::C_NAME]);
        $this->setAddress($data[Leasing::C_ADDRESS]);
        $this->setTelephone($data[Leasing::C_TELEPHONE]);
    }

    protected function onAdd(): Insert
    {
        $insert = new Insert(Leasing::TNAME, true);
        $insert->appendPrimaryKey(new Column(Leasing::C_ID));
        $insert->appendColumnValue(new Column(Leasing::C_ID), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getId()));
        $insert->appendColumnValue(new Column(Leasing::C_NAME), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getName()));
        $insert->appendColumnValue(new Column(Leasing::C_ADDRESS), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getAddress()));
        $insert->appendColumnValue(new Column(Leasing::C_TELEPHONE), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getTelephone()));
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
        $select = new Select(Leasing::TNAME);
        $select->appendColumn(new Column(Leasing::C_ID));
        $select->appendColumn(new Column(Leasing::C_NAME));
        $select->appendColumn(new Column(Leasing::C_ADDRESS));
        $select->appendColumn(new Column(Leasing::C_TELEPHONE));
        return $select;
    }
}