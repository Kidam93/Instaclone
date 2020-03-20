<?php 
namespace App\Controller\User;

use App\Repository\UserRepository;
use App\Repository\ProfilRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class User extends AbstractController{
    
    private $session;

    protected $container;

    private $userRepo;

    private $profilRepo;

    public function __construct(SessionInterface $session, 
                                ContainerInterface $container, 
                                UserRepository $userRepo, 
                                ProfilRepository $profilRepo)
    {
        $this->session = $session;
        $this->container = $container;
        $this->userRepo = $userRepo;
        $this->profilRepo = $profilRepo;
    }
    /**
     * @Route("/user/search-{id}", name="profil.user")
     */
    public function user($id){
        // IS FRIEND ?
        
        //
        $profil = $this->profilRepo->find($id);
        return $this->render("user/user.html.twig", [
            'profil' => $profil
        ]);
    }
}