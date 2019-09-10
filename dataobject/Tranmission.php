<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 04/07/2017
 * Time: 12:56
 */
class Transmission extends CustomDataObject
{
    const TNAME = "t_transmission";
    const C_ID = "id";
    const C_TRANSMISSION = "transmission";
    private $id, $transmission;
    public function __construct($id = null){parent::__construct();$this->id = $id;}
    public function getId() : int{return $this->id;}
    public function getTransmission() : string{return $this->transmission;}
    public function setTransmission(string $transmission){$this->transmission = $transmission;}
    public function current() : Transmission
    {
        $trans = new Transmission($this->getArr()[Transmission::C_ID]);
        $trans->setTransmission($this->getArr()[Transmission::C_TRANSMISSION]);
        return $trans;
    }

    protected function onLoad(): Select
    {
        // TODO: Implement onLoad() method.
    }

    protected function onPostLoad($data)
    {
        // TODO: Implement onPostLoad() method.
    }

    protected function onAdd(): Insert
    {
        // TODO: Implement onAdd() method.
    }

    protected function onUpdate(Parameter ...$parameters): Update
    {
        // TODO: Implement onUpdate() method.
    }

    protected function onDelete(): Delete
    {
        // TODO: Implement onDelete() method.
    }

    protected function onRewind(): Select
    {
        $select = new Select(Transmission::TNAME);
        $select->appendColumn(new Column(Transmission::C_ID));
        $select->appendColumn(new Column(Transmission::C_TRANSMISSION));
        return $select;
    }
}