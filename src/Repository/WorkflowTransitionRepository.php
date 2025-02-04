<?php

namespace App\Repository;

use App\Entity\WorkflowTransition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WorkflowTransition>
 *
 * @method WorkflowTransition|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkflowTransition|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkflowTransition[]    findAll()
 * @method WorkflowTransition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkflowTransitionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkflowTransition::class);
    }

    public function add(WorkflowTransition $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WorkflowTransition $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Récupère les transitions possibles pour un état donné.
     */
    public function findTransitionsForState($state): array
    {
        return $this->createQueryBuilder('wt')
            //->where('wt.fromState = :state')
//            ->join('t.fromState = :state')
//            ->setParameter('state', $state)
            ->join('wt.fromState', 'fs')
            ->join('wt.toState', 'ts')
            ->where('fs.name = :stateName OR ts.name = :stateName')
            ->setParameter('stateName', $state)
            ->orderBy('wt.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return WorkflowTransition[] Returns an array of WorkflowTransition objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?WorkflowTransition
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
