<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 25/02/2016
 * Time: 12:40
 */
class JSONEncoder
{
    const STATE = "status";
    const MESSAGE = "message";
    const RESULT = "result";
    private $json;
    public function __construct()
    {
        $this->json = array();
        $this->json[JSONEncoder::STATE] = 0;
    }
    public function status_gagal()
    {
        $this->set_status();
    }
    public function status_berhasil()
    {
        $this->set_status(true);
    }
    public function set_status(bool $status = false)
    {
        $this->json[JSONEncoder::STATE] = ($status) ? 1 : 0;
    }
    public function set_message(string $message)
    {
        $this->json[JSONEncoder::MESSAGE] = $message;
    }
    public function set_result($value, $key = null)
    {
        if($key === null)
        {
            $this->json[JSONEncoder::RESULT] = $value;
        }
        else if(is_array($key))
        {
            $this->add( $this->json[JSONEncoder::RESULT], $key, $value);
        }
        else
        {
            $this->json[JSONEncoder::RESULT][$key] = $value;
        }
    }

    private function add(&$arr, $key, $value)
    {
        if(is_array($key))
        {
            for($i = 0; $i < count($key); $i++)
            {
                if(is_array($key[$i]))
                {
                    $this->add($arr[0], $key[$i], $value);
                }
                else
                {
                    $arr[$key[$i]] = $value;
                    break;
                }
            }
        }
        else $arr[$key] = $value;
    }
    public function remove($key)
    {
        unset($this->json[$key]);
    }
    public function show()
    {
        echo json_encode($this->json);
    }
    public function get_json() : string
    {
        return json_encode($this->json);
    }
}