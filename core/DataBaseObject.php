<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 08/04/2017
 * Time: 21:41
 */
require_once standart_path.'core/MySQLAccess.php';
abstract class DataBaseObject implements Iterator, Countable
{
    const LINK_TRANSACTION_ALL = 0;
    const LINK_TRANSACTION_LOAD = 1;
    const LINK_TRANSACTION_LOOP = 2;
    const LINK_TRANSACTION_ADD = 3;
    const LINK_TRANSACTION_UPDATE = 4;
    const LINK_TRANSACTION_DELETE = 5;

    private $arr, $arr_pointer;
    private $runner;
    private $link_transaction = array();
    private $link_join = array();

    //begin load
    public function load() : bool {
        if(!$this->isRunnerValid()) throw new Exception("Invalid Connection to Database");
        $query = $this->onLoad();
        $query_array = new QueryArray();
        if(count($this->link_transaction) > 0) $query_array->setExecuteAsTransaction();
        $query_array->append($query);
        foreach ($this->link_transaction as $item) {
            $dbo = $item[0];
            /** @var $dbo DataBaseObject */
            if($item[1] === DataBaseObject::LINK_TRANSACTION_LOAD){
                switch ($item[2]) {
                    case DataBaseObject::LINK_TRANSACTION_LOAD: $query_array->append($dbo->getLoadQuery()); break;
                    case DataBaseObject::LINK_TRANSACTION_LOOP: $query_array->append($dbo->getLoopQuery()); break;
                    case DataBaseObject::LINK_TRANSACTION_ADD: $query_array->append($dbo->getAddQuery()); break;
                    case DataBaseObject::LINK_TRANSACTION_UPDATE: $query_array->append($dbo->getUpdateQuery()); break;
                    case DataBaseObject::LINK_TRANSACTION_DELETE: $query_array->append($dbo->getDeleteQuery()); break;
                }
            }
        }
        $this->getRunner()->appendQueryArray($query_array);
        $this->getRunner()->execute();
        if($query->isSuccessExecuted()) {
            if(($temp = $query->fetch(PDO::FETCH_ASSOC)) === false) return false;
            $this->onPostLoad($temp);
            foreach ($this->link_transaction as $item) {
                $dbo = $item[0];
                /** @var $dbo DataBaseObject */
                if($item[1] === DataBaseObject::LINK_TRANSACTION_LOAD){
                    switch ($item[2]) {
                        case DataBaseObject::LINK_TRANSACTION_LOAD: {
                            if($dbo->getLoadQuery()->isSuccessExecuted()) {
                                if(($temp = $dbo->getLoadQuery()->fetch(PDO::FETCH_ASSOC)) === false) return false;
                                $dbo->onPostLoad($temp);
                            }
                        } break;
                        case DataBaseObject::LINK_TRANSACTION_LOOP: {
                            if(!$dbo->getAddQuery()->isSuccessExecuted()) return false;
                            if(($temp = $dbo->getLoopQuery()->fetchAll(PDO::FETCH_ASSOC)) === false) return false;
                            $dbo->setArr($temp);
                        } break;
                        case DataBaseObject::LINK_TRANSACTION_ADD: {
                            if(!$dbo->getAddQuery()->isSuccessExecuted()) return false;
                        } break;
                        case DataBaseObject::LINK_TRANSACTION_UPDATE: {
                            if(!$dbo->getUpdateQuery()->isSuccessExecuted()) return false;
                        } break;
                        case DataBaseObject::LINK_TRANSACTION_DELETE: {
                            if(!$dbo->getDeleteQuery()->isSuccessExecuted()) return false;
                        } break;
                    }
                }
            }
            return true;
        }
        else return false;
    }
    protected function onPreLoad() {}
    protected abstract function onLoad() : Select;
    protected abstract function onPostLoad($data);
    //end load

