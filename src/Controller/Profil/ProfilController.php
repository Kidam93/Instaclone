<?php
namespace App\Controller\Profil;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
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
}