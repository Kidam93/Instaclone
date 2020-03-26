<?php
namespace App\Controller\Profil;

use App\Entity\User;
use App\Entity\Wall;
use App\Entity\Friend;
use App\Entity\Profil;
use App\Form\WallType;
use App\Form\ProfilType;
use App\Repository\UserRepository;
use App\Repository\FriendRepository;
use App\Repository\ProfilRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\Profil\Wall\WallController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController{

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
     * @Route("/profil/id={id}&token={token}", name="verifyprofil")
     */
    public function verifyProfil($id, $token){
        $profilId = $this->repository->findId($id);
        $profilToken = $this->repository->findToken($token);
        if((int)$id === $profilId && $token === $profilToken){
            $this->session->set('id', $profilId);
            if(session_status() === PHP_SESSION_NONE){
                session_start();
            }
            $_SESSION['id'] = $id;
            return $this->redirectToRoute("homeprofil");
        }else{
            session_destroy();
            return $this->redirectToRoute("index.registration");
        }
    }

    /**
     * @Route("/user", name="homeprofil")
     */
    public function homeProfil(Request $request){
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        $user = null;
        $profil = null;
        $filename = null;
        $data = $_SESSION['id'] ?? null;
        $this->session->set('id', $data);
        $username = $this->userRepo->findUsernameProfil($data);
        $created = $this->userRepo->findCreatedAtProfil($data);
        if(!$data){
            session_destroy();
            return $this->redirectToRoute("index.registration");
        }
        if((int)$this->profilRepo->findJoinProfil($data) !== 0){
            $tab = $this->profilRepo->findJoinProfil($data);
            $user = (int)end($tab)['profil_id'];
            $profil = $this->profilRepo->find($user);
            $filename = $profil->getFilename();
            $friends = $this->userRepo->findJoinId($user);
        }
        $aff = $this->friendRepo->findJoinFriend($user, $data);
        $wall = new Wall();
        $userObj = $this->getDoctrine()->getRepository(User::class)->find($this->session->get('id'));
        $form = $this->createForm(WallType::class, $wall);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);
        $wall->addProfilComment($profil);
        $wall->addUserComment($userObj);
        if($form->isSubmitted() && $form->isValid()){
            $dataFiles = new WallController($this->container);
            $dataFiles->imageUpload($form, $request);
            $dataFiles->setFile($form, $wall);
            $em->persist($wall);
            $em->flush();
            return $this->redirectToRoute("homeprofil");
        }
        $wallData = $this->userRepo->findWall($this->session->get('id'));
        return $this->render("profil/homeprofil.html.twig", [
            'user' => $username,
            'created' => $created,
            'profil' => $profil ?? null,
            'filename' => $filename ?? null,
            'friends' => $friends ?? null,
            'affs' => $aff ?? null,
            'wallData' => array_reverse($wallData),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/updated", name="updatedprofil")
     */
    public function updated(Request $request){
        $id = $this->session->get('id');
        $em = $this->getDoctrine()->getManager();
        $profil = new Profil();
        $form = $this->createForm(ProfilType::class, $profil);
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form->handleRequest($request);
        $profil->addUser($user);
        if(!empty($this->profilRepo->findJoinProfil($id))){
            $dataProfil = (int)$this->profilRepo->findJoinProfil($id)[0]['profil_id'];
        }else{
            $dataProfil = null;
        }
        if($form->isSubmitted() && $form->isValid()){ 
            $dataFiles = new FilesController($this->container);
            $dataFiles->imageUpload($form, $request);
            $dataFiles->setFileName($form, $profil);
            //DELETE LAST FIELD
            if($dataProfil !== null){
                $this->profilRepo->deleteProfil_user($dataProfil);
                $this->profilRepo->deleteProfil($dataProfil);
            }
            //
            $em->persist($profil);
            $em->flush();
            return $this->redirectToRoute("homeprofil");
        }
        return $this->render("profil/updatedprofil.html.twig", [
            'form' => $form->createView()
        ]);           
    }

    /**
     * @Route("/user/search", name="searchprofil")
     */
    public function search(Request $request){
        $data = $request->request->get('data');
        $profil = $this->profilRepo->findName($data);
        // dd($profil);
        return $this->render("user/search.html.twig", [
            'profiltabs' => $profil ?? null
        ]);
    }
}