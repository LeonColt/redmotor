<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 09/07/2017
 * Time: 12:50
 */
require_once standart_path.'core/Random.php';
require_once standart_path.'dataobject/Mobil.php';
class GetNewCarId
{
    public function execute() {
        $random = new Random();

        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
        do {
            $id = $random->random_number_int(0);
            $car = new Mobil($id);
            $car->appendLoadBy(Mobil::LOAD_BY_ID);
            $runner->clearQueryArrayArray();
            $car->setRunner($runner);
        } while($car->load());
        return $id;
    }
}