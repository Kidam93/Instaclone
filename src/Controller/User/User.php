<?php 
namespace App\Controller\User;

use App\Entity\Friend;
use App\Repository\UserRepository;
use App\Repository\FriendRepository;
use App\Repository\ProfilRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class User extends AbstractController{
    
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
     * @Route("/user/search-{id}", name="profil.user")
     */
    public function user($id, Session $session){
        // IS FRIEND ?
        $profil = $this->profilRepo->find($id);
        $myId = $session->get('id');
        $friend = $profil->getId();
        $myFriend = (int)$this->userRepo->findMyFriend($friend)[0]['user_id'];
        $myProfil = 0;
        $add = false;
        if(!empty($this->friendRepo->findFriendId($id))){
            $isFriend = 0;
            $existing = false;
            if(!empty($this->friendRepo->findFriendUser($myId))){
                $isFriend = (int)$this->friendRepo->findIsFriend((int)$id, $myId)[0]['is_friend'];
                $existing = true;
            }
        }
        if((int)$this->userRepo->findMyProfil($myId, $myFriend) !== 0){
            $myProfil = (int)$this->userRepo->findMyProfil($myId, $myFriend)[0]['user_id'];
            if(!empty($myProfil)){
                $existing = true;
                $add = true;
            }
        }
        // dd($existing, $isFriend, $add, $myProfil);
        //
        return $this->render("user/user.html.twig", [
            'profil' => $profil,
            'isFriend' => $isFriend ?? null,
            'existing' => $existing ?? null,
            'add' => $add ?? null,
            'myProfil' => $myProfil ?? null
        ]);
    }

    /**
     * @Route("/user/add-{id}", name="profil.adduser")
     */
    public function add($id, Session $session){
        $userId = $session->get('id');
        $user = $this->userRepo->find($userId);
        $em = $this->getDoctrine()->getManager();
        $friend = new Friend();
        $friend->addUser($user);
        $friend->setUserId($userId)
            ->setFriendId($id)
            ->setIsFriend(0);
        $em->persist($friend);
        $em->flush();
        return $this->redirectToRoute("homeprofil");
    }
}