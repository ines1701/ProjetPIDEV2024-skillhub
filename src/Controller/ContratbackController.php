<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Form\Contrat1Type;
use App\Repository\ContratRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/contratback/controller')]
class ContratbackController extends AbstractController
{
    #[Route('/', name: 'app_contratback_controller_index', methods: ['GET'])]
public function index(Request $request, ContratRepository $contratRepository,PaginatorInterface $paginator): Response
{
    $searchQuery = $request->query->get('q');
    $contratQuery = $contratRepository->findAll();
    if ($searchQuery) {
        $contratQuery = $contratRepository->searchContrat($searchQuery);
    }
    $pagination = $paginator->paginate(
        $contratRepository->findAll(), // La requête ou le query builder, pas le résultat
        $request->query->get('page', 1), // Numéro de la page en cours, 1 par défaut
        5 // Nombre de résultats par page
    ); 
    return $this->render('contratback/index.html.twig', [
        'contrats' => $contratQuery, 
        'searchQuery' => $searchQuery, 
        'contrats' => $pagination,
        
    ]);
}


    #[Route('/new', name: 'app_contratback_controller_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contrat = new Contrat();
        $form = $this->createForm(Contrat1Type::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contrat);
            $entityManager->flush();

            return $this->redirectToRoute('app_contratback_controller_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contratbackr/new.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contratback_controller_show', methods: ['GET'])]
    public function show(Contrat $contrat): Response
    {
        return $this->render('contratback/show.html.twig', [
            'contrat' => $contrat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_contratback_controller_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Contrat1Type::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_contratback_controller_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contratback/edit.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contratback_controller_delete', methods: ['POST'])]
    public function delete(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contrat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($contrat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_contratback_controller_index', [], Response::HTTP_SEE_OTHER);
    }
    
}