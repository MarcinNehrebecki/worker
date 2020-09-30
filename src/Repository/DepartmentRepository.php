<?php

namespace App\Repository;

use App\Entity\DepartmentEntity;
use App\Service\DepartmentService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DepartmentEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method DepartmentEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method DepartmentEntity[]    findAll()
 * @method DepartmentEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DepartmentEntity::class);
    }

    /**
     * @param DepartmentService $service
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findTotalOnDepartment(DepartmentService $service): int
    {
        $query = $this->createQueryBuilder('c')
            ->select('count(c.name)')
            ->andWhere('c.name LIKE :val')
            ->setParameter('val', '%'.$service->getQuery().'%')
            ->getQuery();
        return $query->getSingleScalarResult() ?? 0;
    }

    /**
     * @param DepartmentService $service
     * @return mixed
     */
    public function findListOnDepartment(DepartmentService $service)
    {
        $query = $this->createQueryBuilder('c')
            ->andWhere('c.name LIKE :val')
            ->setParameter('val', '%'.$service->getQuery().'%')
            ->orderBy('c.'.$service->getSort(), $service->getOrder())
            ->setMaxResults($service->getLimit())
            ->setFirstResult($service->getOffset())
            ->getQuery();
        return $query->getResult();
    }

    // /**
    //  * @return DepartmentEntity[] Returns an array of DepartmentEntity objects
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
    public function findOneBySomeField($value): ?DepartmentEntity
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
