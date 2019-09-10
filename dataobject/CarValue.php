<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 19/04/2017
 * Time: 12:31
 */
require_once standart_path.'dataobject/CustomDataObject.php';
class CarValue extends CustomDataObject
{
    const TNAME = "nilai_mobil";
    const C_ID = "id";
    const C_DOCUMENT = "nilai_dokumen";
    const C_ENGINE = "nilai_mesin";
    const C_ODOMETER = "nilai_odometer";
    const C_INTERIOR = "nilai_interior";
    const C_EXTERIOR = "nilai_exterior";
    const C_YEAR = "nilai_tahun";
    const C_PRICE = "nilai_harga";
    private $id, $document, $engine, $odometer, $interior, $exterior, $year, $price;

    /**
     * CarValue constructor.
     * @param int $id
     */
    public function __construct($id = null)
    {
        parent::__construct();
        $this->id = $id;
    }

    public function getId() : int{return $this->id;}
    public function getDocument() : int{return $this->document;}
    public function setDocument(int $document){$this->document = $document;}
    public function getEngine() : int{return $this->engine;}
    public function setEngine(int $engine){$this->engine = $engine;}
    public function getOdometer() : int{return $this->odometer;}
    public function setOdometer(int $odometer){$this->odometer = $odometer;}
    public function getInterior() : int{return $this->interior;}
    public function setInterior(int $interior){$this->interior = $interior;}
    public function getExterior() : int{return $this->exterior;}
    public function setExterior(int $exterior){$this->exterior = $exterior;}
    public function getYear() : int{return $this->year;}
    public function setYear(int $year){$this->year = $year;}
    public function getPrice() : int{return $this->price;}
    public function setPrice(int $price){$this->price = $price;}
    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        // TODO: Implement current() method.
        $cv = new CarValue($this->getArr()[CarValue::C_ID]);
        $cv->setDocument($this->getArr()[CarValue::C_DOCUMENT]);
        $cv->setEngine($this->getArr()[CarValue::C_ENGINE]);
        $cv->setOdometer($this->getArr()[CarValue::C_ODOMETER]);
        $cv->setInterior($this->getArr()[CarValue::C_INTERIOR]);
        $cv->setExterior($this->getArr()[CarValue::C_EXTERIOR]);
        $cv->setYear($this->getArr()[CarValue::C_YEAR]);
        $cv->setPrice($this->getArr()[CarValue::C_PRICE]);
        return $cv;
    }

    protected function onLoad() : Select
    {
        // TODO: Implement onLoad() method.
        $select = new Select(CarValue::TNAME);
        $select->appendColumn(new Column(CarValue::C_ID));
        $select->appendColumn(new Column(CarValue::C_DOCUMENT));
        $select->appendColumn(new Column(CarValue::C_ENGINE));
        $select->appendColumn(new Column(CarValue::C_ODOMETER));
        $select->appendColumn(new Column(CarValue::C_INTERIOR));
        $select->appendColumn(new Column(CarValue::C_EXTERIOR));
        $select->appendColumn(new Column(CarValue::C_EXTERIOR));
        $select->appendColumn(new Column(CarValue::C_YEAR));
        $select->appendColumn(new Column(CarValue::C_PRICE));
        $select->append_where(CarValue::C_ID.' = ?');
        $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->id));
        return $select;
    }

    protected function onPostLoad($data)
    {
        // TODO: Implement onPostLoad() method.
        $this->document = $data[CarValue::C_DOCUMENT];
        $this->engine = $data[CarValue::C_ENGINE];
        $this->odometer = $data[CarValue::C_ODOMETER];
        $this->interior = $data[CarValue::C_INTERIOR];
        $this->exterior = $data[CarValue::C_EXTERIOR];
        $this->year = $data[CarValue::C_YEAR];
        $this->price = $data[CarValue::C_PRICE];
    }

    protected function onAdd() : Insert
    {
        // TODO: Implement onAdd() method.
        $insert = new Insert(CarValue::TNAME, true);
        $insert->appendColumnValue( new Column(CarValue::C_ID), new Parameter($insert->getParameterVariableIntegerOrder(), $this->id));
        $insert->appendColumnValue( new Column(CarValue::C_DOCUMENT), new Parameter($insert->getParameterVariableIntegerOrder(), $this->document));
        $insert->appendColumnValue( new Column(CarValue::C_ENGINE), new Parameter($insert->getParameterVariableIntegerOrder(), $this->engine));
        $insert->appendColumnValue( new Column(CarValue::C_ODOMETER), new Parameter($insert->getParameterVariableIntegerOrder(), $this->odometer));
        $insert->appendColumnValue( new Column(CarValue::C_INTERIOR), new Parameter($insert->getParameterVariableIntegerOrder(), $this->interior));
        $insert->appendColumnValue( new Column(CarValue::C_EXTERIOR), new Parameter($insert->getParameterVariableIntegerOrder(), $this->exterior));
        $insert->appendColumnValue( new Column(CarValue::C_YEAR), new Parameter($insert->getParameterVariableIntegerOrder(), $this->year));
        $insert->appendColumnValue( new Column(CarValue::C_PRICE), new Parameter($insert->getParameterVariableIntegerOrder(), $this->price));
        $insert->appendPrimaryKey(new Column(CarValue::C_ID));
        return $insert;
    }

    protected function onUpdate(Parameter ...$parameters) : Update
    {
        // TODO: Implement onUpdate() method.
    }

    public function onDelete() : Delete
    {
        // TODO: Implement onDelete() method.
        $delete = new Delete(CarValue::TNAME);
        $delete->append_where('id = ?');
        $delete->appendParameter(new Parameter($delete->getParameterVariableIntegerOrder(), $this->id));
        return $delete;
    }

    protected function onRewind(): Select
    {
        // TODO: Implement onRewind() method.
        $select = new Select(CarValue::TNAME);
        $select->appendColumn(new Column(CarValue::C_ID));
        $select->appendColumn(new Column(CarValue::C_DOCUMENT));
        $select->appendColumn(new Column(CarValue::C_ENGINE));
        $select->appendColumn(new Column(CarValue::C_ODOMETER));
        $select->appendColumn(new Column(CarValue::C_INTERIOR));
        $select->appendColumn(new Column(CarValue::C_EXTERIOR));
        $select->appendColumn(new Column(CarValue::C_YEAR));
        $select->appendColumn(new Column(CarValue::C_PRICE));
        return $select;
    }
}