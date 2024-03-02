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

class ProjectBackController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('baseback.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/tps', name: 'app_AP')]
    public function AfficheP (ProjectRepository $repository)
        {
            $projet=$repository->findAll() ; //select *
            return $this->render('condidate/tousp.html.twig',['projet'=>$projet]);
    }
    #[Route('/tousC', name: 'app_AC')]
    public function AfficheC (CondidatureRepository $condidature)
        {
            $condidature=$condidature->findAll() ;
            return $this->render('condidate/tousc.html.twig',['condidature'=>$condidature]);
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

    #[Route('/deleteFromAdmin/{id}', name: 'app_deleteFA')]
    public function deleteProjet($id, ProjectRepository $repository,EntityManagerInterface $em)
    {
    $projet = $repository->find($id);
    $em->remove($projet);
    $em->flush();
    return $this->redirectToRoute('app_AP');
    }

    #[Route('/delete/{id}', name: 'app_dC')]
    public function deleteCOND($id, CondidatureRepository $repository,EntityManagerInterface $em)
    {
    $condidature = $repository->find($id);
    $em->remove($condidature);
    $em->flush();
    return $this->redirectToRoute('app_AC');
    }
}
