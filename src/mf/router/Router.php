<?php
namespace mf\router;

use tweeterapp\control\TweeterController;
use mf\auth\Authentification;

 class Router extends AbstractRouter {


    public function __construct(){

        parent::__construct();

    }

            //----------------xxxxxxxxxxx
    public function addRoute($name, $url, $ctrl, $mth, $niveau){

        self::$routes[$url] = [ $ctrl, $mth, $niveau ] ;
        self::$aliases[$name] = $url; 

    }


    public function setDefaultRoute($url){
        self::$aliases['default'] =  $url; 
    }

        //--------------xxxxxxxxxxxxxx
     public function run(){
        $auth = new Authentification();
        if(isset(self::$routes[$this->http_req->path_info]) && $auth->checkAccessRight(self::$routes[$this->http_req->path_info][2])){
            

                $cntrl = new self::$routes[$this->http_req->path_info][0]();
                $var = self::$routes[$this->http_req->path_info][1];
                echo $cntrl->$var();

            
        }else{
            
            $cntrl = new self::$routes[self::$aliases['default']][0]();
            $var = self::$routes[self::$aliases['default']][1];
            echo $cntrl->$var();
            
        }

     }

     static function executeRoute($alias){
            $cntrl = new self::$routes[self::$aliases[$alias]][0]();
            $var = self::$routes[self::$aliases[$alias]][1];
            echo $cntrl->$var();
     }






     public function urlFor($route_name, $param_list=[]){
        $url = $this->http_req->script_name;
        $url .= self::$aliases[$route_name];

       if(!empty($param_list)){
           $url .= "?";
           foreach($param_list as $key => $val){
               $url .= $key."=".$val."&";
           }
       }

       return $url;

    }





}

?>