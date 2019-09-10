<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 19/04/2017
 * Time: 11:57
 */
require_once 'CustomDataObject.php';
require_once standart_path.'dataobject/TipeMobil.php';
require_once standart_path.'dataobject/MerekMobil.php';
class JenisMobil extends CustomDataObject
{
    const LOAD_LOOP_KIND_ONLY = 1;
    const LOOP_BY_BRAND_ID = 200;
    const LOOP_BY_TYPE_ID = 201;

    const TNAME = "jenis_mobil";
    const C_ID = "id";
    const C_KIND  = "jenis";
    const C_TYPE = "tipe";
    const C_BRAND = "merek";
    private $id, $jenis, $tipe, $merek;
    public function __construct($id = null)
    {
        parent::__construct();
        $this->id = $id;
    }

    public function getId() : int{return $this->id;}
    public function getJenis() : string{return $this->jenis;}
    public function setJenis(string $jenis){$this->jenis = $jenis;}
    public function getTipe() : TipeMobil{return $this->tipe;}
    public function setTipe(TipeMobil $tipe){$this->tipe = $tipe;}
    public function getMerek() : MerekMobil{return $this->merek;}
    public function setMerek(MerekMobil $merek){$this->merek = $merek;}

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     * @return JenisMobil
     */
    public function current() : JenisMobil
    {
        // TODO: Implement current() method.
        $jenis = new JenisMobil($this->getArr()[JenisMobil::C_ID]);
        $jenis->setJenis($this->getArr()[JenisMobil::C_KIND]);
        $merek = new MerekMobil($this->getArr()[JenisMobil::C_BRAND]);
        $merek->setRunner($this->getRunner());
        $merek->load();
        $jenis->setMerek($merek);
        $tipe = new TipeMobil($this->getArr()[JenisMobil::C_TYPE]);
        $tipe->setRunner($this->getRunner());
        $tipe->load();
        $jenis->setTipe($tipe);
        return $jenis;
    }

    protected function onLoad() : Select
    {
        // TODO: Implement onLoad() method.
        $select = new Select(JenisMobil::TNAME);
        $select->appendColumn(new Column(JenisMobil::C_ID));
        $select->appendColumn(new Column(JenisMobil::C_KIND));
        $select->appendColumn(new Column(JenisMobil::C_TYPE));
        $select->appendColumn(new Column(JenisMobil::C_BRAND));
        return $select;
    }

    protected function onPostLoad($data)
    {
        // TODO: Implement onPostLoad() method.
        $this->id = $data[JenisMobil::C_ID];
        $this->jenis = $data[JenisMobil::C_KIND];
        $this->tipe = new TipeMobil($data[JenisMobil::C_TYPE]);
        $this->tipe->setRunner($this->getRunner());
        $this->tipe->load();
        $this->merek = new MerekMobil($data[JenisMobil::C_BRAND]);
        $this->merek->setRunner($this->getRunner());
        $this->merek->load();
    }

    protected function onAdd() : Insert
    {
        // TODO: Implement onAdd() method.
        $insert = new Insert(JenisMobil::TNAME);
        $insert->appendColumnValue(new Column(JenisMobil::C_ID), new Parameter($insert->getParameterVariableIntegerOrder(), $this->id));
        $insert->appendColumnValue(new Column(JenisMobil::C_KIND), new Parameter($insert->getParameterVariableIntegerOrder(), $this->jenis));
        $insert->appendColumnValue(new Column(JenisMobil::C_TYPE), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getTipe()->getId()));
        $insert->appendColumnValue(new Column(JenisMobil::C_BRAND), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getMerek()->getId()));
        return $insert;
    }

    protected function onUpdate() : Update
    {
        // TODO: Implement onUpdate() method.
    }

    public function onDelete() : Delete
    {
        // TODO: Implement onDelete() method.
    }

    protected function onRewind(): Select
    {
        // TODO: Implement onRewind() method.
        $select = new Select(JenisMobil::TNAME);
        switch($this->getLoadLoopType()) {
            case JenisMobil::LOAD_LOOP_TYPE_FULL: {
                $select->appendColumn(new Column(JenisMobil::C_ID));
                $select->appendColumn(new Column(JenisMobil::C_KIND));
                $select->appendColumn(new Column(JenisMobil::C_TYPE));
                $select->appendColumn(new Column(JenisMobil::C_BRAND));
            } break;

            case JenisMobil::LOAD_LOOP_KIND_ONLY: {
                $select = new Select(JenisMobil::TNAME);
                $select->appendColumn(new Column(JenisMobil::C_ID));
                $select->appendColumn(new Column(JenisMobil::C_KIND));
                $select->appendColumn(new Column(JenisMobil::C_TYPE));
                $select->appendColumn(new Column(JenisMobil::C_BRAND));
            } break;
        }
        if($this->isLoopBy(JenisMobil::LOOP_BY_BRAND_ID)) {
            $select->append_where(JenisMobil::C_BRAND."= ?");
            $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->getParameters()[JenisMobil::LOOP_BY_BRAND_ID]));
        }
        if($this->isLoopBy(JenisMobil::LOOP_BY_TYPE_ID)) {
            $select->append_where(JenisMobil::C_TYPE." = ? ", $this->isLoopBy(JenisMobil::LOOP_BY_BRAND_ID) ? "AND" : null);
            $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->getParameters()[JenisMobil::LOOP_BY_TYPE_ID]));
        }
        return $select;
    }
}