<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;


class ProfilFixtures extends Fixture
{
    public const ADMIN_REFERENCE = 'ADMIN';
    public const APPRENANT_REFERENCE = 'APPRENANT';
    public const FORMATEUR_REFERENCE = 'FORMATEUR';
    public const CM_REFERENCE = 'CM';

    public function load(ObjectManager $manager)
    {
        $profils = ["ADMIN","FORMATEUR","APPRENANT","CM"] ;

        for ($c=0; $c < count($profils) ; $c++) {
            $profil = new Profil() ;
            $profil->setLibelle($profils[$c]) ;
            $profil->setArchivage(0) ;
            if ($profils[$c] == "ADMIN") {
                $this->addReference(self::ADMIN_REFERENCE, $profil);
            }
            elseif ($profils[$c] == "APPRENANT") {
                $this->addReference(self::APPRENANT_REFERENCE, $profil);
            } elseif ($profils[$c] == "CM") {
                $this->addReference(self::CM_REFERENCE, $profil);
            } elseif ($profils[$c] == "FORMATEUR") {
                $this->addReference(self::FORMATEUR_REFERENCE, $profil);
            }


            $manager->persist($profil);

        }
        $manager->flush();
    }
}
