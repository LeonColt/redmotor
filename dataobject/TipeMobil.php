<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 06/04/2017
 * Time: 16:24
 */
class TipeMobil extends DataBaseObject
{
    const TNAME = "tipe_mobil";
    const BY_ID = "id";
    const BY_TIPE = "tipe";
    const C_ID = "id";
    const C_TYPE = "tipe";
    private $id, $type;
    /**
     * TipeMobil constructor.
     * @param int $id
     */
    public function __construct( $id = null){ $this->id = $id;}
    /**
     * @return int
     */
    public function getId(){return $this->id;}
    /**
     * @return string
     */
    public function getType() : string{return $this->type;}
    /**
     * @param string $type
     */
    public function setType(string $type){$this->type = $type;}

    protected function onLoad() : Select
    {
        // TODO: Implement onLoad() method.
        $select = new Select(TipeMobil::TNAME);
        $select->appendColumn(new Column(TipeMobil::C_ID));
        $select->appendColumn(new Column(TipeMobil::C_TYPE));
        $select->append_where('id = ?');
        $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->id));
        $select->fetchAssoc();
        return $select;
    }

    protected function onAdd() : Insert
    {
        // TODO: Implement onAdd() method.
        $random = new Random();
        do {
            $exists = false;
            $id = $random->random_number_int(0);
            $select = new Select(User::TNAME);
            $select->appendColumn(new Column('id'));
            $select->append_where('id = ?');
            $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $id));
            $this->getRunner()->clear_query();
            $this->getRunner()->append_query($select);
            $this->getRunner()->execute();
            if(count($select->result()) > 0) $exists = true;
        } while($exists);
        $this->id = $id;
        $insert = new Insert(TipeMobil::TNAME);
        $insert->append_column_value('id', new Parameter($insert->getParameterVariableIntegerOrder(), $this->id));
        $insert->append_column_value('tipe', new Parameter($insert->getParameterVariableIntegerOrder(), $this->type));
        return $insert;
    }

    protected function onUpdate(Parameter ...$parameters) : Update
    {
        // TODO: Implement onUpdate() method.
        $update = new Update(TipeMobil::TNAME);
        $update->apppend_set('tipe', '?');
        $update->append_parameter(new Parameter($update->getParameterVariableIntegerOrder(), $this->type));
        $update->append_where('id = ?');
        $update->append_parameter(new Parameter($update->getParameterVariableIntegerOrder(), $this->id));
    }

    protected function onRewind(): Select
    {
        // TODO: Implement onRewind() method.
        $select = new Select(TipeMobil::TNAME);
        $select->appendColumn(new Column(TipeMobil::C_ID));
        $select->appendColumn(new Column(TipeMobil::C_TYPE));
        return $select;
    }

    public function current() : TipeMobil
    {
        // TODO: Implement current() method.
        $tipe = new TipeMobil($this->getArr()[TipeMobil::C_ID]);
        $tipe->setType($this->getArr()[TipeMobil::C_TYPE]);
        return $tipe;
    }

    public function onDelete() : Delete
    {
        // TODO: Implement onDelete() method.
        $delete = new Delete(TipeMobil::TNAME);
        $delete->append_where('id = ?');
        $delete->appendParameter(new Parameter($delete->getParameterVariableIntegerOrder(), $this->id));
        return $delete;
    }

    protected function onPostLoad($data)
    {
        // TODO: Implement onPostLoad() method.
        $this->id = $data[TipeMobil::C_ID];
        $this->type = $data[TipeMobil::C_TYPE];
    }
}