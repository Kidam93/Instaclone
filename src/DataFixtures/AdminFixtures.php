<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
// use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

// UserPasswordEncoderInterface $encoder
class AdminFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $admin = new Admin();
        $admin->setUsername('demo');
        $admin->setPassword('demo');
        $plainPassword = 'demo';
        $password = password_hash($plainPassword, PASSWORD_BCRYPT);
        // $encoded = $encoder->encodePassword($admin, $plainPassword);
        $admin->setPassword($password);

        $manager->persist($admin);
        $manager->flush();
    }
}
