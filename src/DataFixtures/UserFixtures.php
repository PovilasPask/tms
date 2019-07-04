<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setName('Petras Petrauskas');
        $user->setEmail('petras@mail.com');
        $user->setIsBlocked(false);

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'demo'
        ));
        $manager->persist($user);

        $user2 = new User();
        $user2->setName('Admin Admin');
        $user2->setEmail('admin@mail.com');
        $user2->setIsBlocked(false);
        $user2->setRoles(['ROLE_ADMIN']);

        $user2->setPassword($this->passwordEncoder->encodePassword(
            $user2,
            'demo'
        ));
        $manager->persist($user2);

        $manager->flush();
    }
}
