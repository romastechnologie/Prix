<?php

namespace App\Controller;

use App\Entity\CategClient;
use App\Entity\Categorie;
use App\Entity\Conditionnement;
use App\Entity\Conditionner;
use App\Entity\ConditionnerCateClient;
use App\Entity\ModeDef;
use App\Entity\Produit;
use App\Entity\SousCategorie;
use App\Entity\Utilisateur;
use App\Form\Drop2Type;
use App\Repository\CategClientRepository;
use App\Repository\CategorieRepository;
use App\Repository\ConditionnementRepository;
use App\Repository\ConditionnerCateClientRepository;
use App\Repository\ConditionnerRepository;
use App\Repository\ModeDefRepository;
use App\Repository\PrixRepository;
use App\Repository\ProduitRepository;
use App\Repository\SousCategorieRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\User;

#[Route('/gprix/main')]
class PrincipalController extends AbstractController
{
    
    #[Route('/main', name: 'principal')]
    public function index(UtilisateurRepository $userRep, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em): Response
    {
        return $this->render('base.html.twig', [
        ]);
    }
    #[Route('/importation/categorie/rodpuit', name: 'importation_general')]
    public function importationCateg(Request $request,ModeDefRepository $modeDefRepository,  ConditionnerCateClientRepository $conditionnerCateClientRepository, CategClientRepository $categClientRepository, EntityManagerInterface $em,ConditionnementRepository $conditionnementRepository, ProduitRepository $produitRe, ConditionnerRepository $conditionnerRepository, CategorieRepository $categorieRepository, SousCategorieRepository $sousCategorieRepository,PrixRepository $prixRepository){
        $dropForm = $this->createForm(Drop2Type::class);
        $dropForm->handleRequest($request);
        if($dropForm->isSubmitted()){
            $fichier = $dropForm->get("dropFile")->getData();
            $type  = $dropForm->get("sel")->getData();
            $nomFi = md5(uniqid()) . '.'. $fichier->guessExtension();
             $fichier->move(
                 $this->getParameter('import_dir') ,
                 $nomFi
             );
             set_time_limit(0);
             $fichier = $this->getParameter('import_dir').'/'.$nomFi;
             $handle = fopen($fichier,"r");
             while(($raw_string = fgets($handle)) !== false) {
                
                 $row = str_getcsv($raw_string,';');
                 if($type == "Catégorie"){
                    $codeCate = $row[0];
                    $libelCat = $row[1];
                    $categorie = $categorieRepository->findOneBy(["libelle"=>$libelCat,"code"=>$codeCate]);
                    if(!$categorie){
                        $categorie = new Categorie();
                        $categorie->setCode($codeCate)->setLibelle($libelCat);
                        $categorieRepository->save($categorie,true);
                    }
                 }

                 if($type == "Sous Catégorie"){
                    $codeSous = $row[0];
                    $libelSous = $row[1];
                    $codeCate = $row[2];
                    $categorie = $categorieRepository->findOneBy(["code"=>$codeCate]);
                    $sousCat = $sousCategorieRepository->findOneBy(["libelle"=>$libelSous, "code"=>$codeSous, "categorie"=>$categorie]);
                    if(!$sousCat){
                        $sousCat = new SousCategorie();
                        $sousCat->setCode($codeSous)->setLibelle($libelSous)->setCategorie($categorie);
                        $sousCategorieRepository->save($sousCat,true);
                    }
                 }

                 if($type == "Conditionnement"){
                    $codeCond = $row[0];
                    $libelCond = $row[1];
                    $qte = $row[2];
                    $conditionnement = $conditionnementRepository->findOneBy(["code"=>$codeCond, "libelle"=>$libelCond]);
                    if(!$conditionnement){
                        $conditionnement = new Conditionnement();
                        $conditionnement->setCode($codeCond)->setLibelle($libelCond)->setQte($qte);
                        $conditionnementRepository->save($conditionnement,true);
                    }
                 }

                 if($type == "Produit"){
                    $codeCond = $row[2];
                    $conditionnement = $conditionnementRepository->findOneBy(["code"=>$codeCond]);
                    $designation = $row[1];
                    $conditionner = null;
                    $produit = $produitRe->findOneBy(["designation"=>$designation]);
                    if(!$produit){
                        $produit = new Produit();
                        $produit->setDesignation($designation)->setATaxe(0)->setMode($modeDefRepository->find(0));
                        $produitRe->save($produit,true);
                        $conditionner = $conditionnerRepository->findOneBy(["produit"=>$produit, "conditionnement"=>$conditionnement]);
                        if(!$conditionner){
                            $conditionner = new Conditionner();
                            $conditionner->setPrixAchat(0)
                            ->setProduit($produit)
                            ->setConditionnement($conditionnement)
                            ->setPrixRevient(0)
                            ->setPrixMin(0)
                            ->setPrixMax(0)
                            ->setQteProduit($conditionnement->getQte())
                            ->setPrixVente(0);
                            $conditionnerRepository->save($conditionner,true);
                        }
                    }else{
                        continue;
                    }

                   // dd($produit,$conditionnement,$conditionner);
                 }

             }
             fclose($handle);

             return new JsonResponse('Enregistrement effectué');
        }
        return $this->renderForm('importation/impor.html.twig',[
            'drop'=> $dropForm
        ]);
        //dd($val);
    }


