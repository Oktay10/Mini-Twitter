<?php
namespace mf\utils;
// require_once "AbstractHttpRequest.php";

class HttpRequest extends AbstractHttpRequest {


public function __construct(){
    $this->script_name = $_SERVER['SCRIPT_NAME'];
    $this->path_info = (isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/home/');
    $this->root = dirname($_SERVER['SCRIPT_NAME'],1);
    $this->method = $_SERVER['REQUEST_METHOD'];
    $this->get = $_GET;
    $this->post = $_POST;
}




}


?>