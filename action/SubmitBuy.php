<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 09/07/2017
 * Time: 12:41
 */
require_once standart_path.'dataobject/Mobil.php';
require_once standart_path.'dataobject/Pembelian.php';
require_once standart_path.'core/universal_methode.php';
class SubmitBuy
{
    const INPUT_ID = "id";
    const INPUT_PRICE = "price";
    const INPUT_CAR_ID = "car_id";
    const INPUT_CAR_NAME = "car_name";
    const INPUT_CAR_KIND = "car_kind";
    const INPUT_CAR_TRANSMISSION = "car_transmission";
    const INPUT_CAR_YEAR = "car_year";
    const INPUT_CAR_COLOR = "car_color";
    const INPUT_CAR_ODOMETER = "car_odometer";
    const INPUT_CAR_NENGINE = "car_nengine";
    const INPUT_CAR_NCHASSIS = "car_nchassis";
    const INPUT_CAR_PIC = "car_pic";

    private $id, $price, $car_id, $car_name, $car_kind, $car_transmission, $car_year, $car_color, $car_odometer, $car_nengine, $car_nchassis, $car_pic;
    public function __construct(
        int $id,
        int $price,
        int $car_id,
        string $car_name,
        int $car_kind,
        int $car_transmission,
        int $car_year,
        string $car_color,
        int $car_odometer,
        string $car_nengine,
        string $car_nchassis, $car_pic
    )
    {
        $this->id = $id;
        $this->price = $price;
        $this->car_id = $car_id;
        $this->car_name = $car_name;
        $this->car_kind = $car_kind;
        $this->car_transmission = $car_transmission;
        $this->car_year = $car_year;
        $this->car_color = $car_color;
        $this->car_odometer = $car_odometer;
        $this->car_nengine = $car_nengine;
        $this->car_nchassis = $car_nchassis;
        $this->car_pic = $car_pic;
    }

    public function execute() {
        $car = new Mobil($this->car_id);
        $car->setName($this->car_name);
        $jenis = new JenisMobil($this->car_kind);
        $car->setJenis($jenis);
        $tran = new Transmission($this->car_transmission);
        $car->setTransmission($tran);
        $car->setYear($this->car_year);
        $car->setColor($this->car_color);
        $car->setOdometer($this->car_odometer);
        $car->setNengine($this->car_nengine);
        $car->setNchasis($this->car_nchassis);
        $car->setPrice(0);
        $pic = new ImageBase64();
        $pic->setType(pathinfo($this->car_pic['name'], PATHINFO_EXTENSION));
        $pic->setImage(base64_encode(file_get_contents($this->car_pic["tmp_name"])));
        $car->setPic($pic);

        $user = GetCurrentUser();

        $pembelian = new Pembelian($this->id);
        $pembelian->setCar($car);
        $pembelian->setCustomer($user);
        $pembelian->setPrice($this->price);

        $car->appendLinkTransaction($pembelian, Mobil::LINK_TRANSACTION_ADD, Pembelian::LINK_TRANSACTION_ADD);

        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);

        $car->setRunner($runner);

        $car->add();

        $message = "ID jual : ".$this->id."\n"."ID Mobil : ".$this->car_id;

        if(!mail($user->getEmail(), "Konfirmasi Jual Mobil", $message)) throw new Exception("Email Gagal Dikirim");
    }
}