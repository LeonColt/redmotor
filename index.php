<?php
/**
 * Created by PhpStorm.
 * User: LC
 * Date: 04/04/2017
 * Time: 16:19
 */
require_once 'controller/Gate.php';

$gate = new Gate(filter_input(INPUT_GET, GlobalConst::VAR_REQUEST));
$gate->execute();