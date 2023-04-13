<?php

namespace App\Controller;

use App\Database\NativeQueryMySQL;
use App\Entity\CategClient;
use App\Entity\Categorie;
use App\Entity\Conditionnement;
use App\Entity\ConditionnerCateClient;
use App\Form\ProduitType;
use App\Entity\Produit;
use App\Entity\Prix;
use App\Entity\Conditionner;
use App\Entity\ModeDef;
use App\Entity\SousCategorie;
use App\Form\DropType;
use App\Form\RechercheType;
use App\Form\Recherche2Type;
use App\Repository\CategClientRepository;
use App\Repository\CategorieRepository;
use App\Repository\ClientRepository;
use App\Repository\ConditionnementRepository;
use App\Repository\ConditionnerCateClientRepository;
use App\Repository\ConditionnerRepository;
use App\Repository\ModeDefRepository;
use App\Repository\PrixRepository;
use App\Repository\ProduitRepository;
use App\Repository\SousCategorieRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gprix/produit')]
class ProduitController extends AbstractController
{
    #[Route('/produit/recherche/info/recuperation', name: 'produit_recherche_recup_donnees')]
    public function resultaRecherche(Request $request,NativeQueryMySQL $native, ClientRepository $clientRepository){
        if($request->isMethod('POST')){

            /*Recuperation*/

            $produit = (int)json_decode($request->request->get("produit"));
             
            $idCli = (int)json_decode($request->request->get("client"));

            $client = $clientRepository->find($idCli);
            $idCateg = (int) $client->getCateClient()->getId();
            /*Requete*/
            $sql = "SELECT cc.prix_min as prixMin, cc.prix_max as prixMax, cc.prix_vente as prixVente, co.libelle as conditionnement, c.prix_achat as prixAchat , c.prix_revient as prixRevient  FROM `conditionner_cate_client` cc INNER JOIN conditionner c ON c.id = cc.conditionner_id INNER JOIN produit p ON p.id = c.produit_id INNER JOIN categ_client cat ON cat.id = cc.cate_client_id INNER JOIN conditionnement co ON co.id = c.conditionnement_id WHERE cat.id =  $idCateg  AND p.id = $produit";
           
            /*Exécution Requete*/
            $results = $native->getConnection()->query($sql)->fetchAllAssociative();

            $res1 = [];
            foreach($results as $r){
                $res1[] = array(
                    'conditionnement'=> $r["conditionnement"] ,
                    'prixAchat'=> $r["prixAchat"]  == '' ? 0 : $r["prixAchat"] ,
                    'prixRevient'=> $r["prixRevient"] == '' ? 0 : $r["prixRevient"] ,
                    'prixVente'=> $r["prixVente"] == '' ? 0 : $r["prixVente"] ,
                    'prixMin'=> $r["prixMin"]  == '' ? 0 : $r["prixMin"],
                    'prixMax'=> $r["prixMax"] == '' ? 0 : $r["prixMax"]
                );
            }
            $res = [
                'data'=>$res1
            ];
            return new JsonResponse($res);
        }
        return $this->redirectToRoute("produit_recherche_info");
    }

    #[Route('/produit/info/recuperation/par/produit', name: 'produit_recherche_recup')]
    public function resultaRechProduit(Request $request,NativeQueryMySQL $native,ProduitRepository $prR){
        $form = $this->createForm(Recherche2Type::class);
        $form->remove("categ");
        $form->handleRequest($request);
        if($request->isMethod('POST')){
           
           $pro = $request->request->get("produit");
           $sql = "SELECT co.libelle as conditionnement, c.prix_min as prixMin, c.prix_max as prixMax, c.prix_achat as prixAchat , c.prix_revient as prixRevient, c.prix_vente as prixVente  FROM conditionner c INNER JOIN produit p ON p.id = c.produit_id INNER JOIN conditionnement co ON co.id = c.conditionnement_id WHERE p.id = $pro";
           
           /*Exécution Requete*/
           $results = $native->getConnection()->query($sql)->fetchAllAssociative();

            //$conds = $results->getConditionners();
            $res1 = null;
            foreach($results as $r){
                $res1[] = array(
                    'conditionnement'=> $r["conditionnement"] ,
                    'prixAchat'=> $r["prixAchat"]  == '' ? 0 : $r["prixAchat"] ,
                    'prixRevient'=> $r["prixRevient"] == '' ? 0 : $r["prixRevient"] ,
                    'prixVente'=> $r["prixVente"] == '' ? 0 : $r["prixVente"] ,
                    'prixMin'=> $r["prixMin"]  == '' ? 0 : $r["prixMin"],
                    'prixMax'=> $r["prixMax"] == '' ? 0 : $r["prixMax"]
                );
            }
            //$donne 
            $res = [
                'data'=>$res1
            ];
            return new JsonResponse($res);

        }
        return $this->renderForm('produit/reste3.html.twig',[
            'form'=>$form
        ]);
    }

