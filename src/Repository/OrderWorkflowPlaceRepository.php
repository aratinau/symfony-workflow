<?php

namespace App\Repository;

use App\Entity\OrderWorkflowPlace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderWorkflowPlace>
 */
class OrderWorkflowPlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderWorkflowPlace::class);
    }

    public function findPlacesByWorkflow(int $workflowId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.workflow = :workflowId')
            ->setParameter('workflowId', $workflowId)
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?OrderWorkflowPlace
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
