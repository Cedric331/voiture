<?php

namespace App\Controller;

use App\Form\RegisterType;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{

    /**
     * @Route("/user/register", name="user_register")
     */
    public function register(Request $request, EntityManagerInterface $entity, UserPasswordEncoderInterface $passwordEncoder): Response
    {
       $user = new Utilisateur;
       
       $form = $this->createForm(RegisterType::class ,$user);

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid())
       {
         $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
         $user->setRoles('ROLE_USER');
         $entity->persist($user);
         $entity->flush();

         $this->addFlash('success', 'Inscription terminée');
         return $this->redirectToRoute('admin');
       }

        return $this->render('user/register.html.twig', [
           'form' => $form->createView(),
        ]);
    }

   /**
    * @Route("/login", name="login")
    */
    public function login(AuthenticationUtils $auth)
    {
      return $this->render('user/login.html.twig', [
         "lastUserName" => $auth->getLastUsername(),
         "error" => $auth->getLastAuthenticationError()
      ]);
    }

    /**
     * @Route("/logout", name="logout")
     *
     * @return void
     */
    public function lougout(){}
}