    #[Route('/produit/recherche/info/recuperation/par/categorie', name: 'produit_recherche_recup_don_cate')]
    public function resultaRecherche2(Request $request,NativeQueryMySQL $native){
        $form = $this->createForm(Recherche2Type::class);
        $form->handleRequest($request);
        if($request->isMethod('POST')){
            /*Recuperation*/
            $produit = (int)json_decode($request->request->get("produit"));
            $idCateg = (int)json_decode($request->request->get("categorie"));
            $sql = "SELECT cc.prix_min as prixMin, cc.prix_max as prixMax, cc.prix_vente as prixVente, co.libelle as conditionnement, c.prix_achat as prixAchat , c.prix_revient as prixRevient  FROM `conditionner_cate_client` cc INNER JOIN conditionner c ON c.id = cc.conditionner_id INNER JOIN produit p ON p.id = c.produit_id INNER JOIN categ_client cat ON cat.id = cc.cate_client_id INNER JOIN conditionnement co ON co.id = c.conditionnement_id WHERE cat.id =  $idCateg  AND p.id = $produit";
           
            /*Exécution Requete*/
            $results = $native->getConnection()->query($sql)->fetchAllAssociative();

            $res1 = [];
            foreach($results as $r){
                $res1[] = array(
                    'conditionnement'=> $r["conditionnement"] ,
                    'prixAchat'=> $r["prixAchat"]  == '' ? 0 : $r["prixAchat"] ,
                    'prixRevient'=> $r["prixRevient"] == '' ? 0 : $r["prixRevient"] ,
                    'prixVente'=> $r["prixVente"] == '' ? 0 : $r["prixVente"] ,
                    'prixMin'=> $r["prixMin"]  == '' ? 0 : $r["prixMin"],
                    'prixMax'=> $r["prixMax"] == '' ? 0 : $r["prixMax"]
                );
            }
            $res = [
                'data'=>$res1
            ];
            return new JsonResponse($res);
        }
        return $this->renderForm('produit/reste2.html.twig',[
            'form'=>$form
        ]);
    }

    #[Route('/produit/recherche/info', name: 'produit_recherche_info')]
    public function recherche(Request $request, NativeQueryMySQL $native){
        $form = $this->createForm(RechercheType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $sql = "SELECT cc.prix_min, cc.prix_max, co.libelle as conditionnement, c.prix_achat as prixAchat , c.prix_revient as prixRevient  FROM `conditionner_cate_client` cc INNER JOIN conditionner c ON c.id = cc.conditionner_id INNER JOIN produit p ON p.id = c.produit_id INNER JOIN categ_client cat ON cat.id = cc.cate_client_id INNER JOIN conditionnement co ON co.id = c.conditionnement_id WHERE cat.id = AND p.id = ";
            $results = $native->getConnection()->query($sql)->fetchAll();
            $produit = $form->get("produit")->getData();
            $client = $form->get("client")->getData();
            $categ = $client->getCategClient();

            return new JsonResponse("Ok");
        }
        return $this->renderForm('produit/reste.html.twig',[
            'form'=>$form
        ]);
    }
    
