<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function save(Produit $entity, bool $flush = false): void
    {

        $this->getEntityManager()->persist($entity);
        
        if ($flush) {
            
            $this->getEntityManager()->flush();
            //dd($entity);
        }
    }

    public function checkProduit($id,$designation, $sousCat){
        $result =  $this->createQueryBuilder('p')
            ->innerJoin('p.sousCategorie','s')
            ->andWhere('p.designation = :designation')
            ->setParameter('designation', $designation);
            if($id !== null){
                $result = $result->andWhere('p.id != :id')
                    ->setParameter('id', $id);
            }
            $result = $result->andWhere('s = :sousCat')
            ->setParameter('sousCat', $sousCat)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        return $result;
    }

    public function getLastProdOfSous($sousCate){
        $code = "%".$sousCate->getCode()."%";
        $result =  $this->createQueryBuilder('p')
            ->innerJoin('p.sousCategorie','s')
            ->andWhere('s = :sou')
            ->andWhere('s = :sou')
            ->andWhere('p.code LIKE :d')
            ->setParameter('d',$code )
            ->setParameter('sou', $sousCate)
            ->orderBy('p.code', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
            return $result;
    }

    public function update(Produit $entity, bool $flush = false): void
    {
        
        if ($flush) {
            $this->getEntityManager()->flush();
            //dd($entity);
        }
    }

    public function remove(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            dd("OK");
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Produit[] Returns an array of Produit objects
    //     */
       public function findLastProd()
       {
           return $this->createQueryBuilder('p')
               ->orderBy('p.id', 'DESC')
               ->setMaxResults(1)
               ->getQuery()
               ->getOneOrNullResult()
           ;
       }


        public function checkIfProdExist($id, $designation, $sousCat): array
       {
           return $this->createQueryBuilder('p')
                ->innerJoin('p.sousCategorie','s')
               ->andWhere('p.designation = :designation')
               ->setParameter('designation', $designation)
               ->orderBy('p.id', 'ASC')
               ->setMaxResults(10)
               ->getQuery()
               ->getResult()
           ;
       }



    //    public function findOneBySomeField($value): ?Produit
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
