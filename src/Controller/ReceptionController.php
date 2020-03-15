<?php
namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\Inscription\InsertNewUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Notification\SendInscription;
use App\Controller\Verified\RegistrationControl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReceptionController extends AbstractController{

    private $manager;

    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }
    
    /**
     * @Route("/", name="index.registration")
     */
    public function registration(Request $request, UserRepository $repository, \Swift_Mailer $mailer){
        if(!empty($_POST)){
            $username = $request->request->get('username');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $password_confirmed = $request->request->get('password_confirmed');
            $registrationControl = new RegistrationControl($username, $email, $password, $password_confirmed);
            $isValid = $registrationControl->isValid();
            $notUser = $registrationControl->notUser($request, $repository);
            if(empty($isValid) && empty($notUser)){
                $token = SendInscription::generateToken(60);
                //INSERTION BDD
                $insertUser = new InsertNewUser($username, $email, $password, $token);
                $id = $insertUser->insertUser($request, $this->manager);
                SendInscription::sendMail($mailer, $token, $id);
            }
        }
        return $this->render("reception/registration.html.twig", [
            'messages' => $isValid ?? null,
            'errors' => $notUser ?? null
        ]);
    }

    /**
     * @Route("/login", name="index.login")
     */
    public function login(Request $request, UserRepository $repository){
        $username = $request->request->get('username');
        $password = $request->request->get('password');
        $idBDD = $repository->findIdUsername($username);
        $usernameBDD = $repository->findUsername($username);
        $hashBDD = $repository->findPassword($username);
        $encoded = password_verify($password, $hashBDD);
        if($_POST){
            if($username === $usernameBDD && $encoded === true){
                if(session_status() === PHP_SESSION_NONE){
                    session_start();
                }
                $_SESSION['id'] = $idBDD;
                return $this->redirectToRoute("homeprofil");
            }else{
                if(!empty($_SESSION['id'])){
                    session_destroy();
                }
                return $this->redirectToRoute("index.login");
            }
        }
        return $this->render("reception/connexion.html.twig", [
            
        ]);
    }

    /**
     * @Route("/deconnexion", name="index.disconnected")
     */
    public function logout(){
        session_start();
        $_SESSION['id'] = null;
        return $this->redirectToRoute("index.registration");
    }
}