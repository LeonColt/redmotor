<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 06/07/2017
 * Time: 12:18
 */
require_once standart_path.'dataobject/Pemesanan.php';
require_once standart_path.'core/Random.php';
class GetBookId
{
    public function execute() {
        $random = new Random();

        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
        do {
            $id = $random->random_number_int(0);
            $pemesanan = new Pemesanan($id);
            $runner->clearQueryArrayArray();
            $pemesanan->setRunner($runner);
        } while($pemesanan->load());
        return $id;
    }
}