<?php 
namespace App\Controller\User;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class User extends AbstractController{
    

    /**
     * @Route("/user/search-{id}", name="profil.user")
     */
    public function user($id){

        return $this->render("user/user.html.twig");
    }
}