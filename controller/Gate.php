<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 04/04/2017
 * Time: 16:19
 */
require_once 'core/GlobalConst.php';
class Gate
{
    private $request;
    private $type;

    /**
     * Gate constructor.
     * @param String $request
     */
    public function __construct($request = RequestPageConstant::HOME){
        $temp = explode("-", $request);
        $this->request = $temp[0];
        if(count($temp) == 1) $this->type = GlobalConst::PAGE;
        else $this->type = $temp[1];
    }
    public function execute() {
        switch($this->type) {
            case GlobalConst::PAGE: {
                require_once standart_path.'controller/RequestPage.php';
                $req = new RequestPage($this->request."-".RequestPageConstant::FLAG);
                $req->execute();
            } break;

            case GlobalConst::ACTION : {
                require_once standart_path.'controller/RequestAction.php';
                $req = new RequestAction($this->request."-".RequestActionConstant::FLAG);
                $req->execute();
            } break;

            default : {
                require_once standart_path.'controller/RequestPage.php';
                $req = new RequestPage($this->request."-".RequestPageConstant::FLAG);
                $req->execute();
            }
        }
    }
}