    //begin add
    public function add() : bool {
        if(!$this->isRunnerValid()) throw new Exception("Invalid Connection to Database");
        $query = $this->onAdd();
        $query_array = new QueryArray();
        if(count($this->link_transaction) > 0) $query_array->setExecuteAsTransaction();
        $query_array->append($query);
        //echo $query."\n";
        foreach ($this->link_transaction as $item) {
            $dbo = $item[0];
            /** @var $dbo DataBaseObject */
            if($item[1] === DataBaseObject::LINK_TRANSACTION_ADD){
                switch ($item[2]) {
                    case DataBaseObject::LINK_TRANSACTION_LOAD: $query_array->append($dbo->getLoadQuery()); break;
                    case DataBaseObject::LINK_TRANSACTION_LOOP: $query_array->append($dbo->getLoopQuery()); break;
                    case DataBaseObject::LINK_TRANSACTION_ADD: $query_array->append($dbo->getAddQuery()); break;
                    case DataBaseObject::LINK_TRANSACTION_UPDATE: $query_array->append($dbo->getUpdateQuery()); break;
                    case DataBaseObject::LINK_TRANSACTION_DELETE: $query_array->append($dbo->getDeleteQuery()); break;
                }
            }
        }
        $this->getRunner()->appendQueryArray($query_array);
        $this->getRunner()->execute();
        if(!$query->isSuccessExecuted()) return false;
        $this->onPostAdd();
        foreach ($this->link_transaction as $item) {
            $dbo = $item[0];
            /** @var $dbo DataBaseObject */
            if($item[1] === DataBaseObject::LINK_TRANSACTION_ADD){
                switch ($item[2]) {
                    case DataBaseObject::LINK_TRANSACTION_LOAD: {
                        if($dbo->getLoadQuery()->isSuccessExecuted()) {
                            if(($temp = $dbo->getLoadQuery()->fetch(PDO::FETCH_ASSOC)) === false) return false;
                            $dbo->onPostLoad($temp);
                        }
                    } break;
                    case DataBaseObject::LINK_TRANSACTION_LOOP: {
                        if(!$dbo->getAddQuery()->isSuccessExecuted()) return false;
                        if(($temp = $dbo->getLoopQuery()->fetchAll(PDO::FETCH_ASSOC)) === false) return false;
                        $dbo->setArr($temp);
                    } break;
                    case DataBaseObject::LINK_TRANSACTION_ADD: {
                        if(!$dbo->getAddQuery()->isSuccessExecuted()) return false;
                    } break;
                    case DataBaseObject::LINK_TRANSACTION_UPDATE: {
                        if(!$dbo->getUpdateQuery()->isSuccessExecuted()) return false;
                    } break;
                    case DataBaseObject::LINK_TRANSACTION_DELETE: {
                        if(!$dbo->getDeleteQuery()->isSuccessExecuted()) return false;
                    } break;
                }
            }
        }
        return true;
    }
    protected function onPreAdd(){}
    protected abstract function onAdd() : Insert;
    protected function onPostAdd() {}
    //end add

