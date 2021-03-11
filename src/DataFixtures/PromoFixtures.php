<?php

namespace App\DataFixtures;

use App\Entity\Promo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PromoFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR') ;

        $promo = new Promo() ;
        for ($i=0 ; $i< 4 ; $i++) {

            $promo->setLibelle($faker->name)
                ->setAnnee($faker->dateTime)
                ->setDateDebut($faker->dateTime)
                ->setDateFin($faker->dateTime)
            ;
            $manager->persist($promo);

        } ;
         $manager->flush();
    }
}