    #[Route('/produit/list', name: 'produi_liste')]
    public function liste(Request $request,ModeDefRepository $modeDefRepository,  ConditionnerCateClientRepository $conditionnerCateClientRepository, 
    CategClientRepository $categClientRepository, EntityManagerInterface $em,ConditionnementRepository $conditionnementRepository, ProduitRepository $produitRe, 
    ConditionnerRepository $conditionnerRepository, CategorieRepository $categorieRepository, SousCategorieRepository $sousCategorieRepository,PrixRepository $prixRepository, 
    ProduitRepository $prR, PrixRepository $prixR, ConditionnerRepository $condR, ConditionnerCateClientRepository $condCaCli): Response
    {
        $dropForm = $this->createForm(DropType::class);
        $produits = $prR->findAll();
        $res1 = [];
        foreach($produits as $pr){
            $cons = $condR->findBy(['produit'=>$pr]);
            $condition = [];
            if($cons){
                foreach($cons as $co){
                    $prix = $prixR->findOneBy(["conditionner"=>$co, "estActif"=> 1]);
                    // dd($prix->getPrixMax());
                    $prX = [];
                    if($prix){
                        $prX = array(
                            "prixMin"=> $prix->getPrixMin(),
                            "prixMax"=> $prix->getPrixMax(),
                            "prixConcu"=> $prix->getPrixConcurentiel(),
                        );
                    }else{
                        $prX = array(
                            "prixMin"=> 0,
                            "prixMax"=> 0,
                            "prixConcu"=> 0,
                        );
                    }
                    
                    $condition[] = [
                        "constionnement"=>$co->getConditionnement()->getLibelle(),
                        "prixMin"=> $co->getPrixMin(),
                        "prixMax"=> $co->getPrixMax(),
                        "prixConcu"=>$co->getPrixConcurentiel(),
                        'prix'=>$prX
                    ];
                   // dd($prix->getPrixMax());
                 }
            }

            $conClients = array();
            foreach($cons as $c){
                $condParClient = $condCaCli->findBy(["conditionner"=>$c]);
                //dd($condParClient);
                $conClients[] = array(
                    "conditionnement"=>$c,
                    "condClients"=>$condParClient
                );
            }
            
            // $res1[] = array(
            //     'id'=>$pr->getId(),
            //     'code'=>$pr->getCode() ,
            //     'designation'=>$pr->getDesignation(),
            //     'description'=>$pr->getDescription(),
            //     'refUsi'=>$pr->getRefUsine(),
            //     'sousCategorie'=> $pr->getSousCategorie() == null ? " " : $pr->getSousCategorie()->getLibelle(),
            //     'categorie'=> $pr->getSousCategorie() == null ? " " :  $pr->getSousCategorie()->getCategorie()->getLibelle(),
            //     'mode'=>$pr->getMode() == null ? " ":$pr->getMode()->getLibelle(),
            //     'conditionners'=>$condition,
            //     'conditionnersParCategClients'=>  $conClients,
            // );
        }
        // $res = [
        //     'data'=>$res1
        // ];
        // $result = new JsonResponse($res);
        $dropForm->handleRequest($request);

        if($dropForm->isSubmitted()){
            $fichier = $dropForm->get("dropFile")->getData();
            
            $nomFi = md5(uniqid()) . '.'. $fichier->guessExtension();
            // move_uploaded_file($image,$this->getParameter( "upload_dir"."/".strtoupper($nom.$prenom)));
             $fichier->move(
                 $this->getParameter('import_dir') ,
                 $nomFi
             );
             //dd($nomFi);
             set_time_limit(0);
             $fichier = $this->getParameter('import_dir').'/'.$nomFi;
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
             return $this->renderForm('produit/liste.html.twig',[
                "produits"=>$res1,
                'drop'=>$dropForm,
                "msg"=>"Importation réussie"
             ]);
        }

        return $this->renderForm('produit/liste.html.twig', [
            "produits"=>$produits,
            'drop'=>$dropForm
        ]);
    }

