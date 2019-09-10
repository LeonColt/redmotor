<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 04/04/2017
 * Time: 16:44
 */
class ResourceLoader
{
    const CSS = 1;
    const JS = 2;
    const IMAGE = 3;
    const FONT = 4;
    private $name, $type;
    public function __construct($name, $type)
    {
        $this->name = $name;
        $this->type = $type;
    }
    public function loadUrl() {
        if($this->type == ResourceLoader::CSS)
            return standart_url.GlobalConst::CSS_LOCATION.$this->name;
        else if($this->type == ResourceLoader::JS)
            return standart_url.GlobalConst::JS_LOCATION.$this->name;
        else if($this->type == ResourceLoader::IMAGE)
            return standart_url.GlobalConst::IMAGE_LOCATION.$this->name;
        else if($this->type == ResourceLoader::FONT)
            return standart_url.GlobalConst::FONT_LOCATION.$this->name;
    }
    public function loadPath() {

    }
    public function load() {

    }
}