<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 06/04/2017
 * Time: 16:24
 */
require_once standart_path.'core/DataBaseObject.php';
class MerekMobil extends DataBaseObject
{
    const TNAME = "merek_mobil";
    const C_ID = "id";
    const C_BRAND = "merek";
    const C_PIC = "picture";
    private $id, $merek, $pic;
    /**
     * MerekMobil constructor.
     * @param null $id
     */
    public function __construct($id = null){ $this->id = $id;}
    public function getId() : int{return $this->id;}
    public function getMerek() : string{return $this->merek;}
    public function setMerek(string $merek){$this->merek = $merek;}
    public function getPic() : string {return $this->pic;}
    public function setPic(string $pic) {$this->pic = $pic;}
    protected function onLoad() : Select
    {
        // TODO: Implement onLoad() method.
        $select = new Select(MerekMobil::TNAME);
        $select->appendColumn(new Column(MerekMobil::C_ID));
        $select->appendColumn(new Column(MerekMobil::C_BRAND));
        $select->appendColumn(new Column(MerekMobil::C_PIC));
        $select->append_where(MerekMobil::C_ID.' = ?');
        $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->id));
        return $select;
    }
    protected function onAdd() : Insert
    {
        // TODO: Implement onAdd() method.
        $insert = new Insert(MerekMobil::TNAME);
        $insert->appendColumnValue(new Column(MerekMobil::C_ID), new Parameter($insert->getParameterVariableIntegerOrder(), 1));
        $insert->appendColumnValue(new Column(MerekMobil::C_BRAND), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getMerek()));
        $insert->appendColumnValue(new Column(MerekMobil::C_PIC), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getPic()));
        return $insert;
    }
    public function current() : MerekMobil
    {
        // TODO: Implement current() method.
        $merek = new MerekMobil($this->getArr()[MerekMobil::C_ID]);
        $merek->setMerek($this->getArr()[MerekMobil::C_BRAND]);
        $merek->setPic($this->getArr()[MerekMobil::C_PIC]);
        return $merek;
    }

    protected function onUpdate() : Update
    {
        // TODO: Implement onUpdate() method.
        $update = new Update(MerekMobil::TNAME);
        $update->apppend_set(MerekMobil::C_BRAND, '?');
        $update->appendParameter(new Parameter($update->getParameterVariableIntegerOrder(), $this->merek));
        $update->apppend_set(MerekMobil::C_PIC, '?');
        $update->appendParameter(new Parameter($update->getParameterVariableIntegerOrder(), $this->pic));
        return $update;
    }
    protected function onRewind(): Select
    {
        // TODO: Implement onRewind() method.
        $select = new Select(MerekMobil::TNAME);
        $select->appendColumn(new Column(MerekMobil::C_ID));
        $select->appendColumn(new Column(MerekMobil::C_BRAND));
        $select->appendColumn(new Column(MerekMobil::C_PIC));
        return $select;
    }

    public function onDelete() : Delete
    {
        // TODO: Implement onDelete() method.
        $delete = new Delete(MerekMobil::TNAME);
        $delete->append_where(MerekMobil::C_ID.'=?');
        $delete->appendParameter(new Parameter($delete->getParameterVariableIntegerOrder(), $this->id));
        return $delete;
    }

    protected function onPostLoad($data)
    {
        // TODO: Implement onPostLoad() method.
        $this->id = $data[MerekMobil::C_ID];
        $this->setMerek($data[MerekMobil::C_BRAND]);
        $this->setPic($data[MerekMobil::C_PIC]);
    }
}