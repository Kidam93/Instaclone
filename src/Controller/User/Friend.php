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
        $myId = $this->session->get('id');
        $profil = $this->profilRepo->findJoinProfil($myId);
        if(!empty($profil)){
            $profil = (int)$profil[0]['profil_id'];
            $myProfilId = $this->profilRepo->find($profil)->getId();
            // Me $myProfilId
            // Friend (int)$id
            $updated = $this->profilRepo->updateFriend($myProfilId, (int)$id);
        }
        return $this->redirectToRoute("homeprofil");
    }

    /**
     * @Route("/user/refuse-{id}", name="profil.refuse")
     */
    public function refuse($id){
        // DEBUG DELETE FRIEND
        $myId = $this->session->get('id');
        $profil = $this->profilRepo->findJoinProfil($myId);
        if(!empty($profil)){
            $profil = (int)$profil[0]['profil_id'];
            $myProfilId = $this->profilRepo->find($profil)->getId();
            $delete = $this->profilRepo->deleteFriend($myProfilId, (int)$id);
        }
        return $this->redirectToRoute("homeprofil");
    }

    /**
     * @Route("/user/delete-{id}", name="profil.delete")
     */
    public function delete($id){
        $myId = $this->session->get('id');
        $profil = $this->profilRepo->findJoinProfil($myId);
        if(!empty($profil)){
            $profil = (int)$profil[0]['profil_id'];
            $myProfilId = $this->profilRepo->find($profil)->getId();
            $friendId = $this->profilRepo->findJoinProfil((int)$id);
            if($friendId){
                $friendId = (int)$friendId[0]['profil_id'];
                $delete = $this->profilRepo->deleteMyFriend($myId, $profil, (int)$id, $friendId);
            }
        }
        // dd($myId, $profil, (int)$id, $friendId);
        return $this->redirectToRoute("homeprofil");
    }
}