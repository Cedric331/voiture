<?php

namespace App\Controller;

use App\Form\RegisterType;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    /**
     * @Route("/user/register", name="user_register")
     */
    public function register(Request $request, EntityManagerInterface $entity): Response
    {
       $user = new Utilisateur;
       
       $form = $this->createForm(RegisterType::class ,$user);

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid())
       {
         $entity->persist($user);
         $entity->flush();

         $this->addFlash('success', 'Inscription terminée');
         return $this->redirectToRoute('admin');
       }

        return $this->render('user/register.html.twig', [
           'form' => $form->createView(),
        ]);
    }
}
