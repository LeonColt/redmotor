<?php
/**
 * Created by PhpStorm.
 * User: LC
 * Date: 14/04/2017
 * Time: 12:17
 */
namespace {
    error_reporting(E_ERROR | E_PARSE);
    require_once 'core/GlobalConst.php';
    require_once standart_path.'core/MySQLAccess.php';
}
namespace AHP {
    class MapDualKeys extends \BasicCollection {
        const LEFT = 0;
        const RIGHT = 1;
        public function __construct(){parent::__construct();}
        public function at($left, $right) {
            if($this->offsetExists($this->makeKey($left, $right))) return $this->get($this->makeKey($left, $right));
        }
        public function push($left, $right, $value) { $this->set($this->makeKey($left, $right), $value);}
        public function current(){return $this->get($this->key());}
        public function makeKey($left, $right) : string{return $left."-".$right;}
        public function unmakeKey(string $key) : array {
            return explode("-", $key);
        }
        public function offsetGet($offset)
        {
            // TODO: Implement offsetGet() method.
        }
        public function offsetSet($offset, $value)
        {
            // TODO: Implement offsetSet() method.
        }
    }
    class PairComparison {
        private $matrix, $eigen, $ci, $cr;
        private $ri;
        private $criteria;
        public function __construct(array $criteria)
        {
            $this->matrix = new MapDualKeys();
            $this->ri = array(0, 0, 0, 0.58, 0.9, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49);
            $this->criteria = $criteria;
            foreach($criteria as $criteria_left) {
                foreach($criteria as $criteria_right){
                    if(strcmp($criteria_left, $criteria_right) === 0)
                        $this->matrix->push($criteria_left, $criteria_right, 1);
                    else $this->matrix->push($criteria_left, $criteria_right, 0);
                }
            }
            foreach($criteria as $criterion){
                $this->matrix->push($criterion, \AHP::PRIORITY_VECTOR, 0);
                $this->matrix->push($criterion, \AHP::TOTAL, 0);
            }
            $this->matrix->push(\AHP::TOTAL, \AHP::PRIORITY_VECTOR, 0);
        }
        public function getMatrix() : MapDualKeys{return $this->matrix;}
        public function setWeightValue($left, $right, $value) {
            if(strcmp($left, $right) === 0) throw new \Exception("set value with same keys are forbidden, default value is 1");
            $this->matrix->push($left, $right, $value);
        }
        public function getEigenValue() {return $this->eigen;}
        public function getConsistencyIndex() {return $this->ci;}
        public function getConsistencyRatio() { return $this->cr;}
        public function calculate() {
            //begin calculate total
            foreach($this->criteria as $criteria_left) {
                $total = 0;
                foreach($this->criteria as $criteria_right)
                    $total += $this->matrix->at($criteria_right, $criteria_left);
                $this->matrix->push(\AHP::TOTAL, $criteria_left, $total);
            }
            //end calculate total

            //begin calculate priority_Vector
            foreach($this->criteria as $criteria_left) {
                $total = 0;
                foreach($this->criteria as $criteria_right)
                    $total += ($this->matrix->at($criteria_left, $criteria_right)/$this->matrix->at(\AHP::TOTAL, $criteria_right));
                $total /= count($this->criteria);
                $this->matrix->push($criteria_left, \AHP::PRIORITY_VECTOR, $total);
            }
            //jumlah prioriy_vector
            $total = 0;
            foreach($this->criteria as $criteria)
                $total += $this->matrix->at($criteria, \AHP::PRIORITY_VECTOR);
            $this->matrix->push(\AHP::TOTAL, \AHP::PRIORITY_VECTOR, $total);
            //end calculate priority_Vector

            //begin calculate eigen
            $this->eigen = 0;
            foreach($this->criteria as $criteria)
                $this->eigen += $this->matrix->at(\AHP::TOTAL, $criteria)*$this->matrix->at($criteria, \AHP::PRIORITY_VECTOR);
            //end calculae eigen

            //calculate CI/Consistency Index
            if((count($this->criteria)-1) === 0) $this->ci = ($this->eigen-count($this->criteria))/((count($this->criteria)-1));
            else $this->ci = 0;

            //calculate CR/Consistency Ratio
            $this->cr = $this->ci/(double)$this->ri[count($this->criteria)];
        }
    }
}
namespace {
    use AHP\PairComparison;
    use AHP\MapDualKeys;

    class AHP
    {
        const GLOBAL = "global";
        const PRIORITY_VECTOR = "priority_vector";
        const TOTAL = "total";
        const WEIGHT = "weight";
        const COMPOSITE_WEIGHT = "composite_weight";
        private $pair_comparison;

        private $criteria, $alternatives;

        public function __construct(array $criteria, array $alternatives){
            $this->criteria = $criteria;
            $this->alternatives = $alternatives;
            $this->pair_comparison = array();
            $this->pair_comparison[AHP::GLOBAL] = new AHP\PairComparison($this->criteria);
            foreach ($criteria as $criterion) $this->pair_comparison[$criterion] = new AHP\PairComparison($alternatives);
        }
        public function getPairComparison(string $index) : AHP\PairComparison {return $this->pair_comparison[$index];}
        public function getPairComparisonMatrix(string $index) : AHP\MapDualKeys{return $this->pair_comparison[$index]->getMatrix();}
        /*
         * index 0 for criteria,
         * index 1 for score
         */
        public function calculate() : AHP\MapDualKeys {
            foreach ($this->pair_comparison as $item) {
                /** @var $item PairComparison */
                $item->calculate();
            }
            //overall composite weight
            $ocw = new MapDualKeys();
            foreach ($this->criteria as $criterion)
                $ocw->push($criterion, AHP::WEIGHT, $this->getPairComparison(AHP::GLOBAL)->getMatrix()->at($criterion, AHP::PRIORITY_VECTOR));
            foreach ($this->criteria as $criterion) {
                foreach ($this->alternatives as $alternative)
                    $ocw->push($criterion, $alternative, $this->getPairComparison($criterion)->getMatrix()->at($alternative, AHP::PRIORITY_VECTOR));
            }
            //calculate score
            foreach ($this->alternatives as $alternative) {
                $val = 0;
                foreach ($this->criteria as $criterion) {
                    $val += ($ocw->at($criterion, AHP::WEIGHT)* $ocw->at($criterion, $alternative));
                }
                $ocw->push(AHP::COMPOSITE_WEIGHT, $alternative, $val);
            }
            return $ocw;
        }
    }
}