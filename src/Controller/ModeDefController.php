<?php

namespace App\Controller;

use App\Entity\ModeDef;
use App\Form\ModeDefType;
use App\Repository\ModeDefRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gprix/mode/def')]
class ModeDefController extends AbstractController
{
    #[Route('/', name: 'app_mode_def_index', methods: ['GET'])]
    public function index(ModeDefRepository $modeDefRepository): Response
    {
        return $this->render('mode_def/index.html.twig', [
            'mode_defs' => $modeDefRepository->findAll(),
        ]);
    }

    #[Route('/listeAjax', name: 'liste_mode_def_by_ajax', methods: ['GET'])]
    public function listeAjax(ModeDefRepository $modeDefRepository): Response
    {
        $modes = $modeDefRepository->findAll();
        $res1 = [];
        foreach($modes as $li){
            $res1[] = array(
                'id'=>$li->getId(),
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

    #[Route('/new', name: 'app_mode_def_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ModeDefRepository $modeDefRepository): Response
    {
        $modeDef = new ModeDef();
        $form = $this->createForm(ModeDefType::class, $modeDef);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modeDefRepository->save($modeDef, true);

            return $this->redirectToRoute('app_mode_def_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mode_def/new.html.twig', [
            'mode_def' => $modeDef,
            'form' => $form,
        ]);
    }

    #[Route('/ajax/new', name: 'mode_def_new_by_ajax', methods: ['GET', 'POST'])]
    public function newAjax(Request $request, ModeDefRepository $modeDefRepository): Response
    {
        $modeDef = new ModeDef();
        $form = $this->createForm(ModeDefType::class, $modeDef);
        $form->handleRequest($request);
        
        if ($request->isMethod("POST")) { 
            $id = json_decode($request->request->get("id"));
            $libelle = json_decode($request->request->get("libelle"));
           // $type = json_decode($request->request->get("type"));
            $type = "";
            if($id != ""){
                $msg ="";
                $type = "Modif";
                
                $cateLib =  $modeDefRepository->modeUp($id,$libelle);
                if($cateLib){
                    $msg = "Un enregistrement porte déjà ce libellé";
                    return new JsonResponse([$type,$msg]);
                }
                $modeDef = $modeDefRepository->find((int)$id); 
                $modeDef->setLibelle($libelle);
                $modeDefRepository->update($modeDef, true);
                $msg = "Modification effectuée";
                return new JsonResponse([$type,$msg]);
            }else{
                $msg ="";
                $type = "Ajout";
                $cateLib =  $modeDefRepository->findOneBy(["libelle"=>$libelle]);
                if($cateLib){
                    $msg = "Un enregistrement porte déjà ce libellé";
                    return new JsonResponse([$type,$msg]);
                }
                $mode = new ModeDef();
                $mode->setLibelle($libelle);
                $modeDefRepository->save($mode, true);
                $msg = "Enregistrement effectué";
                return new JsonResponse([$type,$msg]);
            }
            
        }else{
            return $this->renderForm('mode_def/new.html.twig', [
                'mode_def' => $modeDef,
                'form' => $form,
            ]);
            }
    }

    #[Route('/{id}', name: 'app_mode_def_show', methods: ['GET'])]
    public function show(ModeDef $modeDef): Response
    {
        return $this->render('mode_def/show.html.twig', [
            'mode_def' => $modeDef,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mode_def_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ModeDef $modeDef, ModeDefRepository $modeDefRepository): Response
    {
        $form = $this->createForm(ModeDefType::class, $modeDef);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modeDefRepository->save($modeDef, true);

            return $this->redirectToRoute('app_mode_def_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mode_def/edit.html.twig', [
            'mode_def' => $modeDef,
            'form' => $form,
        ]);
    }

    #[Route('/mode/def/supprimer', name: 'mode_def_delete', methods: ['POST'])]
    public function delete(Request $request,ModeDefRepository $modeDefRep, ProduitRepository $produitRepository): Response
    {
        
        if ($request->isMethod("POST")) { 
            $id = json_decode($request->request->get("id"));
            $mode = $modeDefRep->find((int)$id);
            if($produitRepository->findOneBy(["mode"=>$mode])){
                return new JsonResponse(["Erreur","Erreur, ce mode ne peut plus être supprimer"]);
            }
            $modeDefRep->remove($mode, true);
        }

        return new JsonResponse(["Succes",'Supprimer']);
    }
}
