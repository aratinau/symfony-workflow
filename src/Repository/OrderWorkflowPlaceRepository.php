<?php

namespace App\Repository;

use App\Entity\OrderWorkflowPlace;
use App\Entity\OrderWorkflowTransition;
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

    public function canTransitionQuery($workflow, $currentPlace, $targetPlace)
    {
        return $this->createQueryBuilder('p')
//            ->innerJoin('p.outgoingTransitions', 'out')
//            ->innerJoin('p.incomingTransitions', 'inc')
            ->andWhere('p.workflow = :workflow')
            ->andWhere('p.id = :currentPlace')
//            ->andWhere('out.toPlace = :targetPlace OR inc.fromPlace = :targetPlace')
            ->setParameter('workflow', $workflow)
            ->setParameter('currentPlace', $currentPlace)
//            ->setParameter('targetPlace', $targetPlace)
            ;

        //outgoingTransitions
        //incomingTransitions
    }

    public function canTransition($workflow, $currentPlace, $targetPlace)
    {
        return $this->canTransitionQuery($workflow, $currentPlace, $targetPlace)
                ->getQuery()
                ->getOneOrNullResult() !== null;
    }

    public function getTransitions($workflow, $currentPlace, $targetPlace)
    {
        return $this->canTransitionQuery($workflow, $currentPlace, $targetPlace)
                ->getQuery()
                ->getResult();
    }

    public function canTransitionTest(OrderWorkflowPlace $currentPlace, OrderWorkflowPlace $targetPlace): bool
    {
        $qbOutgoing = $this->createQueryBuilder('p');
        $qbOutgoing->select('count(t.id)')
            ->from(OrderWorkflowTransition::class, 't')
            ->where('t.fromPlace = :currentPlace')
            ->andWhere('t.toPlace = :targetPlace')
            ->setParameter('currentPlace', $currentPlace)
            ->setParameter('targetPlace', $targetPlace);

        $qbIncoming = $this->createQueryBuilder('p');
        $qbIncoming->select('count(t.id)')
            ->from(OrderWorkflowTransition::class, 't')
            ->where('t.toPlace = :currentPlace')
            ->andWhere('t.fromPlace = :targetPlace')
            ->setParameter('currentPlace', $currentPlace)
            ->setParameter('targetPlace', $targetPlace);

        $countOutgoing = $qbOutgoing->getQuery()->getSingleScalarResult();
        $countIncoming = $qbIncoming->getQuery()->getSingleScalarResult();

        return ($countOutgoing + $countIncoming) > 0;
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
