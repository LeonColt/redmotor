<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 04/04/2017
 * Time: 16:26
 */
namespace {
    if(!defined('standard_path'))
        define('standart_path', $_SERVER['DOCUMENT_ROOT'].'/redmotor/');

    if(!defined('standart_url'))
        define('standart_url', "http://".$_SERVER['SERVER_NAME'].'/redmotor/');

    if(!defined('name_of_session'))
        define('name_of_session', ('erhlaten_daten_'.bin2hex(openssl_random_pseudo_bytes(7))));

    if(!defined('DEFAULT_PATH')) define('DEFAULT_PATH', $_SERVER['DOCUMENT_ROOT'].'/bengkel/');
    if(!defined('NULL')) define('NULL', 0);
    if(!defined('SUPER_ADMIN')) define('SUPER_ADMIN', 1);
    if(!defined('ADMIN')) define('ADMIN', 2);
    if(!defined('MEMBER')) define('MEMBER', 3);
    if(!defined('INTERVAL_TAHUN_PEMBUATAN_MOBIL')) define('INTERVAL_TAHUN_PEMBUATAN_MOBIL', 20);
    class GlobalConst
    {
        const PAGE = 1;
        const ACTION = 2;
        const FONT_LOCATION = "assets/fonts/";
        const CSS_LOCATION = "assets/css/";
        const JS_LOCATION = "assets/js/";
        const IMAGE_LOCATION = "assets/img/";
        const MYSQL_BIGINT_UNSIGNED_MAX = 18446744073709552000;

        const SUPER_ADMIN = 1;
        const ADMIN = 2;
        const CUSTOMER = 3;
        const CUSTOMER_SERVICE = 4;


        /**
         * VALUE OF "VAR_REQUEST" = "request"-"type"
         */
        const VAR_REQUEST = "do";
        const VAR_CAR = "car";
        const VAR_URL_REDIRECT = "redirect";
    }
}
namespace GlobalConst {
    class DBConst {
        const HOST = "mysql.hostinger.co.id";
        const PORT = 3306;
        const DATABASE = "u673279960_rm";
        const DATABASE_USERNAME = "u673279960_urm";
        const DATABASE_PASSWORD = "19Zj8HsiBhBS";
    }
    class SessionKey {
        const LOGIN = "sssk-24384902384920482394";
        const AHP_RESULT = "sssk-343490894289424";
    }
    class AHPConst {
        const CRITERIAS = array(
            "Dokumen",
            "Mesin",
            "Odometer",
            "Interior",
            "Exterior",
            "Tahun",
            "Harga"
        );
    }
}