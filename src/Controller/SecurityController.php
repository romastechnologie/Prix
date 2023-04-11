<?php

namespace App\Controller;

use App\Database\NativeQueryMySQL;
use App\Entity\Role;
use App\Entity\Utilisateur;
use App\Repository\RoleRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils,NativeQueryMySQL $native, RoleRepository $roleRepository, UserPasswordHasherInterface $userPasswordHasher, UtilisateurRepository $userRep): Response
    {
       // dd("OK");
       
       if(!$roleRepository->findAll()){
        if(!$roleRepository->findOneBy(["role"=>"ROLE_ADMIN", "nom"=>"ROLE ADMIN"])){
            $role1 = new Role();
            $role1->setNom("ROLE ADMIN")->setRole("ROLE_ADMIN");
            $roleRepository->save($role1,true);
            $role = new Role();
            $role->setNom("ROLE OPERATEUR")->setRole("ROLE_OPERATEUR");
            $roleRepository->save($role,true);
            $role2 = new Role();
            $role2->setNom("ROLE USER")->setRole("ROLE_USER");
            $roleRepository->save($role2,true);
        }

       }
        if($userRep->findAll() == NULL ){
            if(!$userRep->findOneBy(["email"=>"admin@gmail.com", "nom"=>"Admin"])){
                $user = new Utilisateur();
                $user->setNom("Admin")->setEmail("admin@gmail.com")->setPrenom("Admin")->setRoles(["ROLE_ADMIN"]);
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        'Admin123456'
                    )
                );
                $userRep->save($user, true);
                $sql = "INSERT INTO `utilisateur_role` (`utilisateur_id`, `role_id`) VALUES ('1', '1')";
                $native->getConnection()->query($sql)->fetch();
            }         
        }
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
             'error' => $error
            ]);
    }

    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
