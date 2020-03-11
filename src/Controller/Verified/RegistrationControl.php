<?php

namespace App\Controller\Verified;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class RegistrationControl extends AbstractController{

    private $username;
    private $email;
    private $password;
    private $password_confirmed;

    public function __construct($username = null, $email = null, $password = null, $password_confirmed = null){
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->password_confirmed = $password_confirmed;
    }

    public function isValid(){
        $errors = [];
        if(strlen($this->username) < 3){
            $errors['username'] = "Votre pseudo est trop court";
        }
        if(strlen($this->email) < 3){
            $errors['email'] = "Votre email est trop court";
        }
        if(strlen($this->password) <= 8){
            $errors['password'] = "Votre mot de passe est trop court";
        }
        if(empty($this->password_confirmed)){
            $errors['password_confirmed'] = "Veuillez confirmer votre mot de passe";
        }
        if($this->password_confirmed !== $this->password){
            $errors['not_equal'] = "Vos mots de passes ne correspondent pas";
        }
        return $errors;
    }

    public function notUser(Request $request, UserRepository $userRepository){
        $usernameRequest = $request->request->get('username');
        $emailRequest = $request->request->get('email');
        $usernameBDD = $userRepository->findUsername($usernameRequest);
        $emailBDD = $userRepository->findEmail($emailRequest);
        $errorsUser = [];
        if($usernameBDD){
            $errorsUser['username'] = "Ce pseudo est deja pris";
        }
        if($emailBDD){
            $errorsUser['email'] = "Cet email existe deja";
        }
        return $errorsUser;
    }

}