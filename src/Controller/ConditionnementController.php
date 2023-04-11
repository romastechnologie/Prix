<?php

namespace App\Controller;

use App\Entity\Conditionnement;
use App\Form\ConditionnementType;
use App\Repository\ConditionnementRepository;
use App\Repository\ConditionnerCateClientRepository;
use App\Repository\ConditionnerRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gprix/conditionnement')]
class ConditionnementController extends AbstractController
{
    #[Route('/', name: 'app_conditionnement_index', methods: ['GET'])]
    public function index(ConditionnementRepository $conditionnementRepository): Response
    {
        return $this->render('conditionnement/index.html.twig', [
            'conditionnements' => $conditionnementRepository->findAll(),
        ]);
    }

    #[Route('/conditio/liste', name: 'conditionnement_liste')]
    public function listeCond(ConditionnementRepository $catR): Response
    {
        $conditions = $catR->findAll();
        $res1 = [];
        foreach($conditions as $li){
            $res1[] = array(
                'id'=>$li->getId(),
                'code'=>$li->getCode() ,
                'libelle'=>$li->getLibelle(),
                'qte'=>$li->getQte(),
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


    #[Route('/new', name: 'app_conditionnement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ConditionnementRepository $conditionnementRepository): Response
    {
        $conditionnement = new Conditionnement();
        $form = $this->createForm(ConditionnementType::class, $conditionnement);
        $form->handleRequest($request);

        if ($request->isMethod("POST")) { 
            $msg ="";
            $code = json_decode($request->request->get("code"));
            $id = json_decode($request->request->get("id"));
            $libelle = json_decode($request->request->get("libelle"));
            $qte = json_decode($request->request->get("qte"));
            if($id == ""){
                $type = "Ajout";
                $catCode = $conditionnementRepository->findOneBy(["code"=>$code]);
                $cateLib =  $conditionnementRepository->findOneBy(["libelle"=>$libelle]);
                if($catCode){
                    return new JsonResponse([$type,"Un enregistrement porte déjà ce code"]);
                }
                if($cateLib){
                    return new JsonResponse([$type, "Un enregistrement porte déjà ce libellé"]);
                }
                $conditionnement->setCode($code)->setLibelle($libelle)->setQte($qte);
                $conditionnementRepository->save($conditionnement, true);
                return new JsonResponse([$type,"Enregistrement effectué"]);
            }else{
                $type = "Modif";
                $cond = $conditionnementRepository->findConditionnementByValues($id, $libelle, $code);
                if($cond){
                    return new JsonResponse([$type,"Un enregistrement porte déjà ces information"]);
                }
                $condi =  $conditionnementRepository->find((int)$id);
                $condi->setCode($code)->setLibelle($libelle)->setQte($qte);
                $conditionnementRepository->save($condi, true);
                return new JsonResponse([$type,"Modification effectuée"]);
            }
            
        }else{
            return $this->renderForm('conditionnement/new.html.twig', [
                'conditionnement' => $conditionnement,
                'form' => $form,
            ]);
            }
    }

    #[Route('/{id}', name: 'app_conditionnement_show', methods: ['GET'])]
    public function show(Conditionnement $conditionnement): Response
    {
        return $this->render('conditionnement/show.html.twig', [
            'conditionnement' => $conditionnement,
        ]);
        
    }

    #[Route('/{id}/edit', name: 'app_conditionnement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Conditionnement $conditionnement, ConditionnementRepository $conditionnementRepository): Response
    {
        $form = $this->createForm(ConditionnementType::class, $conditionnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conditionnementRepository->save($conditionnement, true);

            return $this->redirectToRoute('app_conditionnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('conditionnement/new.html.twig', [
            'conditionnement' => $conditionnement,
            'form' => $form,
        ]);
    }

    #[Route('/conditionnement/supprimer', name: 'app_conditionnement_delete', methods: ['POST'])]
    public function delete(Request $request, ConditionnerCateClientRepository $conditionnerCateClientRepository, ConditionnementRepository $conditionnementRepository, ProduitRepository $produitRepository, ConditionnerRepository $conditionnerRepository): Response
    {
        
        if ($request->isMethod("POST")) { 
            $id = json_decode($request->request->get("id"));
            $conditionnement = $conditionnementRepository->find((int)$id);
                // return new JsonResponse(["Verif",$id]);
            if($conditionnerRepository->findOneBy(["conditionnement"=>$conditionnement])){
                return new JsonResponse(["Erreur","Erreur, ce condtionnement ne peut plus être supprimer"]);
            }
            $conditionnementRepository->remove($conditionnement, true);
        }

        return new JsonResponse(["Succes",'Supprimer']);
    }
}
