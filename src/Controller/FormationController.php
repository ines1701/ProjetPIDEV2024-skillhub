<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Ressource;
use App\Form\FormformationType;
use App\Repository\FormationRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\RessourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;






class FormationController extends AbstractController
{
    
    #[Route('/formation', name: 'app_formation')]
    public function index(): Response
    {
        return $this->render('formation/index.html.twig', [
            'controller_name' => 'FormationController',
        ]);
    }
    #[Route('/showAuthor/{name}', name: 'app_showAuthor')]

    public function showAuthor ($name)
    {
        return $this->render('formation/show.html.twig',['n'=>$name]);

    }
    #[Route('/Affiche', name: 'app_Affiche')]


    public function Affiche (FormationRepository $repository)
        {
            $formation=$repository->findAll() ; //select *
            return $this->render('formation/Affiche.html.twig',['formation'=>$formation]);
        }
        #[Route('/Add', name: 'app_Add')]

    public function  Add (EntityManagerInterface $em,Request $request)
    {
        $formation=new Formation();
        $form =$this->CreateForm(FormformationType::class,$formation);
      $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($formation);
            $em->flush();
            return $this->redirectToRoute('app_Affiche');
        }
        return $this->render('formation/Add.html.twig',['f'=>$form->createView()]);
    
    }
    #[Route('/edit/{id}', name: 'app_edit')]
    public function edit(FormationRepository $repository, $id, Request $request,EntityManagerInterface $em)
    {
        $formation = $repository->find($id);
        $form = $this->createForm(FormformationType::class, $formation);
        $form->add('Edit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
            $em->flush(); // Correction : Utilisez la méthode flush() sur l'EntityManager pour enregistrer les modifications en base de données.
            return $this->redirectToRoute("app_Affiche");
        }
        return $this->render('formation/edit.html.twig', [
            'f' => $form->createView(),
        ]);
}
#[Route('/delete/{id}', name: 'app_delete')]
public function delete($id, FormationRepository $repository,EntityManagerInterface $em)
{
    $formation = $repository->find($id);
    $em->remove($formation);
    $em->flush();
    return $this->redirectToRoute('app_Affiche');
}
#[Route('/AfficheFront', name: 'app_AfficheFront')]
public function AfficheFront(FormationRepository $repository, PaginatorInterface $paginator, Request $request): Response
{
    $categories = $repository->findDistinctCategories();
    
    // Récupérer le paramètre de catégorie de l'URL
    $categorie = $request->query->get('categorie');

    // Si une catégorie est sélectionnée, filtrer les formations par cette catégorie
    if ($categorie) {
        $formations = $repository->findBy(['categorie' => $categorie]);
    } else {
        // Sinon, afficher toutes les formations
        $formations = $repository->findAll();
    }

    $pagination = $paginator->paginate(
        $formations,
        $request->query->getInt('page', 1), // numéro de page par défaut
        3 // nombre d'éléments par page
    );

    return $this->render('formation/AfficheFront.html.twig', [
        'formation' => $pagination,
        'categories' => $categories,
        'categorieSelectionnee' => $categorie,
    ]);
}




    #[Route('/formation/{id}/ressources', name: 'formation_ressources')]


    public function formationRessources($id, EntityManagerInterface $entityManager)
    {
        // Récupérer l'objet Formation correspondant à l'ID
        $formation = $entityManager->getRepository(Formation::class)->find($id);

        // Vérifier si la formation existe
        if (!$formation) {
            throw $this->createNotFoundException('Formation non trouvée.');
        }

        // Récupérer les ressources associées à la formation
        $ressources = $formation->getRessources();

        // Afficher la vue avec les ressources
        return $this->render('formation/ressources.html.twig', [
            'formation' => $formation,
            'ressources' => $ressources,
        ]);

    }

    #[Route('/formationFront/{id}/ressources', name: 'formationFront_ressources')]


    public function formationRessourcesFront($id, EntityManagerInterface $entityManager)
    {
        // Récupérer l'objet Formation correspondant à l'ID
        $formation = $entityManager->getRepository(Formation::class)->find($id);

        // Vérifier si la formation existe
        if (!$formation) {
            throw $this->createNotFoundException('Formation non trouvée.');
        }

        // Récupérer les ressources associées à la formation
        $ressources = $formation->getRessources();



        // Afficher la vue avec les ressources
        return $this->render('formation/ressourcesFront.html.twig', [
            'formation' => $formation,
            'ressources' => $ressources,
        ]);
    }
    #[Route('/ajouter-favoris/{id}', name: 'ajouter_favoris')]
public function ajouterFavoris(int $id, Request $request): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $formation = $entityManager->getRepository(Formation::class)->find($id);

    if (!$formation) {
        throw $this->createNotFoundException('Formation non trouvée.');
    }

    // Marquer la formation comme favori
    $formation->setFavoris(true);
    $entityManager->flush();

    // Rediriger vers la page des formations
    return $this->redirectToRoute('app_AfficheFront');
}
#[Route('/favoris', name: 'favoris')]
public function favoris(FormationRepository $formationRepository): Response
{
    $favoris = $formationRepository->findBy(['favoris' => true]);

    return $this->render('formation/favoris.html.twig', [
        'favoris' => $favoris,
    ]);
}



#[Route('/retirer-favoris/{id}', name: 'retirer_favoris', methods: ['POST'])]
public function retirerFavoris(Request $request, $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $formation = $entityManager->getRepository(Formation::class)->find($id);

    if (!$formation) {
        throw $this->createNotFoundException('Formation non trouvée.');
    }

    // Retirer la formation des favoris
    $formation->setFavoris(false);
    $entityManager->flush();

    // Rediriger vers la page des favoris
    return $this->redirectToRoute('favoris');
}

}



  












