<?php

namespace App\Repository;

use App\Entity\Facture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Facture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facture[]    findAll()
 * @method Facture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facture::class);
    }
	
	/**
	 * Liste des factures selon la periode
	 *
	 * @param $debut
	 * @param $fin
	 * @return float|int|mixed|string
	 */
	public function findByPeriode($debut,$fin)
	{
		return $this->createQueryBuilder('f')
			->addSelect('c')
			->addSelect('m')
			->leftJoin('f.client', 'c')
			->leftJoin('f.monture', 'm')
			->where('f.date BETWEEN :debut AND :fin')
			->setParameters([
				'debut' => $debut,
				'fin' => $fin
			])
			->getQuery()->getResult();
	}
	
	/**
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 * @throws \Doctrine\ORM\NoResultException
	 */
	public function getMontantHTByPeriode($debut, $fin)
	{
		return $this->createQueryBuilder('f')
			->select('SUM(f.montantHt)')
			->where('f.date BETWEEN :debut AND :fin')
			->setParameters([
				'debut' => $debut,
				'fin' => $fin
			])
			->getQuery()->getSingleScalarResult();
	}
	
	/**
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 * @throws \Doctrine\ORM\NoResultException
	 */
	public function getRemiseByPeriode($debut, $fin)
	{
		return $this->createQueryBuilder('f')
			->select('SUM(f.remise)')
			->where('f.date BETWEEN :debut AND :fin')
			->setParameters([
				'debut' => $debut,
				'fin' => $fin
			])
			->getQuery()->getSingleScalarResult();
	}

    // /**
    //  * @return Facture[] Returns an array of Facture objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Facture
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
