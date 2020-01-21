<?php
namespace tweeterapp\control;

use tweeterapp\model\User;
use tweeterapp\auth\TweeterAuthentification;
use mf\router\Router;

class TweeterAdminController extends \mf\control\AbstractController {


    public function __construct(){
        parent::__construct();
    }

    //viewFormulaireLogin
    public function viewLogin(){
        $vue = new \tweeterapp\view\TweeterView('');

        return $vue->render('login');
    }

    public function logout(){
        
        $tweetAuth = new TweeterAuthentification();

        $tweetAuth->logout();

        $route = new Router();
        $route->executeRoute('default');
        
    }

    //checkLogin
    public function checkLogin(){

        if(isset($_POST['username'])){
            
            $tweetAuth = new TweeterAuthentification();
            $route = new Router();

            if($tweetAuth->loginUser($_POST['username'], $_POST['password'])){
 
                    $route->executeRoute('followers');

            }else{
                $route->executeRoute('default');
            }
        }
    }

    public function viewFollowers(){
        $user = User::where('username','=',$_SESSION['user_login'])->first();
                    if(!is_null($user))
                    $followers = $user->followedBy()->get();
                    else{
                     $followers = null;
                    }
         
                    $vue = new \tweeterapp\view\TweeterView($followers);
         
                    return $vue->render('followers');  
    }
	
	public function updateFollow(){
               $route = new Router();
               $vue = new \tweeterapp\model\User();	
        
               if(isset($_GET['id'])){			
                $vue = User::find($_GET['id']);
                $vue->followers = 1;
                $vue->save();
                $route->executeRoute('followers');
            }
           }

    public function signup(){
        
        $vue = new \tweeterapp\view\TweeterView('');

        return $vue->render('signup');
        
    }


    public function checksignup(){
        
        $tweetAuth = new TweeterAuthentification();
        $route = new Router();
                
        if(isset($_POST['username'])){
            if($_POST['password'] == $_POST['rpassword']){
                $username = filter_input(INPUT_POST, "username", FILTER_DEFAULT);
                $password = filter_input(INPUT_POST, "password", FILTER_DEFAULT);
                $fullname = filter_input(INPUT_POST, "fullname", FILTER_DEFAULT);
                if($tweetAuth->createUser($username, $password, $fullname)){
                    $route->executeRoute('login');
                }else{
                    $route->executeRoute('signup');
                }
            }else{
                $route->executeRoute('signup');
            }
                
        }
        
        
    }


}


?>