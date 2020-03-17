<?php 
namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminController extends AbstractController{
    
    /**
     * @Route("/admin-login", name="index.admin")
     */
    public function adminLogin(AuthenticationUtils $auth){
        $username = $auth->getLastUsername();
        // dd($username);
        // dd($_POST);
        return $this->render("admin/controll.html.twig");
    }

    /**
     * @Route("/control", name="index.control")
     */
    public function admin(UserRepository $userRepo){
        $users = $userRepo->findAll();
        return $this->render("admin/admin.html.twig", [
            'users' => $users
        ]);
    }

}