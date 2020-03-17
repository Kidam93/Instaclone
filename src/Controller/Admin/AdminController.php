<?php 
namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminController extends AbstractController{

    /**
     * @Route("/login-admin", name="control.admin")
     */
    public function admin(AuthenticationUtils $auth){
        $username = $auth->getLastUsername();
        $errors = $auth->getLastAuthenticationError();
        return $this->render("admin/control.html.twig", [
            'username' => $username,
            'errors' => $errors
        ]);
    }

    /**
     * @Route("/admin", name="index.admin")
     */
    public function adminLogin(UserRepository $userRepo, AuthenticationUtils $auth){
        $users = $userRepo->findAll();
        return $this->render("admin/admin.html.twig", [
            'users' => $users,
        ]);
    }

}