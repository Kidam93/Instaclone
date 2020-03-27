<?php
namespace App\Controller\Profil\Wall;

use App\Entity\Reputation;
use App\Repository\ProfilRepository;
use App\Repository\ReputationRepository;
use App\Repository\UserRepository;
use App\Repository\WallRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class WallReputationController extends AbstractController{

    const SIZECONTENT = 3;

    private $session;

    private $profilRepo;

    private $userRepo;

    private $reputationRepo;

    private $wallRepo;

    private $em;

    public function __construct(
                                SessionInterface $session,
                                ProfilRepository $profilRepo,
                                UserRepository $userRepo,
                                ReputationRepository $reputationRepo,
                                WallRepository $wallRepo,
                                EntityManagerInterface $em)
    {
        $this->session = $session;
        $this->profilRepo = $profilRepo;
        $this->userRepo = $userRepo;
        $this->reputationRepo = $reputationRepo;
        $this->wallRepo = $wallRepo;
        $this->em = $em;
    }

    /**
     * @Route("/wall/comment-{me}-{friend}-{art}", name="wall.comment")
     */
    public function Comments($me, $friend, $art, Request $request){
        $id = $this->session->get('id');
        if(!empty($this->profilRepo->findJoinProfil($id))){
            $profil = (int)$this->profilRepo->findJoinProfil($id)[0]['profil_id'];
        }
        $comment = $request->request->get('comment');
        // BEN
        // $id = 16 | $profil = 97
        // LAURENCE
        // $friendId = 18 | $friend = 99
        if(!empty($this->userRepo->findMyFriend($friend))){
            $friendId = (int)$this->userRepo->findMyFriend($friend)[0]['user_id'];
        }
        if(strlen($comment) >= self::SIZECONTENT){
            $reputation = new Reputation();
            $reputation->addUser($this->userRepo->find($id))
                ->addProfil($this->profilRepo->find($profil))
                ->addWall($this->wallRepo->find($art))
                ->setComments($comment)
                ->setDate(new DateTime())
                ->setUserId($friendId)
                ->setProfilId($friend);
            $this->em->persist($reputation);
            $this->em->flush();
            return $this->redirectToRoute("profil.user", [
                'id' => $friend
            ]);
        }
    }
}