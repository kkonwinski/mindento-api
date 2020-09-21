<?php

namespace App\Repository;

use App\Entity\DelegationCountry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DelegationCountry|null find($id, $lockMode = null, $lockVersion = null)
 * @method DelegationCountry|null findOneBy(array $criteria, array $orderBy = null)
 * @method DelegationCountry[]    findAll()
 * @method DelegationCountry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DelegationCountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DelegationCountry::class);
    }

    // /**
    //  * @return DelegationCountry[] Returns an array of DelegationCountry objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DelegationCountry
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
