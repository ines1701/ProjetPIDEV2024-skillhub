<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormformationType;
use App\Repository\FormationRepository;
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


public function AfficheFront (FormationRepository $repository)
    {
        $formation=$repository->findAll() ; //select *
        return $this->render('formation/AfficheFront.html.twig',['formation'=>$formation]);
    }





}
