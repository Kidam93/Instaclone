<?php
namespace App\Controller\Notification;

class SendInscription{

    private static $path = "http://localhost:8000/";

    public static function generateToken($length){
        $alphabet = "azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN0123456789";
        $shuffle = str_shuffle(str_repeat($alphabet, $length));
        return substr($shuffle, 0, $length);
    }

    public static function sendMail(\Swift_Mailer $mailer, $token, $id){
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo('recipient@example.com')
            ->setBody('Afin de valider votre compte veuiller cliquer sur ce lien : '.
                        self::$path.'profil/'.
                        'id='.$id.'&token='.$token);
        return $mailer->send($message);
    }
}