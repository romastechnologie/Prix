<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use App\Repository\SousCategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gprix/categorie')]
class CategorieController extends AbstractController
{

    #[Route('/categorie/liste/{id}--update', name: 'update_catege')]
    public function update(Categorie $categ){
        dd($categ);
        return $this->render('/categrie/index.html.twig',[

        ]);
    }

    
    #[Route('/categorie/liste/delete/{id}--', name: 'delete_catege')]
    public function delete(Categorie $categ){
        
        dd($categ);

        return $this->render('/categrie/index.html.twig',[

        ]);
    }

    #[Route('/categorie/liste', name: 'categorie')]
    public function index(CategorieRepository $catR): Response
    {
        $categories = $catR->findAll();
        $res1 = [];
        foreach($categories as $li){
            $res1[] = array(
                'id'=>$li->getId(),
                'code'=>$li->getCode() ,
                'libelle'=>$li->getLibelle(),
                
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
        $result = new JsonResponse($res);
        return $result;
    }

    #[Route('/categorie/new', name: 'new_categ')]
    public function new(Request $request, CategorieRepository $catR)
    {
        $cate = new Categorie();
        $form = $this->createForm(CategorieType::class,$cate);
        $form->handleRequest($request);
        
        return $this->renderForm('categorie/new.html.twig', [
            'form'=>$form
        ]);
    }

    public function checkingCode(Request $request, CategorieRepository $catR){
        $msg = "";
        if($request->isMethod("POST")){
            $code = json_decode($request->request->get("code"));
            if($catR->findBy(["code"=>$code])){
                $mes = "Catégorie existante";
            }
        }
        return new JsonResponse($msg);
    }

    #[Route('/categorie/new/ajax/new', name: 'new_categ_ajax')]
    public function newParAjax(Request $request, CategorieRepository $catR)
    {
        $cate = new Categorie();
        $em = $this->getDoctrine()->getManager();
        if ($request->isMethod("POST")) {
            $code = json_decode($request->request->get("code"));
            $id = json_decode($request->request->get("id"));
            $libelle = json_decode($request->request->get("libelle"));
            $msg ="";
            if($id !== ""){
                $type = "Modif";
               $cat =   $catR->findCategorieByValues($id, $libelle, $code);
               if($cat){
                    $msg = "Un enregistrement porte déjà ce code";
                    return new JsonResponse([$type,$msg]);
                }else{
                    $categ = $catR->find($id);
                    $categ->setCode($code)->setLibelle($libelle);
                    $catR->save($categ,true);
                    $msg = "Modification effectuée";
                    return new JsonResponse([$type,$msg]);
                }
                
            }else{
                $type = "Ajout";
                $catCode = $catR->findOneBy(["code"=>$code]);
                $cateLib =  $catR->findOneBy(["libelle"=>$libelle]);
                if($catCode){
                    $msg = "Un enregistrement porte déjà ce code";
                    return new JsonResponse([$type,$msg]);
                }
                if($cateLib){
                    $msg = "Un enregistrement porte déjà ce libellé";
                    return new JsonResponse([$type,$msg]);
                }
                $cate->setCode($code)->setLibelle($libelle);
                $catR->save($cate,true);
                $msg = "Enregistrement effectué";
                return new JsonResponse([$type,$msg]);
            }
            
        }else{
            return new JsonResponse("Method Erreur");
        }
    }

    #[Route('/categorie/supprimer', name: 'categorie_delete', methods: ['POST'])]
    public function delete2(Request $request,SousCategorieRepository $sousCategorieRepository, CategorieRepository $categorieRepository): Response
    {
        
        if($request->isMethod("POST")) { 
            $id = json_decode($request->request->get("id"));
            $categorie = $categorieRepository->find((int)$id);
            if($sousCategorieRepository->findOneBy(["categorie"=>$categorie])){
                return new JsonResponse(["Erreur","Erreur, ce mode ne peut plus être supprimer"]);
            }
            $categorieRepository->remove($categorie, true);
        }
        return new JsonResponse(["Succes",'Supprimer']);
    }
}
