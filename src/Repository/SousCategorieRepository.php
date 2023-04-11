<?php

namespace App\Repository;

use App\Entity\SousCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SousCategorie>
 *
 * @method SousCategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method SousCategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method SousCategorie[]    findAll()
 * @method SousCategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SousCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SousCategorie::class);
    }

    public function save(SousCategorie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SousCategorie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findSousIfSousCateExist($cate, $code, $libelle, $id)
    {
        $result  = $this->createQueryBuilder('s')
            ->innerJoin("s.categorie","c")
            ->andWhere('s.code = :val1')
            ->setParameter('val1', $code)
            ->andWhere('c.id = :val2')
            ->setParameter('val2', $cate)
            ->andWhere('s.libelle = :val3')
            ->setParameter('val3', $libelle);
            if($id != ""){
                $result = $result->andWhere('s.id != :val4')
                ->setParameter('val4', $id);
            }
        $result = $result->getQuery()
            ->getOneOrNullResult()
        ;
        return $result ;
    }

//    /**
//     * @return SousCategorie[] Returns an array of SousCategorie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SousCategorie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
