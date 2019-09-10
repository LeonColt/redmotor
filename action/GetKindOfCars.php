<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 26/04/2017
 * Time: 12:46
 */
require_once standart_path.'core/MySQLAccess.php';
require_once standart_path.'dataobject/JenisMobil.php';
class GetKindOfCars
{
    const BRAND = "brand";
    const TYPE = "type";

    const RETURN_ID = "id";
    const RETURN_KIND = "jenis";

    private $brand, $type;
    public function __construct($brand, $type)
    {
        $this->brand = $brand;
        $this->type = $type;
    }
    public function execute() : array {
        if(!is_numeric($this->brand) && !is_numeric($this->type)) return array();
        if((int)$this->brand === 0 && (int)$this->type === 0) return array();
        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);

        $kinds = new JenisMobil();
        $kinds->setRunner($runner);
        if((int)$this->brand !== 0) {
            $kinds->appendLoopBy(JenisMobil::LOOP_BY_BRAND_ID);
            $kinds->appendParameters(JenisMobil::LOOP_BY_BRAND_ID, $this->brand);
        }
        if((int)$this->type !== 0) {
            $kinds->appendLoopBy(JenisMobil::LOOP_BY_TYPE_ID);
            $kinds->appendParameters(JenisMobil::LOOP_BY_TYPE_ID, $this->type);
        }
        $data =array();
        foreach($kinds as $kind) {
            $temp = array();
            $temp[GetKindOfCars::RETURN_ID] = $kind->getId();
            $temp[GetKindOfCars::RETURN_KIND] = $kind->getJenis();
            array_push($data, $temp);
        }
        return $data;
    }
}