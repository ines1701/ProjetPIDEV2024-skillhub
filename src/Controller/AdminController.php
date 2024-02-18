<?php

namespace App\Controller;

use App\Form\ProjectFormType;
use App\Repository\CondidatureRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('baseback.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/tps', name: 'app_A')]
    public function Affiche (ProjectRepository $repository, CondidatureRepository $condidature)
        {
            $projet=$repository->findAll() ; //select *
            $condidature=$condidature->findAll() ;
            return $this->render('condidate/tousp.html.twig',['projet'=>$projet , 'condidature'=>$condidature]);
    }
    #[Route('/pdet/{id}', name: 'app_dP')]
    public function showProject($id, ProjectRepository $repository)
{
    $projet = $repository->find($id);
    if (!$projet) {
        return $this->redirectToRoute('app_A');
    }

    return $this->render('condidate/detp.html.twig', ['p' => $projet]);
}
#[Route('/modif/{id}', name: 'app_edit')]
public function edit(ProjectRepository $repository, $id, Request $request,EntityManagerInterface $em)
{
    $projet = $repository->find($id);
    $form = $this->createForm(ProjectFormType::class, $projet);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->flush(); // Correction : Utilisez la méthode flush() sur l'EntityManager pour enregistrer les modifications en base de données.
        return $this->redirectToRoute('app_A');
    }
    return $this->render('condidate/modifp.html.twig', [
        'f' => $form->createView(),
    ]);
}
    #[Route('/delete/{id}', name: 'app_delete')]
    public function delete($id, ProjectRepository $repository,EntityManagerInterface $em)
    {
    $projet = $repository->find($id);
    $em->remove($projet);
    $em->flush();
    return $this->redirectToRoute('app_A');
    }

    #[Route('/delete/{id}', name: 'app_dC')]
    public function deleteCOND($id, CondidatureRepository $repository,EntityManagerInterface $em)
    {
    $condidature = $repository->find($id);
    $em->remove($condidature);
    $em->flush();
    return $this->redirectToRoute('app_A');
    }
}
