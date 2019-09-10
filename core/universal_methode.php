<?php
/**
 * Created by PhpStorm.
 * User: LC
 * Date: 04/04/2017
 * Time: 16:51
 */
require_once 'ResourceLoader.php';
/**
 * @param string $name
 * @return string
 */
function load_css_url($name) {
    $loader = new ResourceLoader($name, ResourceLoader::CSS);
    return $loader->loadUrl();
}

/**
 * @param string $name
 * @return string
 */
function load_js_url($name) {
    $loader = new ResourceLoader($name, ResourceLoader::JS);
    return $loader->loadUrl();
}
function load_fonts_url($name) {
    $loader = new ResourceLoader($name, ResourceLoader::FONT);
    return $loader->loadUrl();
}
function load_image_url($name) {
    $loader = new ResourceLoader($name, ResourceLoader::IMAGE);
    return $loader->loadUrl();
}
function getCurrentRequest(){
    return filter_input(INPUT_GET, GlobalConst::VAR_REQUEST);
}
function isCurrentRequestEquals($request) : bool {
    return strcmp(getCurrentRequest(), $request) === 0;
}
function getUrlByRequest($request = "") {
    if( empty($request)) return standart_url;
    else return standart_url."?".GlobalConst::VAR_REQUEST."=".$request;
}
function addGetToUrl($url, $var, $val) {
    if(strcmp($url, standart_url) === 0)
        return $url."?".($var."=".$val);
    else return $url.("&".$var."=".$val);
}
function isSessionLoginValid() : bool {
    require_once standart_path.'core/Session/Session.php';
    require_once  standart_path.'dataobject/User.php';
    $session = new Session();
    $session->startSession();
    return $session->isContainByKey(GlobalConst\SessionKey::LOGIN);
}
function GetCurrentUser() : ?User {
    require_once standart_path.'core/Session/Session.php';
    require_once  standart_path.'dataobject/User.php';
    $session = new Session();
    $session->startSession();
    if(!$session->isContainByKey(GlobalConst\SessionKey::LOGIN)) return null;
    $user = new User();
    $user->unserialize($session->getSessionByKey(GlobalConst\SessionKey::LOGIN));
    return $user;
}

/**
 * index 0 for left key, 1 for right key
 * left and right does not matter, just a habit
 * @return array
 */
function getAHPKey() : array {
    $temp = array();
    $ahp_temp = GlobalConst\AHPConst::CRITERIAS;

    end($ahp_temp);
    $last_key = key($ahp_temp);
    reset($ahp_temp);
    foreach ($ahp_temp as $index => $item) {
        $couple_pointer = $index+1;
        while ($couple_pointer <= $last_key) {
            array_push($temp, array($item, $ahp_temp[$couple_pointer]));
            $couple_pointer++;
        }
    }
    return $temp;
}