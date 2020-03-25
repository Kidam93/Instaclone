<?php 
namespace App\Controller\Profil\Wall;

use App\Entity\Wall;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WallController extends AbstractController{

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
        $directory = dirname(__DIR__, 4) . DS . 'public' . DS . 'walls';

        $wall = new Wall();
        $em = $this->getDoctrine()->getManager();
        $file = $form['file']->getData();
        if($file !== null){
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
    }

    public function setFile($form, $wall){
        $valid = [
            'jpg'
        ];

        $file = $form['file']->getData();
        if($file !== null){
            $someNewFilename = $file->getClientOriginalName();
            $data = explode('.', $someNewFilename);
            $ext = end($data);
            if(in_array($ext, $valid)){
                return $wall->setFile($someNewFilename);
            }
        }
    }
}