<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 *
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function save(Client $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Client $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function checkPersonnePhysique($id, $nom,$prenom, $tel){
        $result = $this->createQueryBuilder('c')
            ->andWhere('c.nom = :nom')
            ->setParameter("nom",$nom)
            ->andWhere('c.prenom = :prenom')
            ->setParameter("prenom",$prenom);
        $result = $result->andWhere('c.telephone1 = :tel')
            ->setParameter("tel",$tel);
            if($id != null){
                $result = $result
                ->andWhere('c.id != :id')
                ->setParameter("id",$id);
            }

        return $result = $result->getQuery()
            ->getResult();
        
    }
    public function checkPersonneMoral($id, $rccm,$denomination, $ifu, $raisonSocial){
        $result = $this->createQueryBuilder('c')
            ->andWhere('c.denomination = :denomination')
            ->setParameter("denomination",$denomination)
            ->andWhere('c.rccm = :rccm')
            ->setParameter("rccm",$rccm)
            ->andWhere('c.ifu = :ifu')
            ->setParameter("ifu",$ifu)
            ->andWhere('c.raisonSociale = :raisonSocial')
            ->setParameter("raisonSocial",$raisonSocial);
            if($id != null){
                $result = $result
                ->andWhere('c.id != :id')
                ->setParameter("id",$id);
            }
       return $result = $result->getQuery()
            ->getResult();
        
    }

//    /**
//     * @return Client[] Returns an array of Client objects
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

//    public function findOneBySomeField($value): ?Client
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