    /**
     * @param int $link_from
     * @return array
     * index 0 for DataBaseObject
     * index 1 for QueryObject
     * index 2 for LINK_TO
     */
    private function initQueryObjectTransactionLink(int $link_from) : array {
        $res = array();
        foreach ($this->link_transaction as $item) {
            $dbo = $item[0];
            /** @var $dbo DataBaseObject */
            if($item[1] === $link_from){
                switch ($item[2]) {
                    case DataBaseObject::LINK_TRANSACTION_LOAD: array_push($res, array($dbo, $dbo->getLoadQuery(), $item[2])); break;
                    case DataBaseObject::LINK_TRANSACTION_LOOP: array_push($res, array($dbo, $dbo->getLoopQuery(), $item[2])); break;
                    case DataBaseObject::LINK_TRANSACTION_ADD: array_push($res, array($dbo, $dbo->getAddQuery(), $item[2])); break;
                    case DataBaseObject::LINK_TRANSACTION_UPDATE: array_push($res, array($dbo, $dbo->getUpdateQuery(), $item[2])); break;
                    case DataBaseObject::LINK_TRANSACTION_DELETE: array_push($res, array($dbo, $dbo->getDeleteQuery(), $item[2])); break;
                }
            }
        }
        return $res;
    }
    public function update() : bool {
        if(!$this->isRunnerValid()) throw new Exception("Invalid Connection to Database");
        $query = $this->onUpdate();
        $query_array = new QueryArray();
        $query_array->append($query);
        $transactions = $this->initQueryObjectTransactionLink(DataBaseObject::LINK_TRANSACTION_UPDATE);
        if(count($transactions) > 0) $query_array->setExecuteAsTransaction();
        foreach ($transactions as $transaction) $query_array->append($transaction[1]);
        $this->getRunner()->appendQueryArray($query_array);
        $this->getRunner()->execute();
        if(!$query->isSuccessExecuted()) return false;
        foreach ($transactions as $transaction) {
            $dbo = &$transaction[0];
            /** @var $dbo DataBaseObject */
            switch ($transaction[2]) {
                case DataBaseObject::LINK_TRANSACTION_LOAD: {
                    $query_link = $transaction[1];
                    /** @var $query_link Select */
                    if($query_link->isSuccessExecuted()) {
                        if(($temp = $query_link->fetch(PDO::FETCH_ASSOC)) === false) return false;
                        $dbo->onPostLoad($temp);
                    }
                } break;
                case DataBaseObject::LINK_TRANSACTION_LOOP: {
                    $query_link = $transaction[1];
                    /** @var $query_link Select */
                    if(!$query_link->isSuccessExecuted()) return false;
                    if(($temp = $query_link->fetchAll(PDO::FETCH_ASSOC)) === false) return false;
                    $dbo->setArr($temp);
                } break;
                case DataBaseObject::LINK_TRANSACTION_ADD: {
                    $query_link = $transaction[1];
                    /** @var $query_link Insert */
                    if(!$query_link->isSuccessExecuted()) return false;
                } break;
                case DataBaseObject::LINK_TRANSACTION_UPDATE: {
                    $query_link = $transaction[1];
                    /** @var $query_link Update */
                    if(!$query_link->isSuccessExecuted()) return false;
                } break;
                case DataBaseObject::LINK_TRANSACTION_DELETE: {
                    $query_link = $transaction[1];
                    /** @var $query_link Update */
                    if(!$query_link->isSuccessExecuted()) return false;
                } break;
            }
        }
        return true;
    }
    protected function onPreUpdate() {}
    protected abstract function onUpdate() : Update;
    protected function onPostUpdate() {}
    public function delete() {
        if(!$this->isRunnerValid()) throw new Exception("Invalid Connection to Database");
        $this->onPreDelete();
        $query = $this->onDelete();
        $query_array = new QueryArray();
        $query_array->append($query);
        $this->getRunner()->appendQueryArray($query_array);
        $this->getRunner()->execute();
        $this->onPostDelete();
    }
    protected function onPreDelete() {}
    protected abstract function onDelete() : Delete;
    protected function onPostDelete() {}
    /**
     * @param array $arr
     */
    protected function setArr(array $arr) {$this->arr = $arr; $this->resetArrPtr();}
    protected final function resetArrPtr(){$this->arr_pointer = 0;}
    protected function nextIndex() {$this->arr_pointer++;}protected function previousIndex() {$this->arr_pointer--;}
    protected function getArr() {return $this->arr[$this->arr_pointer];}
    protected function isIndexValid() : bool {return $this->arr_pointer > -1 && $this->arr_pointer < count($this->arr);}
    public function rewind()
    {
        // TODO: Implement rewind() method.
        if(!$this->isRunnerValid()) throw new Exception("Invalid Connection to Database");
        $query = $this->onRewind();
        $query_array = new QueryArray();
        if(count($this->link_transaction) > 0) $query_array->setExecuteAsTransaction();
        $query_array->append($query);
        foreach ($this->link_transaction as $item) {
            $dbo = $item[0];
            /** @var $dbo DataBaseObject */
            if($item[1] === DataBaseObject::LINK_TRANSACTION_LOAD){
                switch ($item[2]) {
                    case DataBaseObject::LINK_TRANSACTION_LOAD: $query_array->append($dbo->getLoadQuery()); break;
                    case DataBaseObject::LINK_TRANSACTION_LOOP: $query_array->append($dbo->getLoopQuery()); break;
                    case DataBaseObject::LINK_TRANSACTION_ADD: $query_array->append($dbo->getAddQuery()); break;
                    case DataBaseObject::LINK_TRANSACTION_UPDATE: $query_array->append($dbo->getUpdateQuery()); break;
                    case DataBaseObject::LINK_TRANSACTION_DELETE: $query_array->append($dbo->getDeleteQuery()); break;
                }
            }
        }
        $this->getRunner()->appendQueryArray($query_array);
        $this->getRunner()->execute();
        $this->setArr($query->fetchAll(PDO::FETCH_ASSOC));
    }
    protected abstract function onRewind(): Select;
    public function valid(){return $this->arr_pointer < count($this->arr);}
    public function key(){return $this->arr_pointer;}
    public function next(){$this->arr_pointer++;}
    public function setRunner(Runner &$runner) {$this->runner = &$runner;}
    public function destroyRunner() {$this->runner = null;}
    public function &getRunner() : Runner {return $this->runner;}
    public function isRunnerValid() : bool {return $this->getRunner() !== null && $this->getRunner() instanceof Runner;}
    public function count()
    {
        if (count($this->arr) === 0) $this->rewind();
        return count($this->arr);
    }
    public function appendLinkTransaction(DataBaseObject $dbo, int $link_from, int $link_to) {array_push($this->link_transaction, array($dbo, $link_from, $link_to));}
    public function getLoadQuery() : Select { return $this->onLoad();}
    public function getLoopQuery() : Select{return $this->onRewind();}
    public function getAddQuery() {return $this->onAdd();}
    public function getUpdateQuery(){return $this->onUpdate();}
    public function getDeleteQuery() {return $this->onDelete();}
    public function appendLinkJoin(string $dbo_class_name){ array_push($this->link_join, $dbo_class_name);  }
    public function getJoinLink() : array {return $this->link_join;}
    public function isJoinBy(string $table) : bool {return in_array($table, $this->getJoinLink());}
}