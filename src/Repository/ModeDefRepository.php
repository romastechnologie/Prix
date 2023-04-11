<?php

namespace App\Repository;

use App\Entity\ModeDef;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModeDef>
 *
 * @method ModeDef|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModeDef|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModeDef[]    findAll()
 * @method ModeDef[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeDefRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModeDef::class);
    }

    public function save(ModeDef $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function update(ModeDef $entity, bool $flush = false): void
    {
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function modeUp($id,$libel){
        return $this->createQueryBuilder('m')
           ->andWhere('m.libelle = :libelle')
           ->setParameter('libelle', $libel)
           ->andWhere('m.id != :id')
           ->setParameter('id', $id)
           ->getQuery()
           ->getOneOrNullResult()
       ;
    }

    public function remove(ModeDef $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ModeDef[] Returns an array of ModeDef objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ModeDef
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
