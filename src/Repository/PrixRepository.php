<?php

namespace App\Repository;

use App\Entity\Prix;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Prix>
 *
 * @method Prix|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prix|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prix[]    findAll()
 * @method Prix[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrixRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prix::class);
    }

    public function save(Prix $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Prix $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function historiquePrix($conditionner, $produit){
        return $this->createQueryBuilder('p')
        ->innerJoin('p.conditionner','c')
        ->innerJoin('c.produit','pr')
        ->innerJoin('p.conditionnerClient','cClient')
        ->andWhere('c = :val')
        ->setParameter('val', $conditionner)
        ->andWhere('pr = :val1')
        ->setParameter('val1', $produit)
        ->andWhere('cClient IS NULL')
        ->orderBy('p.id', 'DESC')
        ->getQuery()
        ->getResult()
   ;
    }

    public function lastPrixConditionnerForConditionment($conditionnement){
        return $this->createQueryBuilder('p')
            ->andWhere('p.conditionner = :val')
            ->setParameter('val', $conditionnement)
            ->andWhere('p.prixMin != :val2')
            ->setParameter('val2', NULL)
            ->andWhere('p.prixMax != :val3')
            ->setParameter('val3', NULL)
            ->andWhere('p.prixConcurentiel != :val4')
            ->setParameter('val4', NULL)
           ->orderBy('p.id', 'DESC')
           ->setMaxResults(1)
           ->getQuery()
           ->getOneOrNullResult()
       ;
    }
    public function lastPrixConditionnerForConditionmentClient($conditionnerClient){
        return $this->createQueryBuilder('p')
            ->innerJoin('p.conditionnerClient','ccc')    
            ->andWhere('ccc = :val')
            ->setParameter('val', $conditionnerClient)
            ->andWhere('p.prixMin != :val2')
            ->setParameter('val2', NULL)
            ->andWhere('p.prixMax != :val3')
            ->setParameter('val3', NULL)
            ->andWhere('p.prixConcurentiel = :val4')
            ->setParameter('val4', NULL)
            ->andWhere('p.prixAchat = :val5')
            ->setParameter('val5', NULL)
            ->andWhere('p.prixRevient = :val6')
            ->setParameter('val6', NULL)
           ->orderBy('p.id', 'DESC')
           ->setMaxResults(1)
           ->getQuery()
           ->getOneOrNullResult()
       ;
    }

    public function lastPrixConditionnerActif($conditionnement){
        return $this->createQueryBuilder('p')
            ->andWhere('p.condtionner = :val')
            ->setParameter('val', $conditionnement)
            ->andWhere('p.prixMin = :val2')
            ->setParameter('val2', NULL)
            ->andWhere('p.prixMax = :val3')
            ->setParameter('val3', NULL)
            ->andWhere('p.prixConcurentiel = :val4')
            ->setParameter('val4', NULL)
            ->andWhere('p.estActif = :param')
            ->setParameter('param', 1)
           ->orderBy('p.id', 'DESC')
           ->setMaxResults(1)
           ->getQuery()
           ->getOneOrNullResult()
       ;
    }


//    /**
//     * @return Prix[] Returns an array of Prix objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Prix
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
