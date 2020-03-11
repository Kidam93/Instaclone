<?php
namespace App\Controller\Inscription;

use DateTime;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InsertNewUser extends AbstractController{
    
    private $username;
    private $email;
    private $password;
    private $token;

    public function __construct($username = null, $email = null, $password = null, $token = null){
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->token = $token;
    }

    public function insertUser(Request $request, EntityManagerInterface $manager){
        $username = $request->request->get('username');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $user = new User();
        $user->setUsername($this->username)
                ->setEmail($this->email)
                ->setPassword(password_hash($this->password, PASSWORD_BCRYPT))
                ->setToken($this->token)
                ->setCreatedToken(new DateTime())
                ->setCreatedAt(new DateTime());
        $manager->persist($user);
        $manager->flush();
        return $user->getId();
    }
}