<?php

namespace App\Repository;

use App\Entity\WorkerEntity;
use App\Service\WorkerService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WorkerEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkerEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkerEntity[]    findAll()
 * @method WorkerEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkerEntity::class);
    }

    /**
     * @param WorkerService $service
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findTotalOnWorker(WorkerService $service): int
    {
        $query = $this->createQueryBuilder('c')
            ->select('count(c.lastName)')
            ->andWhere('c.lastName LIKE :val')
            ->andWhere('c.firstName LIKE :val')
            ->setParameter('val', '%'.$service->getQuery().'%')
            ->getQuery();
        return $query->getSingleScalarResult() ?? 0;
    }

    /**
     * @param WorkerService $service
     * @return mixed
     */
    public function findListOnWorker(WorkerService $service)
    {
        $query = $this->createQueryBuilder('c')
            ->andWhere('c.lastName LIKE :val')
            ->andWhere('c.firstName LIKE :val')
            ->setParameter('val', '%'.$service->getQuery().'%')
            ->orderBy('c.'.$service->getSort(), $service->getOrder())
            ->setMaxResults($service->getLimit())
            ->setFirstResult($service->getOffset())
            ->getQuery();
        return $query->getResult();
    }

    // /**
    //  * @return WorkerEntity[] Returns an array of WorkerEntity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WorkerEntity
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
