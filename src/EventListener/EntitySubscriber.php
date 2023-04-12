<?php

namespace App\EventListener;

use App\Entity\Conditionner;
use App\Entity\ConditionnerCateClient;
use App\Entity\Prix;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Events;
use App\Entity\Produit;
use App\Repository\PrixRepository;
use App\Repository\ProduitRepository;
use DateTime;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\DateTime as ConstraintsDateTime;

ini_set('xdebug.max_nesting_level', 9999);
class EntitySubscriber implements EventSubscriberInterface
{
    //private $security;
    private $formFactory;
    private $requestStack;
    private $parameterBag;
    private $em;
    private $prixRepository;
    private $produitRepository;
    public function __construct(
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory,
        RequestStack $requestStack,
        PrixRepository $prixRe,
        ParameterBagInterface $parameterBag , ProduitRepository $produitR)
    {
        $this->produitRepository = $produitR;
        $this->prixRepository = $prixRe;
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
        $this->parameterBag = $parameterBag;
        $this->em = $em;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::postPersist,
            Events::postRemove,
            Events::preUpdate,
            Events::postUpdate,
            Events::preFlush,
            Events::postFlush,
            Events::onFlush,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        $em = $args->getEntityManager();

        if($entity instanceof Produit)
        {
            $codeProd = "";
            // $lastProd = $this->produitRepository->findLastProd();
            // $nom = $entity->getDesignation();
            // $nomTab = explode(" ",trim( $nom));
            // $cpt = 1;
            // foreach($nomTab as $t){
            //    // dump("dedans");
            //     preg_match_all('/[0-9]+/',$t, $res);
            //     preg_match_all('/[0-9]+/',$codeProd, $res1);
            //     if($res[0] != []){
            //         // dump($res[0],"Rentrer");
            //         $codeProd .= strtoupper(trim($t));
            //         break;
            //     }else{
            //         if($cpt <= 2 && $res1[0] == [] ){
            //             $codeProd .= strtoupper(trim(substr($t, 0, 3)));
            //         }else{
            //             continue;
            //         }
            //     }
            //     $cpt++;
            // }
            // preg_match_all('/[0-9]+/',$t, $resCo);
            // if($resCo[0] == []){
            //     $codeProd = "";
            //     foreach($nomTab as $t){
            //         $codeProd .= strtoupper(trim(substr($t, 0, 3)));
            //     }
            // }
            $codeProd .= $entity->getSousCategorie()->getCode(); 
            $last = $this->produitRepository->getLastProdOfSous($entity->getSousCategorie());
            $lastCode = "";
            $num = 0;
            if($last){
                $lastCode = $last->getCode();
                $num = (int)substr($lastCode, 4, 4);
                $num += 1;
            }else{
                $num = 1;
            }
            $ref = sprintf($codeProd.'%s', str_pad($num , "4", "0", STR_PAD_LEFT));

            $entity->setCode($ref);  
        }
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        $em = $args->getEntityManager();
        if($entity instanceof Produit)
        {
            /** @var Produit $lastordreFacture */
           // dd($entity);
            //$entity-> setRefFac(sprintf('FA%s', str_pad((int)($entity->getId()), "10", "0", STR_PAD_LEFT)));
            $em->flush();
        }


        if($entity instanceof Conditionner ){
            $prix = new Prix();
            //dd($entity);
                $prix->setDateFin(NULL)->setDateAttribution(new DateTime())
                ->setEstActif(0)->setConditionner($entity)
                ->setPrixMin($entity->getPrixMin() == NULL ? 0 :$entity->getPrixMin())
                ->setPrixVente($entity->getPrixVente())
                ->setPrixMax($entity->getPrixMax() == NULL ? 0 : $entity->getPrixMax())
                ->setPrixConcurentiel($entity->getPrixConcurentiel() == NULL ? 0 : $entity->getPrixConcurentiel())
                ->setPrixAchat($entity->getPrixAchat()== NULL ? 0 :$entity->getPrixAchat())
                ->setPrixRevient($entity->getPrixRevient() == NULL ? 0 : $entity->getPrixRevient());
                $em->persist($prix);
                $em->flush();

            //$em->flush();
        }
        if($entity instanceof ConditionnerCateClient ){
            $prix = new Prix();
                $prix
                ->setConditionnerClient($entity)
                ->setPrixVente($entity->getPrixVente())
                ->setPrixMin($entity->getPrixMin()  == NULL ? 0 :$entity->getPrixMin())
                ->setPrixMax($entity->getPrixMax()  == NULL ? 0 : $entity->getPrixMax());
                $em->persist($prix);
                $em->flush();
            
        }


        if($entity instanceof ConditionnerCateClient ){
            $prix = new Prix();
            //dd($entity);

                $prix->setDateFin(NULL)
                ->setDateAttribution(new DateTime())
                ->setEstActif(1)->setConditionnerClient($entity)
                ->setPrixVente($entity->getPrixVente())
                ->setPrixMin($entity->getPrixMin()  == NULL ? 0 : $entity->getPrixMin())
                ->setPrixMax($entity->getPrixMax()  == NULL ? 0 : $entity->getPrixMax());
                $em->persist($prix);
                $em->flush();            
        }


    }


