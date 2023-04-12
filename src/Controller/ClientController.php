<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\Client1Type;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gprix/client')]
class ClientController extends AbstractController
{

    

    #[Route('/', name: 'app_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {
        return $this->render('client/index.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ClientRepository $clientRepository): Response
    {
        $client = new Client();
        $form = $this->createForm(Client1Type::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stat = $client->getStatut();
            
            if($stat == "Physique"){
                $nom = $client->getNom();
                $prenom = $client->getPrenom();
                $date = $client->getDateNais();
                $tel= $client->getTelephone1();
                $id = $client->getId();
                
                $check = $clientRepository->checkPersonnePhysique($id ,$nom,$prenom,$date, $tel);
                //dd($id,$check);
                if($check){
                    $msg = "Ce client semble déjà existé. Veuillez revoir votre enregistrement"; 
                    return $this->renderForm('client/new.html.twig', [
                        'client' => $client,
                        'form' => $form,
                        'msg' => $msg,
                    ]);
                }
            }
            if($stat == "Moral"){
                $raisonSocial = $client->getRaisonSociale();
                $rccm = $client->getPrenom();
                $ifu = $client->getDateNais();
                $denomination= $client->getTelephone1();
                $id = $client->getId();
                $check = $clientRepository->checkPersonneMoral($id, $rccm,$denomination, $ifu, $raisonSocial);
                if($check){
                    $msg = "Ce client semble déjà existé. Veuillez revoir votre enregistrement"; 
                    return $this->renderForm('client/new.html.twig', [
                        'client' => $client,
                        'form' => $form,
                        'msg' => $msg,
                    ]);
                }
            }
            //dd("Ok");
            $clientRepository->save($client, true);

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, ClientRepository $clientRepository): Response
    {
        $form = $this->createForm(Client1Type::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stat = $client->getStatut();
            if($stat == "Physique"){
                $nom = $client->getNom();
                $prenom = $client->getPrenom();
                $date = $client->getDateNais();
                $tel= $client->getTelephone1();
                $id = $client->getId();
                $check = $clientRepository->checkPersonnePhysique($id ,$nom,$prenom,$date, $tel);

                if($check){
                    $msg = "Ce client semble déjà existé. Veuillez revoir votre enregistrement"; 
                    return $this->renderForm('client/new.html.twig', [
                        'client' => $client,
                        'form' => $form,
                        'msg' => $msg,
                    ]);
                }
            }
            if($stat == "Moral"){
                $raisonSocial = $client->getRaisonSociale();
                $rccm = $client->getPrenom();
                $ifu = $client->getDateNais();
                $denomination= $client->getTelephone1();
                $id = $client->getId();
                $check = $clientRepository->checkPersonneMoral($id, $rccm,$denomination, $ifu, $raisonSocial);
                if($check){
                    $msg = "Ce client semble déjà existé. Veuillez revoir votre enregistrement"; 
                    return $this->renderForm('client/new.html.twig', [
                        'client' => $client,
                        'form' => $form,
                        'msg' => $msg,
                    ]);
                }
            }
            $clientRepository->save($client, true);

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        //dd("OK");
        return $this->renderForm('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, ClientRepository $clientRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $clientRepository->remove($client, true);
        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }
}
