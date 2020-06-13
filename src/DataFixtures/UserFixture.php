<?php

namespace App\DataFixtures;

use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixture extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
         $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $subscriber = new User();
        $subscriber->setEmail('francois@monsite.com');
        $subscriber->setUsername('monuser');
        $subscriber->setRoles(['ROLE_SUBSCRIBER']);
        $subscriber->setPassword($this->passwordEncoder->encodePassword(
            $subscriber,
            'password'
        ));

        $manager->persist($subscriber);

        $admin = new User();
        $admin->setEmail('admin@monsite.com');
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'adminpassword'
        ));

        $manager->persist($admin);

        $manager->flush();
    }
}
