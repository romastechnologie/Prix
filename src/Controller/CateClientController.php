<?php

namespace App\Controller;

use App\Entity\CategClient;
use App\Form\CateClientType;
use App\Repository\CategClientRepository;
use App\Repository\ClientRepository;
use App\Repository\ConditionnerCateClientRepository;
use App\Repository\ModeDefRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gprix/categ/client')]
class CateClientController extends AbstractController
{

    #[Route('/categ/liste', name: 'categ_client_liste')]
    public function listeCond(CategClientRepository $categClientRepository): Response
    {
        $categClients = $categClientRepository->findAll();
        $res1 = [];
        foreach($categClients as $li){
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


    #[Route('/cate/client', name: 'app_cate_client')]
    public function index(): Response
    {
        return $this->render('cate_client/index.html.twig', [
            'controller_name' => 'CateClientController',
        ]);
    }

    #[Route('/new', name: 'categ_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategClientRepository $categClientRepository): Response
    {
        $categClient = new CategClient();
        $form = $this->createForm(CateClientType::class, $categClient);
        $form->handleRequest($request);

        if ($request->isMethod("POST")) { 
            $code = json_decode($request->request->get("code"));
            $id = json_decode($request->request->get("id"));
            $libelle = json_decode($request->request->get("libelle"));
            $catCode = $categClientRepository->findOneBy(["code"=>$code]);
            $cateLib =  $categClientRepository->findOneBy(["libelle"=>$libelle]);
            if($id == ""){
                $type = "Ajout";
                if($catCode){
                    return new JsonResponse([$type,"Un enregistrement porte déjà ce code"]);
                }
                if($cateLib){
                    return new JsonResponse([$type,"Un enregistrement porte déjà ce libellé" ]);
                }
                $categClient->setCode($code)->setLibelle($libelle);
                $categClientRepository->save($categClient, true);
                return new JsonResponse([$type,"Enregistrement effectué"]);
            }else{
                $type = "Modif";
                $cat =  $categClientRepository->findCategorieByValues($id, $libelle, $code);
                if($cat){
                    return new JsonResponse([$type,"Un enregistrement porte déjà ces informations"]);
                }else{
                    $categorie =   $categClientRepository->find((int)$id);
                    $categorie->setCode($code)->setLibelle($libelle);
                    $categClientRepository->save($categorie, true);
                    return new JsonResponse([$type,"Modification effectuée"]);
                }

            }
           
        }else{
            return $this->renderForm('cate_client/new.html.twig', [
                'categ_client' => $categClient,
                'form' => $form,
            ]);
            }
    }

    #[Route('/categorie/client/supprimer', name: 'categ_client_delete', methods: ['POST'])]
    public function delete(Request $request,CategClientRepository $categClientRepository, ConditionnerCateClientRepository $conditionnerCateClientRepository, ClientRepository $clientRepository): Response
    {
        
        if ($request->isMethod("POST")) { 
            $id = json_decode($request->request->get("id"));
            $categ = $categClientRepository->find((int)$id);
            if($clientRepository->findOneBy(["cateClient"=>$categ]) || $conditionnerCateClientRepository->findOneBy(["cateClient"=>$categ]) ){
                return new JsonResponse(["Erreur","Erreur, cette catégorie ne peut plus être supprimer"]);
            }
            $categClientRepository->remove($categ, true);
        }

        return new JsonResponse(["Succes",'Supprimer']);
    }

}
