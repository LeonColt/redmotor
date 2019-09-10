<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 15/07/2017
 * Time: 14:45
 */
require_once standart_path.'core/MySQLAccess.php';
require_once standart_path.'dataobject/Mobil.php';
class GetCarsByIds
{
    const INPUT_IDS = "ids";

    const RETURN_ID = "id";
    const RETURN_NAME = "name";
    const RETURN_BRAND = "brand";
    const RETURN_TYPE = "type";
    const RETURN_KIND = "kind";
    const RETURN_TRANSMISSION = "transmission";
    const RETURN_YEAR = "year";
    const RETURN_ODOMETER = "odometer";
    const RETURN_PRICE = "harga";
    const RETURN_IMAGE = "image";
    const RETURN_IMAGE_TYPE = "image_type";

    private $ids;
    public function __construct(array $ids){$this->ids = $ids;}
    public function execute() {

        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);

        $cars = new Mobil($this->ids);
        $cars->appendLinkJoin(DataObjectConstant::TABLE_CAR_KIND);
        $cars->appendLinkJoin(DataObjectConstant::TABLE_CAR_TYPE);
        $cars->appendLinkJoin(DataObjectConstant::TABLE_CAR_BRAND);
        $cars->appendLinkJoin(DataObjectConstant::TABLE_CAR_TRANSMISSION);
        $cars->setRunner($runner);
        $data = array();
        foreach($cars as $mobil) {
            $temp = array();
            $temp[GetCarsByIds::RETURN_ID] = $mobil->getId();
            $temp[GetCarsByIds::RETURN_NAME] = $mobil->getName();
            $temp[GetCarsByIds::RETURN_BRAND] = $mobil->getJenis()->getMerek()->getMerek();
            $temp[GetCarsByIds::RETURN_TYPE] = $mobil->getJenis()->getTipe()->getType();
            $temp[GetCarsByIds::RETURN_KIND] = $mobil->getJenis()->getJenis();
            $temp[GetCarsByIds::RETURN_TRANSMISSION] = $mobil->getTransmission()->getTransmission();
            $temp[GetCarsByIds::RETURN_YEAR] = $mobil->getYear();
            $temp[GetCarsByIds::RETURN_ODOMETER] = $mobil->getOdometer();
            $temp[GetCarsByIds::RETURN_PRICE] = $mobil->getPrice();
            $temp[GetCarsByIds::RETURN_IMAGE] = $mobil->getPic()->getImage();
            $temp[GetCarsByIds::RETURN_IMAGE_TYPE] = $mobil->getPic()->getType();
            array_push($data, $temp);
        }
        return $data;
    }
}