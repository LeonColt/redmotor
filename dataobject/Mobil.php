<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 06/04/2017
 * Time: 16:24
 */
require_once 'CustomDataObject.php';
require_once standart_path.'dataobject/JenisMobil.php';
require_once standart_path.'dataobject/MerekMobil.php';
require_once standart_path.'dataobject/TipeMobil.php';
require_once standart_path.'dataobject/CarValue.php';
require_once standart_path.'dataobject/Pemesanan.php';
require_once standart_path.'dataobject/Penjualan.php';
require_once standart_path.'dataobject/Tranmission.php';
require_once standart_path.'common/ImageBase64.php';
require_once standart_path.'dataobject/DataObjectConstant.php';
class Mobil extends CustomDataObject
{
    const TNAME = "mobil";
    const LOAD_LOOP_TYPE_CAR_ONLY = 2;

    const LOAD_BY_ID = 100;

    const LOOP_BY_BRAND_ID = 200;
    const LOOP_BY_TYPE_ID = 201;
    const LOOP_BY_KIND_ID = 202;
    const LOOP_BY_TRANSMISSION = 203;
    const LOOP_BY_PRICE_EQUAL = 204;
    const LOOP_BY_PRICE_MINIMUM = 205;
    const LOOP_BY_PRICE_MAXIMUM = 206;
    const LOOP_BY_PRICE_RANGE = 207;
    const LOOP_BY_ODOMETER_EQUAL = 208;
    const LOOP_BY_ODOMETER_MINIMUM = 209;
    const LOOP_BY_ODOMETER_MAXIMUM = 210;
    const LOOP_BY_ODOMETER_RANGE = 211;
    const LOOP_BY_NOT_SOLD = 212;
    const LOOP_BY_SOLD = 213;

