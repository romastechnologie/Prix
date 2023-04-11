<?php

namespace App\Repository;

use App\Entity\ConditionnerCateClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConditionnerCateClient>
 *
 * @method ConditionnerCateClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConditionnerCateClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConditionnerCateClient[]    findAll()
 * @method ConditionnerCateClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConditionnerCateClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConditionnerCateClient::class);
    }

    public function save(ConditionnerCateClient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ConditionnerCateClient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ConditionnerCateClient[] Returns an array of ConditionnerCateClient objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ConditionnerCateClient
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
