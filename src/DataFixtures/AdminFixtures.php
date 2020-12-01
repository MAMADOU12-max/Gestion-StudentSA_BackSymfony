<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Admin;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR') ;

        $user = new Admin() ;
         $user ->setEmail ($faker->email);
        $password = $this->encoder->encodePassword($user, 'pass_1234') ;
         $user ->setFirtname($faker->name);
         $user ->setLastname($faker->lastName);
        // $user ->setPhone(223666);
        $user ->setUsername($faker->userName);
        // $user ->setAddress($faker->address);
         $user->setArchivage(false) ;
        $user->setPassword($password) ;
        $user->setProfil($this->getReference(ProfilFixtures::ADMIN_REFERENCE));
        $manager->persist($user);

        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            ProfilFixtures::class,
        );
    }
}
