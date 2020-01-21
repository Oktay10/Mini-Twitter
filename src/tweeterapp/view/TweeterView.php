<?php

namespace tweeterapp\view;

use tweeterapp\model\User;

class TweeterView extends \mf\view\AbstractView {
  
    /* Constructeur 
    *
    * Appelle le constructeur de la classe parent
    */
    public function __construct( $data ){
        parent::__construct($data);
    }

    /* MÃ©thode renderHeader
     *
     *  Retourne le fragment HTML de l'entÃªte (unique pour toutes les vues)
     */ 
    private function renderHeader(){
        return '<h1>Twitter</h1>';
    }
    
    /* MÃ©thode renderFooter
     *
     * Retourne le fragment HTML du bas de la page (unique pour toutes les vues)
     */
    private function renderFooter(){
        return 'Twitter app créée en Licence Pro &copy;2018';
    }

    /* MÃ©thode renderHome
     *
     * Vue de la fonctionalitÃ© afficher tous les Tweets. 
     *  
     */
    
    private function renderHome(){

        /*
         * Retourne le fragment HTML qui affiche tous les Tweets. 
         *  
         * L'attribut $this->data contient un tableau d'objets tweet.
         * 
         */

        $router = new \mf\router\Router();        

        $res = "<article class='theme-backcolor2'>";
        $res .= "<h2>Nouveaux Tweets</h2>";
        foreach($this->data as $key => $t){
            $user = User::select()->where('id','=',$t->author)->first();

            $res .= "<div class='tweet'>";
            $res .= "<a href='".$router->urlFor('view',['id' => $t->id])."' class='tweet-text'>$t->text</a>";
            $res .= "<a href='".$router->urlFor('user',['id' => $user->id])."' class='tweet-author'>$user->username</a>";
            $res .= "<p>$t->created_at</p>";
            $res .= "</div>";
        }
        $res .= "</article>";
        
        return $res;
    }
	
  
    /* MÃ©thode renderUeserTweets
     *
     * Vue de la fonctionalitÃ© afficher tout les Tweets d'un utilisateur donnÃ©. 
     * 
     */
     
    private function renderUserTweets(){

        /* 
         * Retourne le fragment HTML pour afficher
         * tous les Tweets d'un utilisateur donnÃ©. 
         *  
         * L'attribut $this->data contient un objet User.
         *
         */

        $router = new \mf\router\Router(); 
		

        if(!is_null($this->data['user'])){
            $res = "<article class='theme-backcolor2'>";
            $res .= "<h2>".$this->data['user']->fullname."</h2>";
            $res .= "<h3>".$this->data['user']->username."</h3>";
            $res .= "<h3>".$this->data['user']->followers." follower</h3>";
            // $res .= "<input class='button input' type='submit' value='Follow' id='signup' name='signup' />";

                foreach($this->data['tweets'] as $key => $t){
                    $res .= "<div class='tweet'>";
                    $res .= "<a href='".$router->urlFor('view',['id' => $t->id])."' class='tweet-text'>$t->text</a>";
                    $res .= "<a href='".$router->urlFor('user',['id' => $this->data['user']->id])."' class='tweet-author'>".$this->data['user']->username."</a>";
                    $res .= "<p>$t->created_at</p>";
                    $res .= "</div>";
                }
            $res .= "</article>";

            return $res;
            }else{
                return;
            }
    }
  
    /* MÃ©thode renderViewTweet 
     * 
     * RrÃ©alise la vue de la fonctionnalitÃ© affichage d'un tweet
     *
     */
    
    private function renderViewTweet(){

        /* MÃ©thode renderViewTweet 
         * 
         * Retourne le fragment HTML qui rÃ©alise l'affichage d'un tweet 
         * en particuliÃ© 
         * 
         * L'attribut $this->data contient un objet Tweet
         *
         */

        $router = new \mf\router\Router(); 

         if(!is_null($this->data)){
            $user = User::select()->where('id','=',$this->data->author)->first();
            $res = "<article class='theme-backcolor2'>";
            $res .= "<div class='tweet'>"; 
                    $res .= "<a href='".$router->urlFor('view',['id' => $this->data->id])."' class='tweet-text'>".$this->data->text."</a>";
                    $res .= "<a href='".$router->urlFor('user',['id' => $user->id])."' class='tweet-author'>".$user->username."</a>";
                    $res .= "<p>".$this->data->created_at."</p>";
                    $res .= "<div class='tweet-footer'><hr>";
                   // $res .= "<p class='tweet-score'>".$this->data->score."</p>";
					// $res .= "<a href='".$router->urlFor('updateFollow')."'>Follow</a>";
                    $res .= "</div>";
            $res .= "</div>";
            $res .= "</article>";
                
            return $res;
         }else{
             return;
         }

        
    }

