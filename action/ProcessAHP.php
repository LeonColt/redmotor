<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 07/07/2017
 * Time: 14:22
 */
require_once standart_path.'process/AHP.php';
require_once standart_path.'dataobject/Mobil.php';
class ProcessAHP
{
    const INPUT_CAR_TYPE = "tipe";
    const INPUT_MIN_PRICE = "price_min";
    const INPUT_MAX_PRICE = "price_max";
    const INPUT_CRITERIA = "criteria";
    const INPUT_DATA = "data";

    const RETURN_ID = "id";
    const RETURN_SCORE = "score";

    private $car_type, $price_min, $price_max, $criteria, $data;
    public function __construct(int $cary_type, $price_min, $price_max, array $criteria, array $data)
    {
        $this->car_type = $cary_type;
        $this->price_min = $price_min;
        $this->price_max = $price_max;
        $this->criteria = $criteria;
        $this->data = $data;
    }
    public function execute() {
        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
        $cars = new Mobil();
        $cars->appendLinkJoin(DataObjectConstant::TABLE_CAR_KIND);
        $cars->appendLinkJoin(DataObjectConstant::TABLE_CAR_TYPE);
        $cars->appendLinkJoin(DataObjectConstant::TABLE_CAR_BRAND);
        $cars->appendLinkJoin(DataObjectConstant::TABLE_CAR_TRANSMISSION);
        $cars->appendLinkJoin(DataObjectConstant::TABLE_CAR_VALUE);
        $cars->setRunner($runner);
        if( $this->car_type !== 0) {
            $jenis = new JenisMobil();
            $type = new TipeMobil($this->car_type);
            $cars->setLoopByTypeId();
            $jenis->setTipe($type);
            $cars->setJenis($jenis);
        }
        if(is_numeric($this->price_min) || is_numeric($this->price_max)) {
            if(is_numeric($this->price_min) && is_numeric($this->price_max)) {
                if( (int)$this->price_min === (int)$this->price_max) {
                    $cars->appendLoopBy(Mobil::LOOP_BY_PRICE_EQUAL);
                    $cars->setPrice($this->price_min);
                }
                else {
                    $cars->appendLoopBy(Mobil::LOOP_BY_PRICE_RANGE);
                    $cars->appendParameters(Mobil::LOOP_BY_PRICE_MINIMUM, $this->price_min);
                    $cars->appendParameters(Mobil::LOOP_BY_PRICE_MAXIMUM, $this->price_max);
                }
            }
            else if(is_numeric($this->price_min) ) {
                $cars->appendLoopBy(Mobil::LOOP_BY_PRICE_MINIMUM);
                $cars->appendParameters(Mobil::LOOP_BY_PRICE_MINIMUM, $this->price_min);
            }
            else {
                $cars->appendLoopBy(Mobil::LOOP_BY_PRICE_MAXIMUM);
                $cars->appendParameters(Mobil::LOOP_BY_PRICE_MAXIMUM, $this->price_max);
            }
        }
        $cars_left = array();
        $cars_right = array();
        foreach ($cars as $car) {
            array_push($cars_left, $car);
            array_push($cars_right, $car);
        }
        $alternatives = array();
        foreach ($cars_left as $item) {
            /** @var  $item Mobil */
            array_push($alternatives, $item->getId());
        }
        $ahp = new AHP($this->criteria, $alternatives);
        foreach ($this->data as $key => $datum) {
            $temp = explode("_", $key);
            $var_left = $temp[0];
            $var_right = $temp[1];
            $temp = explode("-", $datum);
            $val_left = $temp[0];
            $val_right = $temp[1];

            $ahp->getPairComparison(AHP::GLOBAL)->setWeightValue($var_left, $var_right, number_format((double)($val_left/$val_right), 14));
            $ahp->getPairComparison(AHP::GLOBAL)->setWeightValue($var_right, $var_left, number_format((double)($val_right/$val_left), 14));
        }
        foreach ($this->criteria as $criterion) {
            //manual mapping actually not recommended
            foreach ($cars_left as $index_left => $car_left) {
                /** @var  $car_left Mobil
                 * @var  $car_right Mobil */
                foreach ($cars_right as $index_right => $car_right) {
                    $value_left = 0;
                    $value_right = 0;
                    //echo "?".$car_left->getName()." === ".$car_right->getName()."\n";
                    if(strcmp($car_left->getName(), $car_right->getName()) === 0) continue;
                    switch ($criterion) {
                        case GlobalConst\AHPConst::CRITERIAS[0] : {
                            $value_left = (double)($car_left->getCarValue()->getDocument() / $car_right->getCarValue()->getDocument());
                            $value_right = (double)($car_right->getCarValue()->getDocument() / $car_left->getCarValue()->getDocument());
                        } break;

                        case GlobalConst\AHPConst::CRITERIAS[1] : {
                            $value_left = (double)($car_left->getCarValue()->getEngine() / $car_right->getCarValue()->getEngine());
                            $value_right = (double)($car_right->getCarValue()->getEngine() / $car_left->getCarValue()->getEngine());
                        } break;

                        case GlobalConst\AHPConst::CRITERIAS[2] : {
                            $value_left = (double)($car_left->getCarValue()->getOdometer() / $car_right->getCarValue()->getOdometer());
                            $value_right = (double)($car_right->getCarValue()->getOdometer() / $car_left->getCarValue()->getOdometer());
                        } break;

                        case GlobalConst\AHPConst::CRITERIAS[3] : {
                            $value_left = (double)($car_left->getCarValue()->getInterior() / $car_right->getCarValue()->getInterior());
                            $value_right = (double)($car_right->getCarValue()->getInterior() / $car_left->getCarValue()->getInterior());
                        } break;

                        case GlobalConst\AHPConst::CRITERIAS[4] : {
                            $value_left = (double)($car_left->getCarValue()->getExterior() / $car_right->getCarValue()->getExterior());
                            $value_right = (double)($car_right->getCarValue()->getExterior() / $car_left->getCarValue()->getExterior());
                        } break;

                        case GlobalConst\AHPConst::CRITERIAS[5] : {
                            $value_left = (double)($car_left->getCarValue()->getYear() / $car_right->getCarValue()->getYear());
                            $value_right = (double)($car_right->getCarValue()->getYear() / $car_left->getCarValue()->getYear());
                        } break;

                        case GlobalConst\AHPConst::CRITERIAS[6] : {
                            $value_left = (double)($car_left->getCarValue()->getPrice() / $car_right->getCarValue()->getPrice());
                            $value_right = (double)($car_right->getCarValue()->getPrice() / $car_left->getCarValue()->getPrice());
                        } break;
                    }
                    $ahp->getPairComparison($criterion)->setWeightValue($car_left->getId(), $car_right->getId(), $value_left);
                    $ahp->getPairComparison($criterion)->setWeightValue($car_right->getId(), $car_left->getId(), $value_right);
                }
            }
        }
        $ocw = $ahp->calculate();
        $res = array();
        $ocw->begin();
        $max_index = $ocw->unmakeKey($ocw->key())[AHP\MapDualKeys::RIGHT];
        while (count($alternatives) > 0) {
            $key_alternatives = -1;
            $alternative = end($alternatives);
            do {
                $index = key($alternatives);
                if((int)$max_index !== (int)$alternative && $ocw->at(AHP::COMPOSITE_WEIGHT, $alternative) >= $ocw->at(AHP::COMPOSITE_WEIGHT, $max_index) ) {
                    $max_index = $alternative;
                    $key_alternatives = $index;
                }
            }while (($alternative = prev($alternatives)) !== false);
            $temp = array();
            $temp[ProcessAHP::RETURN_ID] = $max_index;
            $temp[ProcessAHP::RETURN_SCORE] = $ocw->at(AHP::COMPOSITE_WEIGHT, $max_index);
            array_push($res, $temp);
            $ocw->offsetUnset($ocw->makeKey(AHP::COMPOSITE_WEIGHT, $max_index));
            unset($alternatives[$key_alternatives]);
        }
        return $res;
    }
}