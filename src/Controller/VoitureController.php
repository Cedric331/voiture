<?php

namespace App\Controller;

use App\Entity\RechercheVoiture;
use App\Form\RechercheVoitureType;
use App\Repository\VoitureRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VoitureController extends AbstractController
{

   private $repository;

   public function __construct(VoitureRepository $repository){
      $this->repository = $repository;
   }

    /**
     * @Route("/voitures", name="voitures")
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
         $this->repository->getVoitureAnnee($min, $max),
         $request->query->getInt('page', 1),
         6 /*limit per page*/
     );

        return $this->render('voiture/voitures.html.twig',[
           'voitures' => $voitures,
           'form' => $form->createView(),
           'admin' => false
        ]);
    }

    /**
     * @Route("/session/reset", name="session_reset")
     *
     * @param Request $request
     * @return void
     */
    public function resetSession(Request $request)
    {
      $session = $request->getSession();

         $session->set('min', null);
         $session->set('max', null);

      return $this->redirectToRoute('voitures');
    }

      /**
     * @Route("/voitures", name="voitures_recherche", methods="POST")
     */
   //  public function recherche(PaginatorInterface $paginator, Request $request)
   //  {
   //    $Listvoiture = $this->repository->getVoitureAnnee($request->get('annee1'), $request->get('annee2'));

   //    $voitures = $paginator->paginate(
   //       $Listvoiture,
   //       $request->query->getInt('page', 1),
   //       6 /*limit per page*/
   //   );

   //    return $this->render('voiture/voitures.html.twig',[
   //       'voitures' => $voitures
   //    ]);
   //  }
}