    const C_ID = "id";
    const CJA_KIND_ID = "id_jenis";
    const CJA_KIND = "jenis";
    const CJA_BRAND_ID = "id_merek";
    const CJA_BRAND = "merek";
    const CJA_BRAND_PICTURE = "gambar_merek";
    const CJA_TYPE_ID = "id_type";
    const CJA_TYPE = "type";
    const CJA_TRANSMISSION_ID = "transmission_id";
    const CJA_TRANSMISSION_TRANSMISSION = "transmission";
    const CJA_VALUE_DOCUMENT = "nilai_dokumen";
    const CJA_VALUE_ENGINE = "nilai_engine";
    const CJA_VALUE_ODOMETER = "nilai_odometer";
    const CJA_VALUE_INTERIOR = "nilai_interior";
    const CJA_VALUE_EXTERIOR = "nilai_exterior";
    const CJA_VALUE_YEAR = "nilai_year";
    const CJA_VALUE_PRICE = "nilai_harga";
    const C_NAME = "nama";
    const C_KIND_FK = "jenis";
    const C_TRANSMISSION_FK = "transmission";
    const C_YEAR = "tahun";
    const C_PRICE = "harga";
    const C_COLOR = "warna";
    const C_ODOMETER = "odometer";
    const C_SN_ENGINE = "no_mesin";
    const C_SN_CHASIS = "no_rangka";
    const C_PICTURE = "pic";
    const C_SELLER = "seller";
    const C_BUYER = "buyer";
    private $id, $name, $jenis, $transmission, $year, $price, $color, $odometer, $nengine, $nchasis, $pic, $car_value, $score;
    public function __construct($id = null){
        parent::__construct();
        $this->id = $id;
        $this->score = 0;
    }
    public function getId() : int{return (int)$this->id;}
    public function getName() : string{return $this->name;}
    public function setName(string $name){$this->name = $name;}
    public function getJenis() : ?JenisMobil{return $this->jenis;}
    public function setJenis(JenisMobil $jenis){$this->jenis = $jenis;}
    public function getTransmission() : ?Transmission {return $this->transmission;}
    public function setTransmission(Transmission $transmission) {$this->transmission = $transmission;}
    public function getYear() : int{return $this->year;}
    public function setYear(int $year){$this->year = $year;}
    public function getPrice() : int{return $this->price;}
    public function setPrice(int $price){$this->price = $price;}
    public function getColor() : string{return $this->color;}
    public function setColor(string $color){$this->color = $color;}
    public function getOdometer() : int {return $this->odometer;}
    public function setOdometer(int $odometer) {$this->odometer = $odometer;}
    public function getNengine() : string{return $this->nengine;}
    public function setNengine(string $nengine){$this->nengine = $nengine;}
    public function getNchasis() : string{return $this->nchasis;}
    public function setNchasis(string $nchasis){$this->nchasis = $nchasis;}
    public function getPic() : ImageBase64{return $this->pic;}
    public function setPic(ImageBase64 $pic){$this->pic = $pic;}
    public function getCarValue() : ?CarValue{return $this->car_value;}
    public function setCarValue(CarValue $car_value) {$this->car_value = $car_value;}
    public function setScore(int $score){$this->score = $score;}
    public function getScore() : int {return $this->score;}
    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current() : Mobil
    {
        // TODO: Implement current() method.
        $mobil = new Mobil($this->getArr()[Mobil::C_ID]);
        $mobil->setName($this->getArr()[Mobil::C_NAME]);

        foreach ($this->getJoinLink() as $item) {
            switch ($item) {
                case DataObjectConstant::TABLE_CAR_KIND: {
                    $jenis = new JenisMobil($this->getArr()[Mobil::CJA_KIND_ID]);
                    $jenis->setJenis($this->getArr()[Mobil::CJA_KIND]);
                    $mobil->setJenis($jenis);
                } break;

                case DataObjectConstant::TABLE_CAR_TYPE: {
                    $tipe = new TipeMobil($this->getArr()[Mobil::CJA_TYPE_ID]);
                    $tipe->setType($this->getArr()[Mobil::CJA_TYPE]);
                    $mobil->getJenis()->setTipe($tipe);
                } break;

                case DataObjectConstant::TABLE_CAR_BRAND: {
                    $merek = new MerekMobil($this->getArr()[Mobil::CJA_BRAND_ID]);
                    $merek->setMerek($this->getArr()[Mobil::CJA_BRAND]);
                    $merek->setPic($this->getArr()[Mobil::CJA_BRAND_PICTURE]);
                    $mobil->getJenis()->setMerek($merek);
                } break;

                case DataObjectConstant::TABLE_CAR_TRANSMISSION: {
                    $trans = new Transmission($this->getArr()[Mobil::CJA_TRANSMISSION_ID]);
                    $trans->setTransmission($this->getArr()[Mobil::CJA_TRANSMISSION_TRANSMISSION]);
                    $mobil->setTransmission($trans);
                } break;

                case DataObjectConstant::TABLE_CAR_VALUE: {
                    $value = new CarValue($this->id);
                    $value->setDocument($this->getArr()[Mobil::CJA_VALUE_DOCUMENT]);
                    $value->setEngine($this->getArr()[Mobil::CJA_VALUE_ENGINE]);
                    $value->setOdometer($this->getArr()[Mobil::CJA_VALUE_ODOMETER]);
                    $value->setInterior($this->getArr()[Mobil::CJA_VALUE_INTERIOR]);
                    $value->setExterior($this->getArr()[Mobil::CJA_VALUE_EXTERIOR]);
                    $value->setYear($this->getArr()[Mobil::CJA_VALUE_YEAR]);
                    $value->setPrice($this->getArr()[Mobil::CJA_VALUE_PRICE]);
                    $mobil->setCarValue($value);
                } break;
            }
        }

        $mobil->setYear($this->getArr()[Mobil::C_YEAR]);
        $mobil->setPrice((int)$this->getArr()[Mobil::C_PRICE]);
        $mobil->setColor($this->getArr()[Mobil::C_COLOR]);
        $mobil->setOdometer($this->getArr()[Mobil::C_ODOMETER]);
        $mobil->setNengine($this->getArr()[Mobil::C_SN_ENGINE]);
        $mobil->setNchasis($this->getArr()[Mobil::C_SN_CHASIS]);

        $mobil->setPic(new ImageBase64($this->getArr()[Mobil::C_PICTURE]));

        return $mobil;
    }

