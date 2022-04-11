<?php

namespace App\Repository;

use App\Entity\Vehicles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
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
    public function getDictionary($field): array
    {
        return $this->createQueryBuilder('v')
            ->select('v.' . $field)
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    /**
     * @return float|int|mixed|string
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getMax($field)
    {
        return $this->createQueryBuilder('v')
            ->select('MAX(v.' . $field . ')')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return float|int|mixed|string
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getMin(string $field)
    {
        return $this->createQueryBuilder('v')
            ->select('MIN(v.' . $field . ')')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getQueryBuilderForPagination(array $params): QueryBuilder
    {
        $query = $this->createQueryBuilder('v');

        if ($params['selectedBrands']) {
            $query->andWhere("v.brand IN(:brands)")
                ->setParameter('brands', array_values($params['selectedBrands']));
        }

        if ($params['selectedModels']) {
            $query->andWhere("v.model IN(:models)")
                ->setParameter('models', array_values($params['selectedModels']));
        }

        if ($params['selectedEnergies']) {
            $query->andWhere("v.energy IN(:energies)")
                ->setParameter('energies', array_values($params['selectedEnergies']));
        }

        if ($params['price']['min']) {
            $query->andwhere('v.price BETWEEN :minPrice AND :maxPrice')
                ->setParameter('minPrice', (int)$params['price']['min'])
                ->setParameter('maxPrice', (int)$params['price']['max']);
        }

        if ($params['priceMonthly']['min']) {
            $query->andwhere('v.price_monthly BETWEEN :minPriceMonthly AND :maxPriceMonthly')
                ->setParameter('minPriceMonthly', (int)$params['priceMonthly']['min'])
                ->setParameter('maxPriceMonthly', (int)$params['priceMonthly']['max']);
        }

        $query->orderBy('v.' . $params['sorting'], $params['direction']);

        return $query;
    }
}
