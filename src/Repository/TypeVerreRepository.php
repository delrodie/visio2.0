<?php

namespace App\Repository;

use App\Entity\TypeVerre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeVerre|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeVerre|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeVerre[]    findAll()
 * @method TypeVerre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeVerreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeVerre::class);
    }

    // /**
    //  * @return TypeVerre[] Returns an array of TypeVerre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeVerre
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
