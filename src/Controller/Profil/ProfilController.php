<?php
namespace App\Controller\Profil;

use App\Entity\Friend;
use App\Entity\User;
use App\Entity\Profil;
use App\Form\ProfilType;
use App\Repository\FriendRepository;
use App\Repository\UserRepository;
use App\Repository\ProfilRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    public function verifyProfil($id, $token, UserRepository $repository){
        $profilId = $repository->findId($id);
        $profilToken = $repository->findToken($token);
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
    public function homeProfil(UserRepository $userRepo, ProfilRepository $profilRepo){
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        $user = null;
        $profil = null;
        $filename = null;
        $data = $_SESSION['id'] ?? null;
        $this->session->set('id', $data);
        $username = $userRepo->findUsernameProfil($data);
        $created = $userRepo->findCreatedAtProfil($data);
        if(!$data){
            session_destroy();
            return $this->redirectToRoute("index.registration");
        }
        if((int)$profilRepo->findJoinProfil($data) !== 0){
            $tab = $profilRepo->findJoinProfil($data);
            $user = (int)end($tab)['profil_id'];
            $profil = $profilRepo->find($user);
            $filename = $profil->getFilename();
            $friends = $this->userRepo->findJoinId($user);
        }
        return $this->render("profil/homeprofil.html.twig", [
            'user' => $username,
            'created' => $created,
            'profil' => $profil ?? null,
            'filename' => $filename ?? null,
            'friends' => $friends ?? null
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