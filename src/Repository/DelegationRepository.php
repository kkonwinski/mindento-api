<?php

namespace App\Repository;

use App\Entity\Delegation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Delegation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Delegation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Delegation[]    findAll()
 * @method Delegation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DelegationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Delegation::class);
    }

    // /**
    //  * @return GetSetDelegationParams[] Returns an array of GetSetDelegationParams objects
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


    public function findEmployeeOnDelegation($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.isFinish=true')
            ->andWhere('d.employee = :employee')
            ->setParameter('employee', $value)
            ->getQuery()
            ->getResult();

    }

    public function findEmployeeDelegations($value)
    {
        return $this->createQueryBuilder('d')
            ->select('d.start','d.end')
            ->addSelect('c.country','c.amountDoe','c.currency')
            ->andWhere('d.employee = :employee')
            ->setParameter('employee', $value)
            ->leftJoin('d.country', 'c')
            ->getQuery()
            ->getResult();

    }

}