    public function postRemove(LifecycleEventArgs $args): void
    {
        
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if($entity instanceof Produit){
            $codeD = $entity->getCode();
            if(strpos("$codeD", $entity->getSousCategorie()->getCode()) == false){
                $codeProd = $entity->getSousCategorie()->getCode();
                $last = $this->produitRepository->getLastProdOfSous($entity->getSousCategorie());
                $num = 0;
                if($last){
                    $lastCode = $last->getCode();
                    $num = (int)substr($lastCode, 4, 4);
                    $num += 1;
                }else{
                    $num = 1;
                }
                $ref = sprintf($codeProd.'%s', str_pad($num , "4", "0", STR_PAD_LEFT));
                
                $entity->setCode($ref);
            } 
        }
        $em = $args->getEntityManager();
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        $em = $args->getEntityManager();

        if($entity instanceof Conditionner ){
            $lastPrix = $this->prixRepository->lastPrixConditionnerForConditionment($entity);
            $lastPrix->setDateFin(new DateTime())->setEstActif(0);
            $em->persist($lastPrix);
            $em->flush();
            $prix = new Prix();
                $prix
                ->setEstActif(1)
                ->setConditionner($entity)
                ->setPrixVente($entity->getPrixVente())
                ->setPrixMin($entity->getPrixMin()  == NULL ? 0 : $entity->getPrixMin())
                ->setPrixMax($entity->getPrixMax()  == NULL ? 0 : $entity->getPrixMax())
                ->setPrixConcurentiel($entity->getPrixConcurentiel() == NULL ? 0 : $entity->getPrixConcurentiel())
                ->setPrixAchat($entity->getPrixAchat()== NULL ? 0 :$entity->getPrixAchat())
                ->setPrixRevient($entity->getPrixRevient() == NULL ? 0 : $entity->getPrixRevient());
                $em->persist($prix);
                $em->flush();
        }


        if($entity instanceof ConditionnerCateClient ){
            $lastPrix = $this->prixRepository->lastPrixConditionnerForConditionmentClient($entity);
            $lastPrix->setDateFin(new DateTime());
            $em->persist($lastPrix);
            $em->flush();
            $prix = new Prix();
            //dd($entity);
                $prix
                ->setConditionnerClient($entity)
                ->setPrixVente($entity->getPrixVente())
                ->setPrixMin($entity->getPrixMin()  == NULL ? 0 : $entity->getPrixMin())
                ->setPrixMax($entity->getPrixMax()  == NULL ? 0 : $entity->getPrixMax());
                $em->persist($prix);
                $em->flush();
            
        }
    }

    public function preFlush(PreFlushEventArgs $args): void
    {
        $em = $args->getEntityManager();
    }

    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {

        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {

        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {

        }

        foreach ($uow->getScheduledCollectionDeletions() as $col) {

        }

        foreach ($uow->getScheduledCollectionUpdates() as $col) {

        }
    }

    public function postFlush(PostFlushEventArgs $eventArgs)
    {

    }
}