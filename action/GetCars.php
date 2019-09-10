<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 24/04/2017
 * Time: 17:09
 */
require_once standart_path.'core/MySQLAccess.php';
require_once standart_path.'dataobject/Mobil.php';
require_once standart_path.'core/universal_methode.php';
class GetCars
{
    const BRAND = "merek";
    const TYPE = "tipe";
    const KIND = "jenis";
    const TRANSMISSION = "transmission";
    const PRICE_MIN = "harga_minimum";
    const PRICE_MAX = "harga_maksimum";
    const ODOMETER_MIN = "odometer_min";
    const ODOMETER_MAX = "odometer_max";

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

    private $brand_id, $type_id, $kind_id, $transmission_id, $price_min, $price_max, $odometer_min, $odometer_max;
    public function __construct($brand_id,
                                $type_id,
                                $kind_id,
                                $transmission_id,
                                $price_min, $price_max,
                                $odometer_min, $odometer_max) {
        $this->brand_id = (int)$brand_id;
        $this->type_id = (int)$type_id;
        $this->kind_id = (int)$kind_id;
        $this->transmission_id = (int)$transmission_id;
        $this->price_min = $price_min;
        $this->price_max = $price_max;
        $this->odometer_min = $odometer_min;
        $this->odometer_max = $odometer_max;
    }
    public function execute() : array {
        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);

        $mobils = new Mobil();
        $mobils->appendLinkJoin(DataObjectConstant::TABLE_CAR_KIND);
        $mobils->appendLinkJoin(DataObjectConstant::TABLE_CAR_TYPE);
        $mobils->appendLinkJoin(DataObjectConstant::TABLE_CAR_BRAND);
        $mobils->appendLinkJoin(DataObjectConstant::TABLE_CAR_TRANSMISSION);
        $user = GetCurrentUser();
        if($user !== null) {
            if(!$user->isAdmin() && !$user->isSuperAdmin())
                $mobils->appendLinkJoin(DataObjectConstant::TABLE_CAR_VALUE);
        } else $mobils->appendLinkJoin(DataObjectConstant::TABLE_CAR_VALUE);
        $mobils->setRunner($runner);
        if($this->kind_id !== 0) {
            $jenis = new JenisMobil($this->kind_id);
            $mobils->setLoopByKindId();
        } else $jenis = new JenisMobil();
        if($this->brand_id !== 0) {
            $merek = new MerekMobil($this->brand_id);
            $mobils->setLoopByBrandId();
            $jenis->setMerek($merek);
        }
        if( $this->type_id !== 0) {
            $type = new TipeMobil($this->type_id);
            $mobils->setLoopByTypeId();
            $jenis->setTipe($type);
        }
        if($this->transmission_id !== 0) {
            $trans = new Transmission($this->transmission_id);
            $mobils->appendLoopBy(Mobil::LOOP_BY_TRANSMISSION);
            $mobils->setTransmission($trans);
        }
        if($this->brand_id !== 0 || $this->type_id !== 0 || $this->kind_id !== 0)
            $mobils->setJenis($jenis);
        if(is_numeric($this->price_min) || is_numeric($this->price_max)) {
            if(is_numeric($this->price_min) && is_numeric($this->price_max)) {
                if( (int)$this->price_min === (int)$this->price_max) {
                    $mobils->appendLoopBy(Mobil::LOOP_BY_PRICE_EQUAL);
                    $mobils->setPrice($this->price_min);
                }
                else {
                    $mobils->appendLoopBy(Mobil::LOOP_BY_PRICE_RANGE);
                    $mobils->appendParameters(Mobil::LOOP_BY_PRICE_MINIMUM, $this->price_min);
                    $mobils->appendParameters(Mobil::LOOP_BY_PRICE_MAXIMUM, $this->price_max);
                }
            }
            else if(is_numeric($this->price_min) ) {
                $mobils->appendLoopBy(Mobil::LOOP_BY_PRICE_MINIMUM);
                $mobils->appendParameters(Mobil::LOOP_BY_PRICE_MINIMUM, $this->price_min);
            }
            else {
                $mobils->appendLoopBy(Mobil::LOOP_BY_PRICE_MAXIMUM);
                $mobils->appendParameters(Mobil::LOOP_BY_PRICE_MAXIMUM, $this->price_max);
            }
        }
        if(is_numeric($this->odometer_min) || is_numeric($this->odometer_max) ) {
            if( is_numeric($this->odometer_min) && is_numeric($this->odometer_max)) {
                if((int)$this->odometer_min === (int)$this->price_max) {
                    $mobils->appendLoopBy(Mobil::LOOP_BY_ODOMETER_EQUAL);
                    $mobils->setOdometer($this->odometer_min);
                }
                else {
                    $mobils->appendLoopBy(Mobil::LOOP_BY_ODOMETER_RANGE);
                    $mobils->appendParameters(Mobil::LOOP_BY_ODOMETER_MINIMUM, $this->odometer_min);
                    $mobils->appendParameters(Mobil::LOOP_BY_ODOMETER_MAXIMUM, $this->odometer_min);
                }
            }
            else if( is_numeric($this->price_min)) {
                $mobils->appendLoopBy(Mobil::LOOP_BY_ODOMETER_MINIMUM);
                $mobils->appendParameters(Mobil::LOOP_BY_ODOMETER_MINIMUM, $this->odometer_min);
            }
            else {
                $mobils->appendLoopBy(Mobil::LOOP_BY_ODOMETER_MAXIMUM);
                $mobils->appendParameters(Mobil::LOOP_BY_ODOMETER_MAXIMUM, $this->odometer_min);
            }
        }
        $mobils->appendLoopBy(Mobil::LOOP_BY_NOT_SOLD);
        $data = array();
        foreach($mobils as $mobil) {
            $temp = array();
            $temp[GetCars::RETURN_ID] = $mobil->getId();
            $temp[GetCars::RETURN_NAME] = $mobil->getName();
            $temp[GetCars::RETURN_BRAND] = $mobil->getJenis()->getMerek()->getMerek();
            $temp[GetCars::RETURN_TYPE] = $mobil->getJenis()->getTipe()->getType();
            $temp[GetCars::RETURN_KIND] = $mobil->getJenis()->getJenis();
            $temp[GetCars::RETURN_TRANSMISSION] = $mobil->getTransmission()->getTransmission();
            $temp[GetCars::RETURN_YEAR] = $mobil->getYear();
            $temp[GetCars::RETURN_ODOMETER] = $mobil->getOdometer();
            $temp[GetCars::RETURN_PRICE] = $mobil->getPrice();
            $temp[GetCars::RETURN_IMAGE] = $mobil->getPic()->getImage();
            $temp[GetCars::RETURN_IMAGE_TYPE] = $mobil->getPic()->getType();
            array_push($data, $temp);
        }
        return $data;
    }
}