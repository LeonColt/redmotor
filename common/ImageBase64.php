<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 09/07/2017
 * Time: 15:06
 * as single string from db type;image format
 */
class ImageBase64
{
    private $type, $image;
    public function __construct(?string $single = null)
    {
        if($single !== null) {
            $temp = explode(";", $single);
            $this->type = $temp[0];
            $this->image = $temp[1];
        }
    }

    public function getType() : string{return $this->type;}
    public function setType(string $type){$this->type = $type;}
    public function getImage() : string{return $this->image;}
    public function setImage(string $image){$this->image = $image;}
    public function __toString() : string{return $this->type.";".$this->image;}
}