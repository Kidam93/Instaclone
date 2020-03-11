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
        return $this->render("reception/registration.html.twig", [
            'messages' => $isValid,
            'errors' => $notUser
        ]);
    }
}