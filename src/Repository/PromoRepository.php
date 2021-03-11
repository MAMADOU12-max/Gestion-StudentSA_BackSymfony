<?php

namespace App\Repository;

use App\Entity\Promo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Promo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promo[]    findAll()
 * @method Promo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promo::class);
    }

     /**
      * @return Promo[] Returns an array of Promo objects
      */

    public function promoApprenantAttente()
    {
        return $this->createQueryBuilder('p')
                    ->select('p,a,r')
                    ->leftJoin('p.referentiels', 'r')
                    ->leftJoin('p.apprenants' ,'a')
                    ->andWhere('a.Attente = :val')
                    ->setParameter('val', 1)
                    ->getQuery()
                    ->getResult()
        ;
    }



    public function getPromoRefbyId($id)
    {
        return $this->createQueryBuilder('p')
                    ->select('p,r,a,g,c')
                    ->leftJoin('p.referentiels','r')
                    ->leftJoin('r.grpcompetence','g')
                    ->leftJoin('g.competences','c')
                     ->leftJoin('c.nomCompetence','n')
                    ->andWhere('p.id = :val')
                    ->setParameter('val',$id)
                    ->getQuery()
                    ->getOneOrNullResult()
        ;
    }


    public function getPromoRefbAppreneaAttenteById($id)
    {
        return $this->createQueryBuilder('p')
                    ->select('p,r,a,g,c')
                    ->andWhere('p.id = :val')
                    ->setParameter('val',$id)
                    ->leftJoin('p.referentiels','r')
                    ->leftJoin('r.grpcompetence','g')
                    ->leftJoin('g.competences','c')
                    ->leftJoin('c.nomCompetence','n')
                    ->getQuery()
                    ->getOneOrNullResult()
            ;
    }
}