    protected function onLoad() : Select
    {
        // TODO: Implement onLoad() method.
        $select = new Select(Mobil::TNAME);
        $select->appendColumn(new Column(Mobil::C_ID, Mobil::TNAME));
        $select->appendColumn(new Column(Mobil::C_NAME));
        $select->appendColumn(new Column(Mobil::C_YEAR));
        $select->appendColumn(new Column(Mobil::C_PRICE));
        $select->appendColumn(new Column(Mobil::C_COLOR));
        $select->appendColumn(new Column(Mobil::C_ODOMETER));
        $select->appendColumn(new Column(Mobil::C_SN_ENGINE));
        $select->appendColumn(new Column(Mobil::C_SN_CHASIS));
        $select->appendColumn(new Column(Mobil::C_PICTURE));
        foreach ($this->getJoinLink() as $item) {
            switch ($item) {
                case DataObjectConstant::TABLE_CAR_KIND : {
                    $select->appendColumn(new Column(JenisMobil::C_ID, JenisMobil::TNAME, Mobil::CJA_KIND_ID));
                    $select->appendColumn(new Column(JenisMobil::C_KIND, JenisMobil::TNAME, Mobil::CJA_KIND) );
                } break;

                case DataObjectConstant::TABLE_CAR_TYPE : {
                    $select->appendColumn(new Column(TipeMobil::C_ID, TipeMobil::TNAME, Mobil::CJA_TYPE_ID));
                    $select->appendColumn(new Column(TipeMobil::C_TYPE, TipeMobil::TNAME, Mobil::CJA_TYPE));
                } break;

                case DataObjectConstant::TABLE_CAR_BRAND : {
                    $select->appendColumn(new Column(MerekMobil::C_ID, MerekMobil::TNAME, Mobil::CJA_BRAND_ID));
                    $select->appendColumn(new Column(MerekMobil::C_BRAND, MerekMobil::TNAME, Mobil::CJA_BRAND));
                    $select->appendColumn(new Column(MerekMobil::C_PIC, MerekMobil::TNAME, Mobil::CJA_BRAND_PICTURE));
                } break;

                case DataObjectConstant::TABLE_CAR_TRANSMISSION : {
                    $select->appendColumn(new Column(Transmission::C_ID, Transmission::TNAME, Mobil::CJA_TRANSMISSION_ID));
                    $select->appendColumn(new Column(Transmission::C_TRANSMISSION, Transmission::TNAME, Mobil::CJA_TRANSMISSION_TRANSMISSION));
                } break;

                case DataObjectConstant::TABLE_CAR_VALUE : {
                    $select->appendColumn(new Column(CarValue::C_DOCUMENT, CarValue::TNAME, Mobil::CJA_VALUE_DOCUMENT));
                    $select->appendColumn(new Column(CarValue::C_ENGINE, CarValue::TNAME, Mobil::CJA_VALUE_ENGINE));
                    $select->appendColumn(new Column(CarValue::C_ODOMETER, CarValue::TNAME, Mobil::CJA_VALUE_ODOMETER));
                    $select->appendColumn(new Column(CarValue::C_INTERIOR, CarValue::TNAME, Mobil::CJA_VALUE_INTERIOR));
                    $select->appendColumn(new Column(CarValue::C_EXTERIOR, CarValue::TNAME, Mobil::CJA_VALUE_EXTERIOR));
                    $select->appendColumn(new Column(CarValue::C_YEAR, CarValue::TNAME, Mobil::CJA_VALUE_YEAR));
                    $select->appendColumn(new Column(CarValue::C_PRICE, CarValue::TNAME, Mobil::CJA_VALUE_PRICE));
                } break;
            }
        }
        //end column

        //begin join
        foreach ($this->getJoinLink() as $item) {
            switch ($item) {
                case DataObjectConstant::TABLE_CAR_KIND : {
                    $select->appendJoin(new JoinTable(JenisMobil::TNAME));
                    $select->appendJoinOnEx(Mobil::TNAME, Mobil::C_KIND_FK, "=", JenisMobil::TNAME, JenisMobil::C_ID);
                } break;

                case DataObjectConstant::TABLE_CAR_TYPE : {
                    $select->appendJoin(new JoinTable(TipeMobil::TNAME));
                    $select->appendJoinOnEx(JenisMobil::TNAME, JenisMobil::C_TYPE, "=", TipeMobil::TNAME, TipeMobil::C_ID);
                } break;

                case DataObjectConstant::TABLE_CAR_BRAND : {
                    $select->appendJoin(new JoinTable(MerekMobil::TNAME));
                    $select->appendJoinOnEx(JenisMobil::TNAME, JenisMobil::C_BRAND, "=", MerekMobil::TNAME, MerekMobil::C_ID);
                } break;

                case DataObjectConstant::TABLE_CAR_TRANSMISSION : {
                    $select->appendJoin(new JoinTable(Transmission::TNAME));
                    $select->appendJoinOnEx(Mobil::TNAME, Mobil::C_TRANSMISSION_FK, "=", Transmission::TNAME, Transmission::C_ID);
                } break;

                case DataObjectConstant::TABLE_CAR_VALUE : {
                    $select->appendJoin(new JoinTable(CarValue::TNAME));
                    $select->appendJoinOnEx(Mobil::TNAME, Mobil::C_ID, "=", CarValue::TNAME, CarValue::C_ID);
                } break;
            }
        }
        //end join
        if($this->isLoadBy(Mobil::LOAD_BY_ID)) {
            $select->append_where(Mobil::TNAME.".".Mobil::C_ID."=?");
            $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->getId()));
        }
        return $select;
    }

    protected function onPostLoad($data)
    {
        // TODO: Implement onPostLoad() method.
        if(empty($data)) throw new Exception("Mobil tidak ditemukan");
        $this->id = $data[Mobil::C_ID];
        $this->setName($data[Mobil::C_NAME]);

        foreach ($this->getJoinLink() as $item) {
            switch ($item) {
                case DataObjectConstant::TABLE_CAR_KIND: {
                    $jenis = new JenisMobil($data[Mobil::CJA_KIND_ID]);
                    $jenis->setJenis($data[Mobil::CJA_KIND]);
                    $this->setJenis($jenis);
                } break;

                case DataObjectConstant::TABLE_CAR_TYPE: {
                    $tipe = new TipeMobil($data[Mobil::CJA_TYPE_ID]);
                    $tipe->setType($data[Mobil::CJA_TYPE]);
                    $this->getJenis()->setTipe($tipe);
                } break;

                case DataObjectConstant::TABLE_CAR_BRAND: {
                    $merek = new MerekMobil($data[Mobil::CJA_BRAND_ID]);
                    $merek->setMerek($data[Mobil::CJA_BRAND]);
                    $merek->setPic($data[Mobil::CJA_BRAND_PICTURE]);
                    $this->getJenis()->setMerek($merek);
                } break;

                case DataObjectConstant::TABLE_CAR_TRANSMISSION: {
                    $trans = new Transmission($data[Mobil::CJA_TRANSMISSION_ID]);
                    $trans->setTransmission($data[Mobil::CJA_TRANSMISSION_TRANSMISSION]);
                    $this->setTransmission($trans);
                } break;

                case DataObjectConstant::TABLE_CAR_VALUE: {
                    $value = new CarValue($this->id);
                    $value->setDocument($data[Mobil::CJA_VALUE_DOCUMENT]);
                    $value->setEngine($data[Mobil::CJA_VALUE_ENGINE]);
                    $value->setOdometer($data[Mobil::CJA_VALUE_ODOMETER]);
                    $value->setInterior($data[Mobil::CJA_VALUE_INTERIOR]);
                    $value->setExterior($data[Mobil::CJA_VALUE_EXTERIOR]);
                    $value->setYear($data[Mobil::CJA_VALUE_YEAR]);
                    $value->setPrice($data[Mobil::CJA_VALUE_PRICE]);
                    $this->setCarValue($value);
                } break;
            }
        }

        $this->setYear($data[Mobil::C_YEAR]);
        $this->setPrice($data[Mobil::C_PRICE]);
        $this->setColor($data[Mobil::C_COLOR]);
        $this->setOdometer($data[Mobil::C_ODOMETER]);
        $this->setNengine($data[Mobil::C_SN_ENGINE]);
        $this->setNchasis($data[Mobil::C_SN_CHASIS]);
        $this->setPic(new ImageBase64($data[Mobil::C_PICTURE]));
    }

    protected function onAdd() : Insert
    {
        // TODO: Implement onAdd() method.
        $insert = new Insert(Mobil::TNAME, true);
        $insert->appendColumnValue(new Column(Mobil::C_ID), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getId()));
        $insert->appendColumnValue(new Column(Mobil::C_NAME), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getName()));
        $insert->appendColumnValue(new Column(Mobil::C_KIND_FK), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getJenis()->getId()));
        $insert->appendColumnValue(new Column(Mobil::C_TRANSMISSION_FK), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getTransmission()->getId()));
        $insert->appendColumnValue(new Column(Mobil::C_YEAR), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getYear()));
        $insert->appendColumnValue(new Column(Mobil::C_PRICE), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getPrice()));
        $insert->appendColumnValue(new Column(Mobil::C_COLOR), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getColor()));
        $insert->appendColumnValue(new Column(Mobil::C_ODOMETER), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getOdometer()));
        $insert->appendColumnValue(new Column(Mobil::C_SN_ENGINE), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getNengine()));
        $insert->appendColumnValue(new Column(Mobil::C_SN_CHASIS), new Parameter($insert->getParameterVariableIntegerOrder(), $this->getNchasis()));
        $insert->appendColumnValue(new Column(Mobil::C_PICTURE), new Parameter($insert->getParameterVariableIntegerOrder(), (string)$this->getPic()));
        $insert->appendPrimaryKey(new Column(Mobil::C_ID));
        return $insert;
    }

    protected function onPostAdd()
    {
        parent::onPostAdd(); // TODO: Change the autogenerated stub
        if($this->getCarValue() !== null) $this->getCarValue()->add();
    }

    protected function onUpdate() : Update
    {
        // TODO: Implement onUpdate() method.
        $update = new Update(Mobil::TNAME);
        $update->appendSet(new Column(Mobil::C_PRICE), '?');
        $update->appendParameter(new Parameter($update->getParameterVariableIntegerOrder(), $this->price));
        $update->append_where(Mobil::C_ID." = ?");
        $update->appendParameter(new Parameter($update->getParameterVariableIntegerOrder(), $this->id));
        return $update;
    }

    public function onDelete() : Delete
    {
        // TODO: Implement onDelete() method.
        $del = new Delete(Mobil::TNAME);
        $del->append_where('id = ?');
        $del->appendParameter(new Parameter($del->getParameterVariableIntegerOrder(), $this->id));
        return $del;
    }

    protected function onRewind(): Select
    {
        // TODO: Implement onRewind() method.
        $select = new Select(Mobil::TNAME);
        //begin column
        $select->appendColumn(new Column(Mobil::C_ID, Mobil::TNAME));
        $select->appendColumn(new Column(Mobil::C_NAME));
        $select->appendColumn(new Column(Mobil::C_YEAR));
        $select->appendColumn(new Column(Mobil::C_PRICE));
        $select->appendColumn(new Column(Mobil::C_COLOR));
        $select->appendColumn(new Column(Mobil::C_ODOMETER));
        $select->appendColumn(new Column(Mobil::C_SN_ENGINE));
        $select->appendColumn(new Column(Mobil::C_SN_CHASIS));
        $select->appendColumn(new Column(Mobil::C_PICTURE));
        foreach ($this->getJoinLink() as $item) {
            switch ($item) {
                case DataObjectConstant::TABLE_CAR_KIND : {
                    $select->appendColumn(new Column(JenisMobil::C_ID, JenisMobil::TNAME, Mobil::CJA_KIND_ID));
                    $select->appendColumn(new Column(JenisMobil::C_KIND, JenisMobil::TNAME, Mobil::CJA_KIND) );
                } break;

                case DataObjectConstant::TABLE_CAR_TYPE : {
                    $select->appendColumn(new Column(TipeMobil::C_ID, TipeMobil::TNAME, Mobil::CJA_TYPE_ID));
                    $select->appendColumn(new Column(TipeMobil::C_TYPE, TipeMobil::TNAME, Mobil::CJA_TYPE));
                } break;

                case DataObjectConstant::TABLE_CAR_BRAND : {
                    $select->appendColumn(new Column(MerekMobil::C_ID, MerekMobil::TNAME, Mobil::CJA_BRAND_ID));
                    $select->appendColumn(new Column(MerekMobil::C_BRAND, MerekMobil::TNAME, Mobil::CJA_BRAND));
                    $select->appendColumn(new Column(MerekMobil::C_PIC, MerekMobil::TNAME, Mobil::CJA_BRAND_PICTURE));
                } break;

                case DataObjectConstant::TABLE_CAR_TRANSMISSION : {
                    $select->appendColumn(new Column(Transmission::C_ID, Transmission::TNAME, Mobil::CJA_TRANSMISSION_ID));
                    $select->appendColumn(new Column(Transmission::C_TRANSMISSION, Transmission::TNAME, Mobil::CJA_TRANSMISSION_TRANSMISSION));
                } break;

                case DataObjectConstant::TABLE_CAR_VALUE : {
                    $select->appendColumn(new Column(CarValue::C_DOCUMENT, CarValue::TNAME, Mobil::CJA_VALUE_DOCUMENT));
                    $select->appendColumn(new Column(CarValue::C_ENGINE, CarValue::TNAME, Mobil::CJA_VALUE_ENGINE));
                    $select->appendColumn(new Column(CarValue::C_ODOMETER, CarValue::TNAME, Mobil::CJA_VALUE_ODOMETER));
                    $select->appendColumn(new Column(CarValue::C_INTERIOR, CarValue::TNAME, Mobil::CJA_VALUE_INTERIOR));
                    $select->appendColumn(new Column(CarValue::C_EXTERIOR, CarValue::TNAME, Mobil::CJA_VALUE_EXTERIOR));
                    $select->appendColumn(new Column(CarValue::C_YEAR, CarValue::TNAME, Mobil::CJA_VALUE_YEAR));
                    $select->appendColumn(new Column(CarValue::C_PRICE, CarValue::TNAME, Mobil::CJA_VALUE_PRICE));
                } break;
            }
        }
        //end column

        //begin join
        foreach ($this->getJoinLink() as $item) {
            switch ($item) {
                case DataObjectConstant::TABLE_CAR_KIND : {
                    $select->appendJoin(new JoinTable(JenisMobil::TNAME));
                    $select->appendJoinOnEx(Mobil::TNAME, Mobil::C_KIND_FK, "=", JenisMobil::TNAME, JenisMobil::C_ID);
                } break;

                case DataObjectConstant::TABLE_CAR_TYPE : {
                    $select->appendJoin(new JoinTable(TipeMobil::TNAME));
                    $select->appendJoinOnEx(JenisMobil::TNAME, JenisMobil::C_TYPE, "=", TipeMobil::TNAME, TipeMobil::C_ID);
                } break;

                case DataObjectConstant::TABLE_CAR_BRAND : {
                    $select->appendJoin(new JoinTable(MerekMobil::TNAME));
                    $select->appendJoinOnEx(JenisMobil::TNAME, JenisMobil::C_BRAND, "=", MerekMobil::TNAME, MerekMobil::C_ID);
                } break;

                case DataObjectConstant::TABLE_CAR_TRANSMISSION : {
                    $select->appendJoin(new JoinTable(Transmission::TNAME));
                    $select->appendJoinOnEx(Mobil::TNAME, Mobil::C_TRANSMISSION_FK, "=", Transmission::TNAME, Transmission::C_ID);
                } break;

                case DataObjectConstant::TABLE_CAR_VALUE : {
                    $select->appendJoin(new JoinTable(CarValue::TNAME));
                    $select->appendJoinOnEx(Mobil::TNAME, Mobil::C_ID, "=", CarValue::TNAME, CarValue::C_ID);
                } break;
            }
        }
        if($this->isLoopBy(Mobil::LOOP_BY_NOT_SOLD)) {
            $select->appendJoin(new JoinTable(Pemesanan::TNAME, JoinTable::LEFT_JOIN));
            $select->appendJoinOnEx(Mobil::TNAME, Mobil::C_ID, "=", Pemesanan::TNAME, Pemesanan::C_CAR_FK);
            $select->appendJoin(new JoinTable(Penjualan::TNAME, JoinTable::LEFT_JOIN));
            $select->appendJoinOnEx(Pemesanan::TNAME, Pemesanan::C_ID, "=", Penjualan::TNAME, Penjualan::C_BOOK_FK);
        }
        //end join
        $indexer = 0;
        if( $this->isLoopBy(Mobil::LOOP_BY_BRAND_ID) ) {
            $select->append_where(MerekMobil::TNAME.".".MerekMobil::C_ID."=?");
            $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->getJenis()->getMerek()->getId()));
            $indexer++;
        }
        if( $this->isLoopBy(Mobil::LOOP_BY_TYPE_ID) ) {
            $select->append_where(TipeMobil::TNAME.".".MerekMobil::C_ID."=?", ($indexer > 0) ? "AND" : null);
            $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->getJenis()->getTipe()->getId()));
            $indexer++;
        }
        if( $this->isLoopBy(Mobil::LOOP_BY_KIND_ID) ) {
            $select->append_where(JenisMobil::TNAME.".".JenisMobil::C_ID.'=?', ($indexer > 0) ? "AND" : null);
            $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->getJenis()->getId()));
            $indexer++;
        }
        if($this->isLoopBy(Mobil::LOOP_BY_TRANSMISSION)) {
            $select->append_where(Mobil::TNAME.".".Mobil::C_TRANSMISSION_FK ."=?", ($indexer > 0) ? "AND" : null);
            $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->getTransmission()->getId()));
            $indexer++;
        }
        if( $this->isLoopBy(Mobil::LOOP_BY_PRICE_EQUAL) ) {
            $select->append_where(Mobil::C_PRICE." = ?", ($indexer > 0) ? "AND" : null);
            $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->getPrice()));
            $indexer++;
        }
        else if( $this->isLoopBy(Mobil::LOOP_BY_PRICE_RANGE) ) {
            $select->append_where(Mobil::C_PRICE." BETWEEN ? AND ?", ($indexer > 0) ? "AND" : null);
            $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->getParameters()[Mobil::LOOP_BY_PRICE_MINIMUM]));
            $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->getParameters()[Mobil::LOOP_BY_PRICE_MAXIMUM]));
            $indexer++;
        }
        else if($this->isLoopBy(Mobil::LOOP_BY_PRICE_MINIMUM)) {
            $select->append_where(Mobil::C_PRICE." >= ?", ($indexer > 0) ? "AND" : null);
            $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->getParameters()[Mobil::LOOP_BY_PRICE_MINIMUM]));
            $indexer++;
        }
        else if( $this->isLoopBy(Mobil::LOOP_BY_PRICE_MAXIMUM)) {
            $select->append_where(Mobil::C_PRICE." <= ?", ($indexer > 0) ? "AND" : null);
            $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->getParameters()[Mobil::LOOP_BY_PRICE_MAXIMUM]));
            $indexer++;
        }
        if( $this->isLoopBy(Mobil::LOOP_BY_ODOMETER_EQUAL)) {
            $select->append_where(Mobil::C_ODOMETER." = ?", ($indexer > 0) ? "AND" : null);
            $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->getOdometer() ));
            $indexer++;
        }
        else if( $this->isLoopBy(Mobil::LOOP_BY_ODOMETER_RANGE)) {
            $select->append_where(Mobil::C_ODOMETER." BETWEEN ? AND ?", ($indexer > 0) ? "AND" : null);
            $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->getParameters()[Mobil::LOOP_BY_ODOMETER_MINIMUM]));
            $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->getParameters()[Mobil::LOOP_BY_ODOMETER_MINIMUM]));
            $indexer++;
        }
        else if($this->isLoopBy(Mobil::LOOP_BY_ODOMETER_MINIMUM)) {
            $select->append_where(Mobil::C_ODOMETER." >= ?", ($indexer > 0) ? "AND" : null);
            $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->getParameters()[Mobil::LOOP_BY_ODOMETER_MINIMUM]));
            $indexer++;
        }
        else if( $this->isLoopBy(Mobil::LOOP_BY_ODOMETER_MINIMUM)) {
            $select->append_where(Mobil::C_ODOMETER." <= ?", ($indexer > 0) ? "AND" : null);
            $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->getParameters()[Mobil::LOOP_BY_ODOMETER_MAXIMUM]));
            $indexer++;
        }
        if($this->isLoopBy(Mobil::LOOP_BY_NOT_SOLD)) {
            $select->append_where(new Column(Pemesanan::C_CAR_FK, Pemesanan::TNAME)." IS NULL", ($indexer > 0) ? "AND" : null);
            $indexer++;
            $select->append_where(new Column(Penjualan::C_BOOK_FK, Penjualan::TNAME)." IS NULL", ($indexer > 0) ? "AND" : null);
            $indexer++;
        }
        if(is_array($this->id)) {
            reset($this->id);
            $first = key($this->id);
            $select->append_where((string)new Column(Mobil::C_ID, Mobil::TNAME)." IN(");
            foreach ($this->id as $index => $id){
                if($first !== $index) $select->append_where(",");
                $select->append_where("?");
                $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $id));
            }
            $select->append_where(")");
        }
        return $select;
    }
    public function setLoopByBrandId() {$this->appendLoopBy(Mobil::LOOP_BY_BRAND_ID);}
    public function setLoopByTypeId() {$this->appendLoopBy(Mobil::LOOP_BY_TYPE_ID);}
    public function setLoopByKindId() {$this->appendLoopBy(Mobil::LOOP_BY_KIND_ID);}
    public function setLoopByTransmission() {$this->appendLoopBy(Mobil::LOOP_BY_TRANSMISSION);}
}