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
        $directory = dirname(__DIR__, 3) . DS . 'public' . DS . 'images';

        $profil = new Profil();
        $em = $this->getDoctrine()->getManager();
        $file = $form['filename']->getData();
        $someNewFilename = $file->getClientOriginalName();
        //
        $data = explode('.', $someNewFilename);
        $ext = end($data);
        //
        if(in_array($ext, $valid)){
            $file->move($directory, $someNewFilename);
            $this->addFlash('success', 'Article Created!');
        }else{
            $this->addFlash('danger', 'Invalid!');
        }
    }

    public function setFileName($form, $profil){
        $valid = [
            'jpg'
        ];

        $file = $form['filename']->getData();
        $someNewFilename = $file->getClientOriginalName();
        $data = explode('.', $someNewFilename);
        $ext = end($data);
        if(in_array($ext, $valid)){
            return $profil->setFilename($someNewFilename);
        }
        
    }
}