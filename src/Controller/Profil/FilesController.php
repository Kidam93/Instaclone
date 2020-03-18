<?php 
namespace App\Controller\Profil;

use App\Entity\Profil;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FilesController extends AbstractController{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function imageUpload($form, Request $request){
        $valid = [
            'jpg'
        ];

        define('DS', DIRECTORY_SEPARATOR);
        $directory = dirname(__DIR__, 3) . DS . 'assets' . DS . 'images';

        $profil = new Profil();
        $em = $this->getDoctrine()->getManager();
        $file = $form['img_profil']->getData();
        $someNewFilename = $file->getClientOriginalName();
        $name = explode('.', $someNewFilename)[0];
        $extension = explode('.', $someNewFilename)[1];
        if(in_array($extension, $valid)){
            $file->move($directory, $someNewFilename);
            // dd($em->persist($profil)); NULL
            $this->addFlash('success', 'Article Created!');
        }else{
            $this->addFlash('danger', 'Invalid!');
        }
    }
}