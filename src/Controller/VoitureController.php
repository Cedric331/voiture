<?php

namespace App\Controller;

use App\Repository\VoitureRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ContainerPvVqP2U\PaginatorInterface_82dac15;
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
      //  $voitures = $this->repository->findAll();

       $voitures = $paginator->paginate(
         $this->repository->findAllPagination(),
         $request->query->getInt('page', 1), /*page number*/
         6 /*limit per page*/
     );

        return $this->render('voiture/voitures.html.twig',[
           'voitures' => $voitures
        ]);
    }
}
