<?php
namespace mf\auth;

use mf\auth\exception\AuthentificationException;
use mf\auth\AbstractAuthentification;

class Authentification extends AbstractAuthentification {

    public function __construct(){
        if(isset($_SESSION['user_login'])){
            $this->user_login = $_SESSION['user_login'];
            $this->access_level = $_SESSION['access_level'];
            $this->logged_in = true;
        }else{
            $this->user_login = NULL;
            $this->access_level = self::ACCESS_LEVEL_NONE;
            $this->logged_in = false;
        }

    }

    public function updateSession($username,$level){
            $this->user_login = $username;
            $this->access_level = $level;
            $_SESSION['user_login'] = $username;
            $_SESSION['access_level'] = $level;
            $this->logged_in = true;
    } 

    public function logout(){
        unset($_SESSION['user_login']);
        unset($_SESSION['access_level']);
        $this->user_login = NULL;
        $this->access_level = self::ACCESS_LEVEL_NONE;
        $this->logged_in = false;
    }



    public function checkAccessRight($requested){
        if($requested > $this->access_level){
            return false;
        }else{
            return true;
        }
    }

    public function login($username,$db_pass,$pass,$level){
        if($this->verifyPassword($pass,$db_pass)){
            $this->updateSession($username,$level);
            return true;
        }else{
            throw new AuthentificationException('mot de passe ne corespond pas au hache');
            return false;
        }
    }


    public function hashPassword($password){
        return password_hash($password,PASSWORD_DEFAULT);
    }

    public function verifyPassword($password, $hash){
        return password_verify($password,$hash);
    }


}

?>