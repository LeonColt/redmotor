<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 04/04/2017
 * Time: 18:42
 */
class Page
{
    /**
     * Page constructor.
     */
    public function __construct(){}
    public function push($obj) {echo $obj;}
    public function load(string $path) {require $path;}
}