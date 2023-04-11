<?php

namespace App\Repository;

use App\Entity\CategClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategClient>
 *
 * @method CategClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategClient[]    findAll()
 * @method CategClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategClient::class);
    }

    public function save(CategClient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CategClient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findCategorieByValues($id, $libelle, $code)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id != :id')
            ->setParameter('id', $id)
            ->andWhere('c.code = :code')
            ->setParameter('code', $code)
            ->andWhere('c.libelle = :libelle')
            ->setParameter('libelle', $libelle)
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return CategClient[] Returns an array of CategClient objects
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

//    public function findOneBySomeField($value): ?CategClient
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
