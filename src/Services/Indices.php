<?php

namespace App\Services;

use App\Entity\Conditionner;
use App\Entity\ConditionnerCateClient;
use App\Entity\Produit;
use App\Entity\Prix;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class Indices
{
    private $em;
    private $sec;
    public function __construct( EntityManagerInterface $entityManager, Security $security){
        $this->em = $entityManager;
        $this->sec = $security;
    }

    public function conditionnerClient($id){
        $produit =  $this->getDoctrine()->getRepository(Produit::class)->find($id);
        //$result = [];

        $cons = $this->getDoctrine()->getRepository(Conditionner::class)->findBy(['produit'=> $produit]);
            $condition = [];
            if($cons){
                foreach($cons as $co){
                    $prix = $this->getDoctrine()->getRepository(Prix::class)->findOneBy(["conditionner"=>$co, "estActif"=> 1]);
                    // dd($prix->getPrixMax());
                    $prX = [];
                    if($prix){
                        $prX = array(
                            "prixMax"=> $prix->getPrixMax(),
                            "prixMin"=> $prix->getPrixMin(),
                            "prixConcu"=> $prix->getPrixConcurentiel(),
                        );
                    }else{
                        $prX = array(
                            "prixMax"=> 0,
                            "prixMin"=> 0,
                            "prixConcu"=> 0,
                        );
                    }
                    
                    $condition[] = [
                        "constionnement"=>$co->getConditionnement()->getLibelle(),
                        "prixMax"=> $co->getPrixMax(),
                        "prixMin"=> $co->getPrixMin(),
                        "prixConcu"=>$co->getPrixConcurentiel(),
                        'prix'=>$prX
                    ];
                   // dd($prix->getPrixMax());
                 }
            }

            $conClients = array();
            foreach($cons as $c){
                $condParClient = $this->getDoctrine()->getRepository(ConditionnerCateClient::class)->findBy(["conditionner"=>$c]);
                //dd($condParClient);
                $conClients[] = array(
                    "conditionnement"=>$c,
                    "condClients"=>$condParClient
                );
            }
            
            $res1[] = array(
                'id'=>$produit->getId(),
                'code'=>$produit->getCode() ,
                'designation'=>$produit->getDesignation(),
                'description'=>$produit->getDescription(),
                'refUsi'=>$produit->getRefUsine(),
                'sousCategorie'=>$produit->getSousCategorie()->getLibelle(),
                'categorie'=>$produit->getSousCategorie()->getCategorie()->getLibelle(),
                'mode'=>$produit->getMode()->getLibelle(),
                'conditionners'=>$condition,
                'conditionnersParCategClients'=>  $conClients,
            );

        return $res1;
    }

    public function getUser(){
        $user = $this->sec->getUser(); 
        return $user;
    }

    public function conditionnerProduit($id){
        
    }
}