    #[Route('/produit/list/ajaxDonne', name: 'produi_listeAjax')]
    public function donnee(Request $request, NativeQueryMySQL $native, ConditionnerRepository $condR, PrixRepository $prR, ConditionnerCateClientRepository $condCaCli){

        if ($request->isMethod("POST")) { 
            $id = (int)$request->request->get("id");
            $produit =  $this->getDoctrine()->getRepository(Produit::class)->find($id);
            $conditionners = $condR->findBy(["produit"=>$produit]);
            $tdbodyCon = "";
            $tdbodyConClient = "";
            $conditAchatRevient = "";
            $historique = "";
            $datass =[];
            foreach($conditionners as $conditionner){
                $tdbodyCon .= "<tr> <td>". $conditionner->getConditionnement()->getLibelle() ." </td><td>". $conditionner->getPrixVente() ."</td><td>". $conditionner->getPrixMin() ."</td><td>".  $conditionner->getPrixMax() ."</td><td>". $conditionner->getPrixConcurentiel() ."</td> </tr> ";
                $historique .= "<div class='btn btn-primary'> CONDITIONNEMENT : ".$conditionner->getConditionnement()->getLibelle()."</div>";
                $condClis = $condCaCli->findBy(["conditionner"=>$conditionner]);
                $cpt = 0;
                
                foreach($condClis as $cond){
                    $tdbodyConClient .= "<tr> <td>". $conditionner->getConditionnement()->getLibelle() ." </td><td>". $cond->getCateClient()->getLibelle() ."</td><td>".$cond->getPrixVente() ."</td><td>".$cond->getPrixMax() ."</td><td>". $cond->getPrixMin() ."</td> </tr> ";
                }
                
                $Histprixs = $prR->historiquePrix($conditionner, $produit);
                $sql = "SELECT p.prix_min, p.prix_max, p.date_attribution, p.date_fin, p.prix_vente FROM prix p INNER JOIN conditionner c ON c.id = p.conditionner_id  INNER JOIN produit pr ON c.produit_id =pr.id WHERE  pr.id = ". $produit->getId()." and p.conditionner_id = ".$conditionner->getId()." ORDER BY p.id DESC" ;
                $datas = $native->getConnection()->query($sql)->fetchAllAssociative();
                //dump($conditionner,$produit, $datas);
                $datass[] =  $sql;

                $historique .=  '<table class="table table-separate table-head-custom table-checkable dataTable no-footer dtr-inline collapsed">';
                $historique .= '<thead><tr><th>Date début</th><th>Date fin</th><th>Prix vente</th><th>Prix Min</th><th>Prix Max</th></tr></thead>'
                               .'<tbody>';
                foreach($datas as $prix){
                    $historique .= "<tr>"
                    ."<td>". $prix['date_attribution']."</td>"
                    ."<td>". $prix['date_fin']."</td>"
                    ."<td>". $prix['prix_vente']."</td>"
                    ."<td>". $prix['prix_min']."</td>"
                    ."<td>". $prix['prix_max']."</td>"
                    ."</tr> ";
                }
                $historique .= '</tbody></table>';
                
                $prixs = $prR->findBy(["conditionner"=>$conditionner, "estActif"=>1, "prixMin"=>NULL, "prixMax"=>NULL, "prixConcurentiel"=>NULL , "conditionnerClient"=>NULL]);
                foreach($prixs as $prix){
                    $conditAchatRevient .= "<tr> <td>". $prix->getConditionner()->getConditionnement()->getLibelle() ." </td><td>". $prix->getPrixAchat() ."</td><td>".$prix->getPrixRevient() ."</td></tr> ";
                }
            }
           return new JsonResponse([$tdbodyCon,$tdbodyConClient, $historique]); 
        }else{ 
            return new JsonResponse("Erreur"); 
        }
    }

