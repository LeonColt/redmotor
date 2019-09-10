<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 13/04/2017
 * Time: 14:29
 */
require_once standart_path.'core/Session/Session.php';
require_once standart_path.'core/universal_methode.php';
class Logout
{
    public function execute() {
        $session = new Session();
        $session->startSession();
        if($session->isContainByKey(GlobalConst\SessionKey::LOGIN)) {
            $session->clear();
            $session->destroy();
        }
        echo '<script>window.location.href="'.getUrlByRequest().'";</script>';
    }
}