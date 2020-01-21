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


//--------------------
// $query1 = User::select();
// $users = $query1->get();


// foreach($users as $key => $u){
//     echo "<h4>".$u->fullname."</h4>";
// }

// echo "<br><hr><br>";

// $query2 = Tweet::select();
// $tweets = $query2->get();

// foreach($tweets as $key => $t){
//     echo "<h4>".$t->text."</h4>";
// }


//-------------------------------


// $tweets2 = Tweet::select()
//                 ->where('score','>',0)
//                 ->orderBy('updated_at')
//                 ->get();


// foreach($tweets2 as $key => $t){
//     echo "<ul>";
//     echo "<li>$t->id</li>";
//     echo "<li>$t->text</li>";
//     echo "<li>$t->author</li>";
//     echo "<li>$t->score</li>";
//     echo "<li>$t->created_at</li>";
//     echo "<li>$t->updated_at</li>";
//     echo "</ul>";
// }

//---------------------------------------


// $user3 = new User();

// $user3->fullname = 'Mouad MOUNACH';
// $user3->username = 'mounach';
// $user3->password = 'mouad';
// $user3->level = 1000;
// $user3->followers = 0;

// $user3->save();


// $tweet3 = new Tweet();

// $tweet3->text = 'mouad mounach is the best hahahah :)';
// $tweet3->author = 12;

// $tweet3->save();

// print_r($tweet3);


//------------------------------------------

// $mouad = User::where('username','=','mounach')
//                 ->first();

// $tweet4 = $mouad->tweets()->get();

// foreach($tweet4 as $key => $t){
//     echo "<ul>";
//     echo "<li>$t->id</li>";
//     echo "<li>$t->text</li>";
//     echo "<li>$t->author</li>";
//     echo "<li>$t->score</li>";
//     echo "<li>$t->created_at</li>";
//     echo "<li>$t->updated_at</li>";
//     echo "</ul>";
// }



// $tweet5 = Tweet::where('text','like','mouad%')
//                 ->first();

// $user5 = $tweet5->author()->first();

//     echo "<ul>";
//     echo "<li>$user5->id</li>";
//     echo "<li>$user5->fullname</li>";
//     echo "<li>$user5->username</li>";
//     echo "<li>$user5->password</li>";
//     echo "<li>$user5->level</li>";
//     echo "<li>$user5->followers</li>";
//     echo "</ul>";


//-------------------------------


// $tweet63 = Tweet::where('id','=',63)
//                 ->first();

// $user63 = $tweet63->likedBy()->get();

// foreach($user63 as $key => $u){
//     echo "<ul>";
//     echo "<li>$u->id</li>";
//     echo "<li>$u->fullname</li>";
//     echo "<li>$u->username</li>";
//     echo "<li>$u->password</li>";
//     echo "<li>$u->level</li>";
//     echo "<li>$u->followers</li>";
//     echo "</ul>";
// }


// $user7 = User::where('id','=',10)
//                 ->first();

// $tweets3 = $user7->liked()->get();

// foreach($tweets3 as $key => $t){
//     echo "<ul>";
//     echo "<li>$t->id</li>";
//     echo "<li>$t->text</li>";
//     echo "<li>$t->author</li>";
//     echo "<li>$t->score</li>";
//     echo "<li>$t->created_at</li>";
//     echo "<li>$t->updated_at</li>";
//     echo "</ul>";
// }




// $user6 = User::where('id','=',10)
//                 ->first();


// // $followers1 = $user6->followedBy()->get();

// $followers1 = $user6->follows()->get();

// foreach($followers1 as $key => $f){
//     echo "<ul>";
//     echo "<li>$f->id</li>";
//     echo "<li>$f->fullname</li>";
//     echo "<li>$f->username</li>";
//     echo "<li>$f->password</li>";
//     echo "<li>$f->level</li>";
//     echo "<li>$f->followers</li>";
//     echo "</ul>";
// }



// $ctrl = new TweeterController();
// echo $ctrl->viewHome();


// /* configuration d'Eloquent (cf partie 1 du projet ) */

// $router = new \mf\router\Router();

// $router->addRoute('maison',
//                   '/home/',
//                   '\tweeterapp\control\TweeterController',
//                   'viewHome');

// $router->setDefaultRoute('/home/');

// /* Après exécution de cette instruction, l'attribut statique $routes et
//    $aliases de la classe Router auront les valeurs suivantes: */

// print_r(Router::$routes);

// print_r(Router::$aliases);

//----

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