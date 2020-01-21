<?php
namespace tweeterapp\auth;

use tweeterapp\model\User;
use mf\auth\exception\AuthentificationException;
use mf\auth\Authentification;

class TweeterAuthentification extends \mf\auth\Authentification {

    /*
     * Classe TweeterAuthentification qui dÃ©finie les mÃ©thodes qui dÃ©pendent
     * de l'application (liÃ©e Ã  la manipulation du modÃ¨le User) 
     *
     */

    /* niveaux d'accÃ¨s de TweeterApp 
     *
     * Le niveau USER correspond a un utilisateur inscrit avec un compte
     * Le niveau ADMIN est un plus haut niveau (non utilisÃ© ici)
     * 
     * Ne pas oublier le niveau NONE un utilisateur non inscrit est hÃ©ritÃ© 
     * depuis AbstractAuthentification 
     */
    const ACCESS_LEVEL_USER  = 100;   
    const ACCESS_LEVEL_ADMIN = 200;

    /* constructeur */
    public function __construct(){
        parent::__construct();
    }

    /* La mÃ©thode createUser 
     * 
     *  Permet la crÃ©ation d'un nouvel utilisateur de l'application
     * 
     *  
     * @param : $username : le nom d'utilisateur choisi 
     * @param : $pass : le mot de passe choisi 
     * @param : $fullname : le nom complet 
     * @param : $level : le niveaux d'accÃ¨s (par dÃ©faut ACCESS_LEVEL_USER)
     * 
     * Algorithme :
     *
     *  Si un utilisateur avec le mÃªme nom d'utilisateur existe dÃ©jÃ  en BD
     *     - soulever une exception 
     *  Sinon      
     *     - crÃ©er un nouvel modÃ¨le User avec les valeurs en paramÃ¨tre 
     *       ATTENTION : Le mot de passe ne doit pas Ãªtre enregistrÃ© en clair.
     * 
     */
    
    public function createUser($username, $pass, $fullname,
                               $level=self::ACCESS_LEVEL_USER) {
            try{

                    $userCheck = User::where('username','=',$username)->first();
                    if(isset($userCheck)){
                        if($userCheck->username == $username)
                            throw new AuthentificationException('username already exit !');
                        return false;
                    }else{
                        $newUser = new User();
                        $newUser->username = $username;
                        $newUser->password = password_hash($pass,PASSWORD_DEFAULT);
                        $newUser->fullname = $fullname;
                        $newUser->level = $level;
                        $newUser->followers = 0;
                        $newUser->save();
                        return true;
                    }
            }catch(AuthentificationException $e){

            }
    }

    /* La mÃ©thode loginUser
     *  
     * permet de connecter un utilisateur qui a fourni son nom d'utilisateur 
     * et son mot de passe (depuis un formulaire de connexion)
     *
     * @param : $username : le nom d'utilisateur   
     * @param : $password : le mot de passe tapÃ© sur le formulaire
     *
     * Algorithme :
     * 
     *  - RÃ©cupÃ©rer l'utilisateur avec l'identifiant $username depuis la BD
     *  - Si aucun de trouvÃ© 
     *      - soulever une exception 
     *  - sinon 
     *      - rÃ©aliser l'authentification et la connexion
     *
     */
    
    public function loginUser($username, $password){
                try{

                    $userCheck = User::where('username','=',$username)->first();
                    if(isset($userCheck)){
                        if($userCheck->username != $username){
                            throw new AuthentificationException('username does not exit !');
                            return false;
                        }else{
                            
                            return $this->login($username,$userCheck->password,$password,$userCheck->level);
                        }
                    }
                    
                }catch(AuthentificationException $e){

                }
    }

}