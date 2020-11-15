<?php

/* pour le chargement automatique des classes dans vendor */
require_once 'vendor/autoload.php';

require_once 'src/mf/utils/ClassLoader.php';

$loader = new mf\utils\ClassLoader("src");
$loader->register();

use mf\auth\Authentification;
use tweeterapp\model\User;
use tweeterapp\model\Follow;
use tweeterapp\model\Like;
use tweeterapp\model\Tweet;
use tweeterapp\control\TweeterController;
use tweeterapp\auth\TweeterAuthentification;
use mf\router\Router;




$init = parse_ini_file("conf/config.ini");

$config = [
    'driver'    => $init["type"],
    'host'      => $init["host"],
    'database'  => $init["name"],
    'username'  => $init["user"],
    'password'  => $init["pass"],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '' ];

/* une instance de connexion  */
$db = new Illuminate\Database\Capsule\Manager();

$db->addConnection( $config ); /* configuration avec nos paramètres */
$db->setAsGlobal();            /* visible de tout fichier */
$db->bootEloquent();           /* établir la connexion */

session_start();

$router = new \mf\router\Router();

$router->addRoute('home', '/home/', '\tweeterapp\control\TweeterController', 'viewHome',TweeterAuthentification::ACCESS_LEVEL_NONE);

$router->setDefaultRoute('/home/');

$router->addRoute('view', '/view/', '\tweeterapp\control\TweeterController', 'viewTweet',TweeterAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('user', '/user/', '\tweeterapp\control\TweeterController', 'viewUserTweets',TweeterAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('post', '/post/', '\tweeterapp\control\TweeterController', 'viewFormulaire',TweeterAuthentification::ACCESS_LEVEL_USER);

$router->addRoute('send', '/send/', '\tweeterapp\control\TweeterController', 'createTweet',TweeterAuthentification::ACCESS_LEVEL_USER);

$router->addRoute('login', '/login/', '\tweeterapp\control\TweeterAdminController', 'viewLogin',TweeterAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('checkLogin', '/checkLogin/', '\tweeterapp\control\TweeterAdminController', 'checkLogin',TweeterAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('followers', '/followers/', '\tweeterapp\control\TweeterAdminController', 'viewFollowers',TweeterAuthentification::ACCESS_LEVEL_USER);

$router->addRoute('logout', '/logout/', '\tweeterapp\control\TweeterAdminController', 'logout',TweeterAuthentification::ACCESS_LEVEL_USER);

$router->addRoute('signup', '/signup/', '\tweeterapp\control\TweeterAdminController', 'signup',TweeterAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('checksignup', '/checksignup/', '\tweeterapp\control\TweeterAdminController', 'checksignup',TweeterAuthentification::ACCESS_LEVEL_NONE);


$router->run();

//--------

// $http_req = new \mf\utils\HttpRequest();
// echo $http_req->script_name;
// echo $http_req->root;


// echo "<a href='".$router->urlFor('view',['id' => 75])."'>tweet 75</a>";




?>
