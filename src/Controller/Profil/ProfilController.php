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
            return $this->redirectToRoute("homeprofil");
        }else{
            return $this->redirectToRoute("index.registration");
        }
    }

    /**
     * @Route("/user", name="homeprofil")
     */
    public function homeProfil(UserRepository $repository){
        $data = $this->session->get('id');
        $user = $repository->findUser($data)[0];

        $id = $user->getId();
        $username = $user->getUsername();
        $created = $user->getCreatedAt();
        return $this->render("profil/homeprofil.html.twig", [
            'username' => $username,
            'created' => $created
        ]);
    }
}