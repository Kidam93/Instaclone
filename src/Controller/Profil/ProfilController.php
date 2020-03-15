<?php
namespace App\Controller\Profil;

use App\Entity\Profil;
use App\Entity\User;
use App\Form\ProfilType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
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
    public function homeProfil(UserRepository $repository){
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        $data = $_SESSION['id'] ?? null;
        $this->session->set('id', $data);
        $username = $repository->findUsernameProfil($data);
        $created = $repository->findCreatedAtProfil($data);
        if(!$data){
            session_destroy();
            return $this->redirectToRoute("index.registration");
        }
        return $this->render("profil/homeprofil.html.twig", [
            'username' => $username,
            'created' => $created
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
        //
        $em->persist($profil);
        //
        if($form->isSubmitted() && $form->isValid()){
            //
            // $em->persist($profil);
            //
            $em->flush();
            return $this->redirectToRoute("homeprofil");
        }
        return $this->render("profil/updatedprofil.html.twig", [
            'form' => $form->createView()
        ]);
    }
}