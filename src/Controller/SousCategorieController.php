<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\SousCategorie;
use App\Form\SousCategorieType;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use App\Repository\SousCategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gprix/sous/categorie')]
class SousCategorieController extends AbstractController
{
    #[Route('/sous/categorie', name: 'sous_categorie')]
    public function index(SousCategorieRepository $souR): Response
    {
        $categories = $souR->findAll();
        $res1 = [];
        foreach($categories as $li){
            $res1[] = array(
                'id'=> $li->getId(),
                'code'=> $li->getCode() ,
                'libelle'=> $li->getLibelle(),
                'categorie'=> $li->getCategorie()->getCode()." ".$li->getCategorie()->getLibelle(),
                'cateId'=> $li->getCategorie()->getId(),
            );
        }
        $res = [
            'meta'=>array(

                "page"=> 1,
                "pages"=> 1,
                "perpage"=> -1,
                "sort"=> "asc",
                "field"=> "id"
            ),
            'data'=>$res1
        ];
        return new JsonResponse($res);
    }

    #[Route('/sous/categorie/nouveau', name: 'sous_categorie_nouveau')]
    public function newAjax(Request $request, SousCategorieRepository $sousCategorieRepository)
    {
        
        $sous = new SousCategorie();
        $form = $this->createForm(SousCategorieType::class, $sous);
        if($request->isMethod("POST")){
            
            $code = json_decode($request->request->get("code"));
            $id = json_decode($request->request->get("id"));
            $libelle = json_decode($request->request->get("libelle"));
            $cate = json_decode($request->request->get("categorie"));
            $categorie = $this->getDoctrine()->getRepository(Categorie::class)->find((int)$cate);
            if($id == ""){
                $type = "Ajout";

                $ifSouEx = $sousCategorieRepository ->findSousIfSousCateExist($cate, $code, $libelle, $id);
                if($ifSouEx){
                    return new JsonResponse([$type,"Cette sous catégorie existe déjà Veuillez revoir les informations inscrites"]);
                }

                $sous->setCode($code)->setLibelle($libelle)->setCategorie($categorie);
                $sousCategorieRepository ->save($sous, true);
                $msg = "Enregistrement effectué";
                return new JsonResponse([$type,$msg]);
            }else{
                $type = "Modif";
                $sousC = $sousCategorieRepository->find((int)$id);
                $sousC->setCode($code)->setLibelle($libelle)->setCategorie($categorie);
                $sousCategorieRepository ->save($sousC, true);
                $msg = "Modification effectuée";
                return new JsonResponse([$type,$msg]);
            }
        }
        $form = $this->createForm(SousCategorieType::class, $sous);
        return $this->renderForm('sous_categorie/new.html.twig', [
            'form'=> $form
        ]);
    }

    
    #[Route('/sous/categorie/supprimer', name: 'sous_categorie_delete', methods: ['POST'])]
    public function delete(Request $request,SousCategorieRepository $sousCategorieRepository, ProduitRepository $produitRepository): Response
    {
        
        if ($request->isMethod("POST")) { 
            $id = json_decode($request->request->get("id"));
            $sous = $sousCategorieRepository->find((int)$id);
            if($produitRepository->findOneBy(["sousCategorie"=>$sous])){
                return new JsonResponse(["Erreur","Erreur, ce mode ne peut plus être supprimer"]);
            }
            $sousCategorieRepository->remove($sous, true);
        }

        return new JsonResponse(["Succes",'Supprimer']);
    }
}
