<?php
namespace App\Controller\User;

use App\Repository\UserRepository;
use App\Repository\FriendRepository;
use App\Repository\ProfilRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Friend extends AbstractController{
    
    private $session;

    protected $container;

    private $userRepo;

    private $profilRepo;

    private $friendRepo;

    public function __construct(SessionInterface $session, 
                                ContainerInterface $container, 
                                UserRepository $userRepo, 
                                ProfilRepository $profilRepo,
                                FriendRepository $friendRepo)
    {
        $this->session = $session;
        $this->container = $container;
        $this->userRepo = $userRepo;
        $this->profilRepo = $profilRepo;
        $this->friendRepo = $friendRepo;
    }

    /**
     * @Route("/user/accept-{id}", name="profil.accept")
     */
    public function accept($id){

    }

    /**
     * @Route("/user/refuse-{id}", name="profil.refuse")
     */
    public function refuse($id){
        
    }
}