    #[Route('/importation/test', name: 'principal_importation')]
    public function importationProduit(ModeDefRepository $modeDefRepository,  ConditionnerCateClientRepository $conditionnerCateClientRepository, CategClientRepository $categClientRepository, EntityManagerInterface $em,ConditionnementRepository $conditionnementRepository, ProduitRepository $produitRe, ConditionnerRepository $conditionnerRepository, CategorieRepository $categorieRepository, SousCategorieRepository $sousCategorieRepository,PrixRepository $prixRepository){
        set_time_limit(0);
        $fichier = $this->getParameter('import_dir').'/produitsConditionners.csv';
        $handle = fopen($fichier,"r");
        while(($raw_string = fgets($handle)) !== false) {
           // dd($raw_string);
            $row = str_getcsv($raw_string,';');
            //dd($row);
            $designation = $row[0];

            $codeCate = $row[10];
            $libelCat = $row[11];
            $categorie = $categorieRepository->findOneBy(["libelle"=>$libelCat,"code"=>$codeCate]);
            if(!$categorie){
                $categorie = new Categorie();
                $categorie->setCode($codeCate)->setLibelle($libelCat);
                $categorieRepository->save($categorie,true);
            }

            $libMod = $row[1];
            $mode = $modeDefRepository->findOneBy(["libelle"=>$libMod]);
            if(!$mode){
                $mode = new ModeDef();
                $mode->setLibelle($libMod);
                $modeDefRepository->save($mode,true);
            }

            $codeSous = $row[8];
            $libelSous =$row[9];
            $sousCat = $sousCategorieRepository->findOneBy(["libelle"=>$libelSous, "code"=>$codeSous, "categorie"=>$categorie]);
            if(!$sousCat){

                $sousCat = new SousCategorie();
                $sousCat->setCode($codeSous)->setLibelle($libelSous)->setCategorie($categorie);
                $sousCategorieRepository->save($sousCat,true);
            }

            $checkProduitExist = $produitRe->findOneBy(["designation"=>$designation, "sousCategorie"=> $sousCat]);

            if(!$checkProduitExist){
                $checkProduitExist = new Produit();
                $checkProduitExist->setDesignation($designation)->setATaxe(0)->setMode($mode)->setSousCategorie($sousCat);
                $produitRe->save($checkProduitExist,true);
            }


            $codeCond = substr($row[2],0,3);
            $libelCond = $row[2];
            $conditionnement = $conditionnementRepository->findOneBy(["code"=>$codeCond, "libelle"=>$libelCond]);
            if(!$conditionnement){
                $conditionnement = new Conditionnement();
                $conditionnement->setCode($codeCond)->setLibelle($libelCond);
                $conditionnementRepository->save($conditionnement,true);
            }

            $codeCateCli = substr($row[12],0,3);
            $libelleCateCli = $row[2];
            $checkIfCateExist = $categClientRepository->findOneBy(["code"=>$codeCateCli, "libelle"=>$libelleCateCli]);
            if(!$checkIfCateExist){
                $checkIfCateExist = new CategClient();
                $checkIfCateExist->setCode($codeCateCli)->setLibelle($libelleCateCli);
                $categClientRepository->save($checkIfCateExist,true);
            }

            $conditionner = $conditionnerRepository->findOneBy(["produit"=>$checkProduitExist, "conditionnement"=>$conditionnement]);
            $prixVente =$row[3];
            $prixMin = $row[6];
            $prixMax = $row[7];
            $prixAchat = $row[4];
            $prixRevient = $row[5];
            if(!$conditionner){
                $conditionner = new Conditionner();
                $conditionner->setPrixAchat($prixAchat)
                ->setProduit($checkProduitExist)
                ->setConditionnement($conditionnement)
                ->setPrixRevient($prixRevient)
                ->setPrixMin($prixMin)
                ->setPrixMax($prixMax)
                ->setPrixVente($prixVente);
                $conditionnerRepository->save($conditionner,true);
            }

            $conditionnerCateClient = $conditionnerCateClientRepository->findOneBy(["conditionner"=>$conditionner, "cateClient"=>$checkIfCateExist]);
            $prixVenteCat =$row[13];
            $prixMinCat = 0;
            $prixMaxCat = 0;
            if(!$conditionnerCateClient){
                $conditionnerCateClient = new ConditionnerCateClient();
                $conditionnerCateClient->setCateClient($checkIfCateExist)
                ->setConditionner($conditionner)
                ->setPrixMax($prixMaxCat)
                ->setPrixMin($prixMinCat)
                ->setPrixVente($prixVenteCat);
                $conditionnerCateClientRepository->save($conditionnerCateClient,true);
            }
        }
        
        fclose($handle);
        return $this->redirectToRoute('produi_liste');
        //dd($val);
    }
}
