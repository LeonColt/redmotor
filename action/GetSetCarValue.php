<?php
/**
 * Created by PhpStorm.
 * User: LC
 * Date: 10/07/2017
 * Time: 18:56
 */
require_once standart_path.'core/MySQLAccess.php';
require_once standart_path.'dataobject/CarValue.php';
class GetSetCarValue
{
    const OPERATION_SET = 1;
    const OPERATION_GET = 2;

    const INPUT_OPERATION = "operation";
    const SG_ID = "id";
    const SG_DOCUMENT = "document";
    const SG_ENGINE = "engine";
    const SG_ODOMETER = "odometer";
    const SG_INTERIOR = "interface";
    const SG_EXTERIOR = "exterior";
    const SG_YEAR = "year";
    const SG_PRICE = "price";

    private $operation, $id, $document, $engine, $odometer, $interior, $exterior, $year, $price;
    public function __construct(int $operation,
                                int $id,
                                $document,
                                $engine,
                                $odometer,
                                $interior,
                                $exterior,
                                $year,
                                $price)
    {
        $this->operation = $operation;
        $this->id = $id;
        $this->document = $document;
        $this->engine = $engine;
        $this->odometer = $odometer;
        $this->interior = $interior;
        $this->exterior = $exterior;
        $this->year = $year;
        $this->price = $price;
    }

    public function execute() {
        switch ($this->operation) {
            case GetSetCarValue::OPERATION_SET : return $this->setCarValue();

            case GetSetCarValue::OPERATION_GET : return $this->getCarValue();
        }
    }
    private function setCarValue() {
        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
        $car_value = new CarValue($this->id);
        $car_value->setDocument($this->document);
        $car_value->setEngine($this->engine);
        $car_value->setOdometer($this->odometer);
        $car_value->setInterior($this->interior);
        $car_value->setExterior($this->exterior);
        $car_value->setPrice($this->price);
        $car_value->setYear($this->year);
        $car_value->setRunner($runner);
        $car_value->add();
        return null;
    }

    private function getCarValue() {
        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
        $car_value = new CarValue($this->id);
        $car_value->setRunner($runner);
        if(!$car_value->load()) throw new Exception("Mobil Tidak Ditemukan atau Belum Diinput");
        $res = array();
        $res[GetSetCarValue::SG_DOCUMENT] = $car_value->getDocument();
        $res[GetSetCarValue::SG_ENGINE] = $car_value->getEngine();
        $res[GetSetCarValue::SG_EXTERIOR] = $car_value->getExterior();
        $res[GetSetCarValue::SG_INTERIOR] = $car_value->getInterior();
        $res[GetSetCarValue::SG_ODOMETER] = $car_value->getOdometer();
        $res[GetSetCarValue::SG_PRICE] = $car_value->getPrice();
        $res[GetSetCarValue::SG_YEAR] = $car_value->getYear();
        return $res;
    }
}