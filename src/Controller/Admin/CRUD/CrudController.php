<?php
namespace App\Controller\Admin\CRUD;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CrudController extends AbstractController{

    /**
     * @return EntityManagerInterface
     */
    private $em;

    /**
     * @return RequestStack
     */
    protected $requestStack;

    public function __construct(EntityManagerInterface $em, RequestStack $requestStack){
        $this->em = $em;
        $this->requestStack = $requestStack;
    }

    /**
     * @Route("/admin/view-{id}", name="admin.view")
     */
    public function view($id, UserRepository $userRepo){
        $user = $userRepo->find($id);
        // dd($user);
        return $this->render("admin/CRUD/admin.view.html.twig", [
            'user' => $user
        ]);
    }

    /**
     * @Route("/admin-{id}", name="admin.edit", methods="GET|POST")
     */
    public function edit($id, UserRepository $userRepo){
        $user = $userRepo->find($id);
        $form = $this->createForm(UserType::class, $user);
        $request = $this->requestStack->getCurrentRequest();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($user);
            $this->em->flush();
            return $this->redirectToRoute("index.admin");
        }
        return $this->render("admin/CRUD/admin.new.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin-{id}", name="admin.delete", methods="DELETE")
     */
    public function delete($id, UserRepository $userRepo){
        $users = $userRepo->find($id);
        $this->em->remove($users);
        $this->em->flush();
        return $this->redirectToRoute("index.admin");
    }

    /**
     * @Route("/admin/new", name="admin.new")
     */
    public function new(UserRepository $userRepo){
        $user = $userRepo->findAll();
        $newUser = new User();
        $form = $this->createForm(UserType::class, $newUser);
        $request = $this->requestStack->getCurrentRequest();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($newUser);
            $this->em->flush();
            return $this->redirectToRoute("index.admin");
        }
        return $this->render("admin/CRUD/admin.new.html.twig", [
            'form' => $form->createView()
        ]);
    }
    
}