<?php

namespace App\Controller;

use App\Database\NativeQueryMySQL;
use App\Entity\Media;
use App\Entity\Utilisateur;
use App\Form\RegisterMdpType;
use App\Form\RegisterProfileType;
use App\Form\RegistrationFormType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gprix/inscription')]
class RegistrationController extends AbstractController
{

    #[Route('/inscription/index', name: 'index_g_prix_', methods: ['GET', 'POST'])]
    public function index(Request $request, UtilisateurRepository $utilisateurRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {

        return $this->renderForm('registration/index.html.twig', [
            'users' => $utilisateurRepository->findAll(),
        ]);
    }

    #[Route('/inscription', name: 'enregistrement_g_prix', methods: ['GET', 'POST'])]
    public function register(Request $request, UtilisateurRepository $utilisateurRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
       // dd($form);
        if ($form->isSubmitted()) {

            $mail = $form->get("email")->getData();
            $util = $utilisateurRepository->findOneBy(["email"=>$mail]);
            if($util){
                return $this->renderForm('registration/register.html.twig', [
                    'registrationForm' => $form,
                    "msg" =>"Ce mail est déjà utilisé par un autre utilisateur"
                ]);
            }

            $pseu = $form->get("username")->getData();
            $util = $utilisateurRepository->findOneBy(["username"=>$pseu]);
            if($util){
                return $this->renderForm('registration/register.html.twig', [
                    'registrationForm' => $form,
                    "msg" =>"Ce pseudo est déjà utilisé par un autre utilisateur"
                ]);
            }

            $nom = $form->get("nom")->getData();
            $prenom = $form->get("prenom")->getData();
            $image = $form->get("file")->getData();
            //dd($image);
            if($image){
                $fichier = md5(uniqid()) . '.'. $image->guessExtension();
                // move_uploaded_file($image,$this->getParameter( "upload_dir"."/".strtoupper($nom.$prenom)));
                 $image->move(
                     $this->getParameter('upload_dir_user')."/". str_replace(strtoupper($nom.$prenom)," ","") ,
                     $fichier
                 );
                 $media = new Media();
                 $media->setNomProd(str_replace(strtoupper($nom.$prenom)," ",""))->setChemin($fichier)->setUtilisateur($user);
                 $user->addPhoto($media);
            }

           
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            //  $utilisateurRepository->save($user, true);
            $entityManager->persist($user); 
            $entityManager->flush();
           
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_utilisateur_index');
        }

        return $this->renderForm('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/password/{id}/edit/', name: 'edit_password_edit', methods: ['GET', 'POST'])]
    public function editPassword(Request $request,NativeQueryMySQL $native,UserPasswordHasherInterface $userPasswordHasher, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository,$id=null): Response
    {
        $form = $this->createForm(RegisterMdpType::class, $utilisateur);
        $form->handleRequest($request);
        
        if($form->isSubmitted()) {
            $utilisateur->setPassword(
                $userPasswordHasher->hashPassword(
                    $utilisateur,
                    $form->get('password')->getData()
                )
            );
            //dd($utilisateur);
            $utilisateurRepository->save($utilisateur, true);
            $error = false;
            $this->addFlash(
                'notice',
                "Modification du mot de passe effectuée."
            );
            return $this->redirectToRoute('edit_password_edit', ['id'=>$utilisateur->getId(),], Response::HTTP_SEE_OTHER);
        }
        //dd($form);
        // else{
        //     // $per = $native->getConnection()->query("SELECT u.username FROM utilisateur u WHERE u.id = $id")->fetchOne();
        //     // $form->add('username',TextType::class,[
        //     //     "attr"=>[
        //     //         'placeholder'=>"Pseudo",
        //     //         "value"=> $per,
        //     //         'class'=>'form-control'
        //     //     ]
        //     // ]);
        // }
        return $this->renderForm('registration/passwordEd.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/profil/{id}/edit/profil/', name: 'edit_profil_edit', methods: ['GET', 'POST'])]
    public function editProfil(Request $request, Utilisateur $utilisateur, NativeQueryMySQL $native, UtilisateurRepository $utilisateurRepository,$id=null): Response
    {

        
        //dd($utilisateur);
        $form = $this->createForm(RegisterProfileType::class, $utilisateur);
        $form->handleRequest($request);
        
        //dd($utilisateur->getUsername());
        
        if($form->isSubmitted()) {
            $utilisateurRepository->save($utilisateur, true);
            $error = false;
            $this->addFlash(
                'notice',
                "Modification du profile effectuée."
            );
            return $this->redirectToRoute('edit_profil_edit', [
                'id'=>$utilisateur->getId(),
            ]);
        }else{
            $per = $native->getConnection()->query("SELECT u.username FROM utilisateur u WHERE u.id = $id")->fetchOne();
            $form->remove("username")->add('username',TextType::class,[
                "attr"=>[
                    'placeholder'=>"Pseudo",
                    "value"=> $per,
                    'class'=>'form-control'
                ]
            ]);
        }
        return $this->renderForm('registration/profilEd.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

}
