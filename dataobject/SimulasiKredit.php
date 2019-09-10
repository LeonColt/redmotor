<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 15/07/2017
 * Time: 18:43
 */
require_once standart_path.'dataobject/CustomDataObject.php';
class SimulasiKredit extends CustomDataObject
{
    const TNAME = "simulasi_kredit";
    const C_DATE = "tanggal";
    const C_FLOWER = "bunga_per_tahun"; // lol should be interest, just for fun
    const C_ADMIN = "biaya_administrasi";
    private $tanggal, $bunga_per_tahun, $administrasi;
    public function getTanggal() : string{return $this->tanggal;}
    public function setTanggal(string $tanggal){$this->tanggal = $tanggal;}
    public function getBungaPerTahun() : int{return $this->bunga_per_tahun;}
    public function setBungaPerTahun(int $bunga_per_tahun){$this->bunga_per_tahun = $bunga_per_tahun;}
    public function getAdministrasi() : int{return $this->administrasi;}
    public function setAdministrasi(int $administrasi){$this->administrasi = $administrasi;}
    public function current()
    {
        // TODO: Implement current() method.
    }

    protected function onLoad(): Select
    {
        $select = new Select(SimulasiKredit::TNAME);
        $select->appendColumn(new Column(SimulasiKredit::C_DATE));
        $select->appendColumn(new Column(SimulasiKredit::C_FLOWER));
        $select->appendColumn(new Column(SimulasiKredit::C_ADMIN));
        $select->append_where("? > ".(string)new Column(SimulasiKredit::C_DATE));
        $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->getTanggal()));
        return $select;
    }

    protected function onPostLoad($data)
    {
        $this->setTanggal($data[SimulasiKredit::C_DATE]);
        $this->setBungaPerTahun($data[SimulasiKredit::C_FLOWER]);
        $this->setAdministrasi($data[SimulasiKredit::C_ADMIN]);
    }

    protected function onAdd(): Insert
    {
        // TODO: Implement onAdd() method.
    }

    protected function onUpdate(): Update
    {
        // TODO: Implement onUpdate() method.
    }

    protected function onDelete(): Delete
    {
        // TODO: Implement onDelete() method.
    }

    protected function onRewind(): Select
    {
        // TODO: Implement onRewind() method.
    }
}