    private function renderViewSignup(){
        $router = new \mf\router\Router();

        $res = "<form id='tweet-form' class='forms' method='POST' action='".$router->urlFor('checksignup')."'>".       
        "<input class='input' type='text' name='fullname' id='fullname' placeholder='Nom' required /><br>".
        "<input class='input' type='test' name='username' id='username' placeholder='Prénom' required /><br>".
        "<input class='input' type='password' name='password' id='password' placeholder='Mot de passe' required /><br>".
        "<input class='input' type='password' name='rpassword' id='rpassword' placeholder='Confirmer mot de passe' required /><br>".  
        "<input class='button input' type='submit' value='Valider' id='signup' name='signup' /></form>";

        return $res;
    }

    private function renderViewLogin(){
        $router = new \mf\router\Router();
        
        $res = "<form id='tweet-form' class='forms' method='POST' action='".$router->urlFor('checkLogin')."'> ".       
        "<input class='input' type='test' name='username' id='username' placeholder='username' required /><br>".
        "<input class='input' type='password' name='password' id='password' placeholder='password' required /><br>".  
        "<input class='button input' type='submit' value='Valider' id='' name='send' /></form>";

        return $res;
    }


    private function renderFollowers(){

        $router = new \mf\router\Router(); 

        if(!is_null($this->data)){

            $res = "<article class='theme-backcolor2'>";

                foreach($this->data as $key => $t){
                    $res .= "<div class='tweet'>";

                    $res .= "<a href='".$router->urlFor('user',['id' => $t->id])."' class='tweet-author'>".$t->username."</a>";
                    
                    $res .= "</div>";
                }
            $res .= "</article>";

            return $res;
            }else{
                return;
            }

        }

    private function renderViewFormulaire(){
        $router = new \mf\router\Router();
        
        $res = "<form id='tweet-form' class='forms' method='POST' action='".$router->urlFor('send')."'> ".       
        "<textarea name='textarea' id='textarea' placeholder='Quoi de neuf ?'></textarea><br>".  
        "<input class='button input' type='submit' value='Tweeter' id='' name='send' /></form>";

        return $res;
    }

    private function renderBottomMenu(){
        $router = new \mf\router\Router();
        $res = "<div class='tweet'>";
        $res = "<a href='".$router->urlFor('post')."' class=''>Tapez votre Tweet</a>";
        $res .= "</div>";
        return $res;
    }

    private function renderTopMenu(){
        $router = new \mf\router\Router();
        $res = "<a class='button' href='".$router->urlFor('home')."'>Accueil</a>";
        if(isset($_SESSION['user_login'])){
            //$res .= "<a class='button' href='".$router->urlFor('followers')."'>Abonnements</a>";
			//$res .= "<a class='button' href='".$router->urlFor('followers')."'>Abonnés</a>";
            $res .= "<a class='button'>".$_SESSION['user_login']."</a>";
            $res .= "<a class='button' href='".$router->urlFor('logout')."'>Déconnexion</a>";
        }else{
            $res .= "<a class='button' href='".$router->urlFor('login')."'>Se connecter</a>";
            $res .= "<a class='button' href='".$router->urlFor('signup')."'>Inscription</a>";
        }
         
        return $res;
    }



    /* MÃ©thode renderBody
     *
     * Retourne la framgment HTML de la balise <body> elle est appelÃ©e
     * par la mÃ©thode hÃ©ritÃ©e render.
     *
     */
    
    protected function renderBody($selector=null){

        /*
         * voire la classe AbstractView
         * 
         */
         $http_req = new \mf\utils\HttpRequest();

         $res = "";

         $res .= "<header class='theme-backcolor1'>".$this->renderHeader();

         $res .= "<nav id='nav-menu'>";

         $res .= $this->renderTopMenu();

         $res .= "</nav></header>";

         $res .= "<section>";

         if($selector == 'home')
         $res .= "<section>".$this->renderHome()."</section>";
	 
         if($selector == 'userTweets')
         $res .= "<section>".$this->renderUserTweets()."</section>";

         if($selector == 'tweet')
         $res .= $this->renderViewTweet();

         if($selector == 'formulaire')
         $res .= $this->renderViewFormulaire();

         if($selector == 'login')
         $res .= $this->renderViewLogin();

         if($selector == 'followers')
         $res .= $this->renderFollowers();

         if($selector == 'signup')
         $res .= $this->renderViewSignup();

        if(isset($_SESSION['user_login']))
         $res .= $this->renderBottomMenu();

         $res .= "</section>";

         $res .= "<footer class='theme-backcolor1'>".$this->renderFooter()."</footer>";

         return $res;
    }











    
}

?>