    #[Route('/produit/list', name: 'produit_ajax_liste')]
    public function Ajaxliste(ProduitRepository $prR, ConditionnerRepository $condR, ConditionnerCateClientRepository $condCaCli): Response
    {
        $produits = $prR->findAll();
        $res1 = [];
        foreach($produits as $pr){
            $cons = $condR->findBy(['produit'=>$pr]);
            $conClients = array();
            foreach($cons as $c){
                $condParClient = $condCaCli->findBy(["conditionner"=>$c]);
                //dd($condParClient);
                $conClients[] = array(
                    "conditionnement"=>$c,
                    "condClients"=>$condParClient
                );
            }
            
            $res1[] = array(
                'id'=>$pr->getId(),
                'code'=>$pr->getCode() ,
                'designation'=>$pr->getDesignation(),
                'description'=>$pr->getDescription(),
                'refUsi'=>$pr->getRefUsine(),
                'sousCategorie'=>$pr->getSousCategorie() == null ? " " : $pr->getSousCategorie()->getLibelle(),
                'categorie'=>$pr->getSousCategorie() == null ? " " : $pr->getSousCategorie()->getCategorie()->getLibelle(),
                'mode'=>$pr->getMode() == null ? " ":$pr->getMode()->getLibelle(),
                'conditionners'=>$pr->getConditionners(),
                'conditionnersParCategClients'=>  $conClients,
            );
        }
        $res = [
            // 'meta'=>array(

            //     "page"=> 1,
            //     "pages"=> 1,
            //     "perpage"=> -1,
            //     "sort"=> "asc",
            //     "field"=> "id"
            // ),
            'data'=>$res1
        ];
        $result = new JsonResponse($res);
        //dd($res);
        return $result;
        //dd($res1);
        // return $this->render('produit/liste.html.twig', [
        //     "produits"=> $res1
        // ]);
    }
    
    #[Route('/enreg/fin', name: 'produit_edit_view_fin_enreg_attente', methods: ['GET', 'POST'])]
    public function apreNew(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
       return $this->redirectToRoute('produit');
    }
    

    #[Route('/produit', name: 'produit', methods: ['GET', 'POST'])]
    public function index(Request $request, ProduitRepository $produitRepository): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

       

        if ($form->isSubmitted()) {
            $conditionners = $form->get("conditionners")->getData();
            $sousCat = $form->get("sousCategorie")->getData();
            $designation = $form->get("designation")->getData();
            $id = $produit->getId();
            $prod = $produitRepository->checkProduit($id, $designation, $sousCat);
            $conds = array();
            foreach($conditionners as $c){
                if(in_array($c->getConditionnement()->getLibelle(), $conds)){
                    $msg = "Le conditionnement ".$c->getConditionnement()->getLibelle()." ne peut pas être utilisé deux fois pour ce produit"; 
                    return $this->renderForm('produit/index.html.twig', [
                        'produit' => $produit,
                        'form' => $form,
                        'msg' => $msg,
                    ]);
                }else{
                    $conds[] = $c->getConditionnement()->getLibelle();
                }
            }
            
            if($prod){
                $msg = "Ce produit semble déjà existé. Veuillez revoir votre enregistrement"; 
                return $this->renderForm('produit/index.html.twig', [
                    'produit' => $produit,
                    'form' => $form,
                    'msg' => $msg,
                ]);
            }
           // dd($id, $designation, $sousCat,$prod);
            $msg = "";
            foreach($conditionners as $c){
                if($c->getPrixMin() || $c->getPrixMax()){
                    if((float)$c->getPrixMin() > (float)$c->getPrixMax()){
                        $msg = "Le prix minimal ne peut pas être supérieur au prix maximal pour les conditionnement"; 
                        return $this->renderForm('produit/index.html.twig', [
                            'produit' => $produit,
                            'form' => $form,
                            'msg' => $msg,
                        ]);
                    }
                    
                    if((float)$c->getPrixMin() <= (float)$c->getPrixMax()){
                        $cats = $c->getConditionnerCateClients();
                        foreach($cats as $co){
                            if((float)$co->getPrixMin() > (float)$co->getPrixMax()){
                                $msg = "Le prix minimal ne peut pas être supérieur au prix maximal pour les prix par catégorie de client"; 
                                return $this->renderForm('produit/index.html.twig', [
                                    'produit' => $produit,
                                    'form' => $form,
                                    'msg' => $msg,
                                ]);
                            }
    
                            if((float)$co->getPrixVente() < (float)$c->getPrixMin() || (float)$co->getPrixVente() > (float)$c->getPrixMax()){
                                $msg = "Le prix de vente pour la catégorie du client *".$co->getCateClient()->getLibelle()."* doit être compris entre le prix minimal et le prix maximal du conditionnement"; 
                                return $this->renderForm('produit/index.html.twig', [
                                    'produit' => $produit,
                                    'form' => $form,
                                    'msg' => $msg,
                                ]);
                            }
    
                        }
                    }
                    if($c->getPrixMax()){
                        if((float)$c->getPrixVente() > (float)$c->getPrixMax() || (float)$c->getPrixVente() < (float)$c->getPrixMin()){
                            $msg = "Le prix de vente doit être compris entre le prix min et le prix max. Veuillez revoir le conditionnement ".$c->getConditionnement();
                            return $this->renderForm('produit/index.html.twig', [
                                'produit' => $produit,
                                'form' => $form,
                                'msg' => $msg,
                            ]);
                        }
                    }
                }
            }
            $produitRepository->save($produit, true);
            return $this->redirectToRoute("produi_liste");
        }

