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
        $myFriend = (int)$this->userRepo->findMyFriend($profil->getId())[0]['user_id'];
        $myProfil = 0;
        $isFriend = 0;
        $add = false;
        $existing = false;
        $isRelation = false;
        //
        if(!empty($this->friendRepo->findFriendId($id))){
            if(!empty($this->friendRepo->findFriendUser($myId))){
                $existing = true;
                if(!empty($this->friendRepo->findIsFriend((int)$id, $myId))){
                    $isFriend = (int)$this->friendRepo->findIsFriend((int)$id, $myId)[0]['is_friend'];
                }
            }
        }
        if((int)$this->userRepo->findMyProfil($myId, $myFriend) !== 0){
            $myProfil = (int)$this->userRepo->findMyProfil($myId, $myFriend)[0]['user_id'];
            if(!empty($myProfil)){
                $existing = true;
                $add = true;
            }
        }
        $profilId = (int)$this->userRepo->selectProfil($myId)[0]['profil_id'];
        if(!empty($this->userRepo->isRelation($myFriend, $profilId))){
            $isRelation = (bool)$this->userRepo->isRelation($myFriend, $profilId)[0]['is_friend'];
            if($isRelation === true){
                $isFriend = 1;
            }
        }
        $aff = $this->friendRepo->affFriend($myId, $id, $myFriend, $profilId);
        if(!empty($aff)){
            $aff = (int)$aff[0]['is_friend'];
            if($aff === 1){
                $wallData = $this->userRepo->findWall($myFriend);
                return $this->render("user/friend.html.twig", [
                    'profil' => $profil,
                    'isFriend' => $isFriend ?? null,
                    'existing' => $existing ?? null,
                    'add' => $add ?? null,
                    'myProfil' => $myProfil ?? null,
                    'wallData' => $wallData ?? null
                ]);
            }
        }
        $me = (int)$this->profilRepo->findJoinProfil($myId)[0]['profil_id'];
        if($me === (int)$id){
            return $this->redirectToRoute("homeprofil");
        }
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
        return $this->redirectToRoute("profil.user", [
            'id' => $id
        ]);
    }
}