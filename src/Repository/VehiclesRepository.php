<?php

namespace App\Repository;

use App\Entity\Vehicles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vehicles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicles[]    findAll()
 * @method Vehicles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehiclesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicles::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Vehicles $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Vehicles $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Vehicles[] Returns an array of Vehicles objects
     */
    public function getModels()
    {
        return $this->createQueryBuilder('v')
            ->select('v.model')
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    public function getBrands()
    {
        return $this->createQueryBuilder('v')
            ->select('v.brand')
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    public function getEnergy()
    {
        return $this->createQueryBuilder('v')
            ->select('v.energy')
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    public function getMaxPrice()
    {
        return $this->createQueryBuilder('v')
            ->select('MAX(v.price)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getMinPrice()
    {
        return $this->createQueryBuilder('v')
            ->select('MIN(v.price)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getMaxPriceMonthly()
    {
        return $this->createQueryBuilder('v')
            ->select('MAX(v.price_monthly)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getMinPriceMonthly()
    {
        return $this->createQueryBuilder('v')
            ->select('MIN(v.price_monthly)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
