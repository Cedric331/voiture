<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Entity\RechercheVoiture;
use App\Form\RechercheVoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{

   private $repo;

   public function __construct(VoitureRepository $repo)
   {
      $this->repo = $repo;
   }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
      $rechercheVoiture = new RechercheVoiture();
      $session = $request->getSession();

       $form = $this->createForm(RechercheVoitureType::class ,$rechercheVoiture);
       
       $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
         $session->set('min', $rechercheVoiture->getminAnnee());
         $session->set('max', $rechercheVoiture->getmaxAnnee());
      }
      $min = $session->get('min');
      $max = $session->get('max');

       $voitures = $paginator->paginate(
         $this->repo->getVoitureAnnee($min, $max),
         $request->query->getInt('page', 1),
         6 /*limit per page*/
     );

        return $this->render('voiture/voitures.html.twig',[
           'form' => $form->createView(),
           'voitures' => $voitures,
           'admin' => true
        ]);
    }

    /**
     * @Route("/voiture/admin/create", name="admin_create")
     * @Route("/voiture/admin/{id}", name="admin_update")
     */
    public function update(Voiture $voiture = null, Request $request, EntityManagerInterface $entity)
    {
       if (!$voiture) {
          $voiture = new Voiture();
       }

       $form = $this->createForm(VoitureType::class ,$voiture);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
          $entity->persist($voiture);
          $entity->flush();
          $this->addFlash('success', 'Modification effectué');
          return $this->redirectToRoute('admin');
       }

       return $this->render('admin/update.html.twig',[
         'form' => $form->createView(),
         'voiture' => $voiture,
         'isUpdate' => $voiture->getId() != null,
         'admin' => true
      ]);
    }

      /**
     * @Route("/voiture/admin/delete/{id}", name="admin_delete")
     */
    public function delete(Voiture $voiture, Request $request, EntityManagerInterface $entity)
    {
      if( $this->isCsrfTokenValid('DEL'. $voiture->getId(), $request->get('_token')) )
      {
         $entity->remove($voiture);
         $entity->flush();

         $this->addFlash('success', 'Voiture supprimée');
         return $this->redirectToRoute('admin');
      }
    }
}
