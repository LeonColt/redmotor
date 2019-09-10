<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 09/07/2017
 * Time: 12:52
 */
require_once standart_path.'core/Random.php';
require_once standart_path.'dataobject/Pembelian.php';
class GetSellId
{
    public function execute() {
        $random = new Random();

        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
        do {
            $id = $random->random_number_int(0);
            $pembelian = new Pembelian($id);
            $runner->clearQueryArrayArray();
            $pembelian->setRunner($runner);
        } while($pembelian->load());
        return $id;
    }
}