        return $this->renderForm('produit/index.html.twig', [
            'produit' => $produit,
            'form' => $form,
            
        ]);
    }

    #[Route('/edit/{id}--', name: 'produit_edit_view', methods: ['GET', 'POST'])]
    public function edition(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
       // $produit
        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            $conditionners = $form->get("conditionners")->getData();
            $msg = "";
            $sousCat = $produit->getSousCategorie();
            $designation = $produit->getDesignation();
            $id = $produit->getId();
            $prod = $produitRepository->checkProduit($id, $designation, $sousCat);

            $conds = array();
            foreach($conditionners as $c){
                if(in_array($c->getConditionnement()->getLibelle(), $conds)){
                    $msg = "Le conditionnement ".$c->getConditionnement()->getLibelle()." ne peut pas être utilisé deux fois pour ce produit"; 
                    return $this->renderForm('produit/index.html.twig', [
                        'produit' => $produit,
                        'form' => $form,
                        'msg' => $msg,
                    ]);
                }else{
                    $conds[] = $c->getConditionnement()->getLibelle();
                }
            }
            

            
            if($prod){
                $msg = "Ce produit semble déjà existé sur un autre enregistrement. Veuillez revoir votre enregistrement"; 
                return $this->renderForm('produit/index.html.twig', [
                    'produit' => $produit,
                    'form' => $form,
                    'msg' => $msg,
                ]);
            }
            foreach($conditionners as $c){
                if($c->getPrixMin() || $c->getPrixMax()){
                    if((float)$c->getPrixMin() > (float)$c->getPrixMax()){
                        $msg = "Le prix minimal ne peut pas être supérieur au prix maximal pour les conditionnement"; 
                        return $this->renderForm('produit/index.html.twig', [
                            'produit' => $produit,
                            'form' => $form,
                            'msg' => $msg,
                        ]);
                    }
                    
                    if((float)$c->getPrixMin() <= (float)$c->getPrixMax()){
                        $cats = $c->getConditionnerCateClients();
                        foreach($cats as $co){
                            if((float)$co->getPrixMin() > (float)$co->getPrixMax()){
                                $msg = "Le prix minimal ne peut pas être supérieur au prix maximal pour les prix par catégorie de client"; 
                                return $this->renderForm('produit/index.html.twig', [
                                    'produit' => $produit,
                                    'form' => $form,
                                    'msg' => $msg,
                                ]);
                            }
    
                            if((float)$co->getPrixVente() < (float)$c->getPrixMin() || (float)$co->getPrixVente() > (float)$c->getPrixMax()){
                                $msg = "Le prix de vente pour la catégorie du client *".$co->getCateClient()->getLibelle()."* doit être compris entre le prix minimal et le prix maximal du conditionnement"; 
                                return $this->renderForm('produit/index.html.twig', [
                                    'produit' => $produit,
                                    'form' => $form,
                                    'msg' => $msg,
                                ]);
                            }
    
                        }
                    }
                    if($c->getPrixMax()){
                        if((float)$c->getPrixVente() > (float)$c->getPrixMax() || (float)$c->getPrixVente() < (float)$c->getPrixMin()){
                            $msg = "Le prix de vente doit être compris entre le prix min et le prix max. Veuillez revoir le conditionnement ".$c->getConditionnement();
                            return $this->renderForm('produit/index.html.twig', [
                                'produit' => $produit,
                                'form' => $form,
                                'msg' => $msg,
                            ]);
                        }
                    }
                }
            }
            $produitRepository->update($produit, true);
            return $this->redirectToRoute("produi_liste");
        }
        
        return $this->renderForm('produit/index.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id}-/prix', name: 'produit_edit_prix_view', methods: ['GET', 'POST'])]
    public function modifPrix(Request $request, Produit $produit, ProduitRepository $produitRepository){
        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            $conditionners = $form->get("conditionners")->getData();
            $msg = "";
            $sousCat = $produit->getSousCategorie();
            $designation = $produit->getDesignation();
            $id = $produit->getId();
            $prod = $produitRepository->checkProduit($id, $designation, $sousCat);

            $conds = array();
            foreach($conditionners as $c){
                if(in_array($c->getConditionnement()->getLibelle(), $conds)){
                    $msg = "Le conditionnement ".$c->getConditionnement()->getLibelle()." ne peut pas être utilisé deux fois pour ce produit"; 
                    return $this->renderForm('produit/index.html.twig', [
                        'produit' => $produit,
                        'form' => $form,
                        'msg' => $msg,
                    ]);
                }else{
                    $conds[] = $c->getConditionnement()->getLibelle();
                }
            }
            

            
            if($prod){
                $msg = "Ce produit semble déjà existé sur un autre enregistrement. Veuillez revoir votre enregistrement"; 
                return $this->renderForm('produit/index.html.twig', [
                    'produit' => $produit,
                    'form' => $form,
                    'msg' => $msg,
                ]);
            }
            foreach($conditionners as $c){
                if($c->getPrixMin() || $c->getPrixMax()){
                    if((float)$c->getPrixMin() > (float)$c->getPrixMax()){
                        $msg = "Le prix minimal ne peut pas être supérieur au prix maximal pour les conditionnement"; 
                        return $this->renderForm('produit/index.html.twig', [
                            'produit' => $produit,
                            'form' => $form,
                            'msg' => $msg,
                        ]);
                    }
                    
                    if((float)$c->getPrixMin() <= (float)$c->getPrixMax()){
                        $cats = $c->getConditionnerCateClients();
                        foreach($cats as $co){
                            if((float)$co->getPrixMin() > (float)$co->getPrixMax()){
                                $msg = "Le prix minimal ne peut pas être supérieur au prix maximal pour les prix par catégorie de client"; 
                                return $this->renderForm('produit/index.html.twig', [
                                    'produit' => $produit,
                                    'form' => $form,
                                    'msg' => $msg,
                                ]);
                            }
    
                            if((float)$co->getPrixVente() < (float)$c->getPrixMin() || (float)$co->getPrixVente() > (float)$c->getPrixMax()){
                                $msg = "Le prix de vente pour la catégorie du client *".$co->getCateClient()->getLibelle()."* doit être compris entre le prix minimal et le prix maximal du conditionnement"; 
                                return $this->renderForm('produit/index.html.twig', [
                                    'produit' => $produit,
                                    'form' => $form,
                                    'msg' => $msg,
                                ]);
                            }
    
                        }
                    }
                    if($c->getPrixMax()){
                        if((float)$c->getPrixVente() > (float)$c->getPrixMax() || (float)$c->getPrixVente() < (float)$c->getPrixMin()){
                            $msg = "Le prix de vente doit être compris entre le prix min et le prix max. Veuillez revoir le conditionnement ".$c->getConditionnement();
                            return $this->renderForm('produit/index.html.twig', [
                                'produit' => $produit,
                                'form' => $form,
                                'msg' => $msg,
                            ]);
                        }
                    }
                }
            }
            $produitRepository->update($produit, true);
            return $this->redirectToRoute("produi_liste");
        }
        
        return $this->renderForm('produit/modifPrix.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('produit/{id}/suppr', name: 'gp_produit_delete', methods: ['GET'])]
    public function delete(Request $request, Produit $produit, ConditionnerRepository $condRep, ProduitRepository $produitRepository): Response
    {
        $cond = $condRep->findBy(["produit"=>$produit]);
        if($cond){
            return $this->redirectToRoute('produi_liste', [
                "msg"=>"Impossible de supprimer l'élément"
            ], Response::HTTP_SEE_OTHER);
        }else{
            $produitRepository->remove($produit, true);
        }
        
        return $this->redirectToRoute('produi_liste', [], Response::HTTP_SEE_OTHER);
    }
}

