<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 26/04/2017
 * Time: 12:55
 */
require_once standart_path.'core/DataBaseObject.php';
abstract class CustomDataObject extends DataBaseObject
{
    const LOAD_LOOP_TYPE_FULL = 0;
    private $load_loop_type, $load_by, $loop_by;
    private $parameters;
    public function __construct()
    {
        $this->loop_by = array();
        $this->load_by = array();
        $this->parameters = array();
        $this->load_loop_type = CustomDataObject::LOAD_LOOP_TYPE_FULL;
    }
    protected function getLoadLoopType() : int {return $this->load_loop_type;}
    protected function getLoopBy() : array {return $this->loop_by;}
    protected function getLoadBy() : array {return $this->load_by;}
    protected function isLoopBy(int $loop_by){return in_array($loop_by, $this->getLoopBy());}
    protected function isLoadBy(int $load_by) {return in_array($load_by, $this->getLoadBy());}
    protected function getParameters() : array{return $this->parameters;}
    public function appendParameters($key, $value) {$this->parameters[$key] = $value;}
    public function eraseParameters($key) {unset($this->parameters[$key]);}
    public function appendLoopBy(int $loop_by) {array_push($this->loop_by, $loop_by);}
    public function appendLoadBy(int $load_by){ array_push($this->load_by, $load_by);}
}