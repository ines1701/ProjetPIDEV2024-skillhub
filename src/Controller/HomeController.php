<?php

namespace App\Controller;

use App\Entity\Condidature;
use App\Entity\Project;
use App\Form\CondidatureFormType;
use App\Form\ProjectFormType;
use App\Repository\CondidatureRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('projectTemp/welcome.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /*#[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('home/projet.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }*/

    #[Route('/all', name: 'app_All')]
    public function Affiche (ProjectRepository $repository)
        {
            $projet=$repository->findAll() ; //select *
            return $this->render('projectTemp/allprojects.html.twig',['projet'=>$projet]);
        }
        
        #[Route('/myprojects', name: 'app_Mine')]
        public function AfficheMine (ProjectRepository $repository)
            {
                $projet=$repository->findAll() ; //select *
                return $this->render('projectTemp/mesprojets.html.twig',['projet'=>$projet]);
            }

    #[Route('/addproject', name: 'app_Addp')]
    public function  Add (EntityManagerInterface $em,Request $request)
    {
        $projet=new Project();
        $form =$this->CreateForm(ProjectFormType::class,$projet);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($projet);
            $em->flush();
            return $this->redirectToRoute('app_All');
        }
        return $this->render('projectTemp/projet.html.twig',['f'=>$form->createView()]);
    }

    #[Route('/edit/{id}', name: 'app_edit')]
    public function edit(ProjectRepository $repository, $id, Request $request,EntityManagerInterface $em)
    {
        $projet = $repository->find($id);
        $form = $this->createForm(ProjectFormType::class, $projet);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush(); // Correction : Utilisez la méthode flush() sur l'EntityManager pour enregistrer les modifications en base de données.
            return $this->redirectToRoute("app_All");
        }
        return $this->render('projectTemp/editproject.html.twig', [
            'f' => $form->createView(),
        ]);
}
#[Route('/delete/{id}', name: 'app_delete')]
public function delete($id, ProjectRepository $repository,EntityManagerInterface $em)
{
    $projet = $repository->find($id);
    $em->remove($projet);
    $em->flush();
    return $this->redirectToRoute('app_All');
}
#[Route('/projectdetails/{id}', name: 'app_detailProject')]
public function showProject($id, ProjectRepository $repository)
{
    $projet = $repository->find($id);
    if (!$projet) {
        return $this->redirectToRoute('app_All');
    }

    return $this->render('projectTemp/detprojet.html.twig', ['p' => $projet]);
}

#[Route('/ShowCondidature', name: 'app_condidature')]
    public function Condidature (ProjectRepository $repository)
        {
            $projet=$repository->findAll() ; //select *
            return $this->render('projectTemp/addcondidature.html.twig',['projet'=>$projet]);
        }


        #[Route('/addCondidature/{id}', name: 'app_condidature', methods: ['GET','POST'])]
        public function  AddCondidature ($id, EntityManagerInterface $em,Request $request, ProjectRepository $projectRepository)
        {
            $condidature=new Condidature();
            $form =$this->CreateForm(CondidatureFormType::class,$condidature);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                $project= $projectRepository->find($id);
                $condidature->setProject($project);
                $em->persist($condidature);
                $em->flush();
                return $this->redirectToRoute('app_All');
            }
            return $this->render('projectTemp/addcondidature.html.twig',['f'=>$form->createView()]);
        }
/*condidature afficher*/

        #[Route('/condidatures', name: 'app_Cond')]
    public function AfficheCond (CondidatureRepository $repository, ProjectRepository $project)
        {
            $condidature=$repository->findAll() ; //select *
            $project=$project->findAll() ;
            return $this->render('projectTemp/mescondi.html.twig',['condidature'=>$condidature, 'project'=>$project]);
        }
        #[Route('/editcond/{id}', name: 'app_editC')]
    public function editCond(CondidatureRepository $repository, $id, Request $request,EntityManagerInterface $em)
    {
        $condidature = $repository->find($id);
        $form = $this->createForm(CondidatureFormType::class, $condidature);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush(); // Correction : Utilisez la méthode flush() sur l'EntityManager pour enregistrer les modifications en base de données.
            return $this->redirectToRoute('app_Cond');
        }
        return $this->render('projectTemp/editcond.html.twig', [
            'f' => $form->createView(),
        ]);
    }
#[Route('/deleteC/{id}', name: 'app_deleteCondidature')]
    public function deleteCOND($id, CondidatureRepository $repository,EntityManagerInterface $em)
    {
    $condidature = $repository->find($id);
    $em->remove($condidature);
    $em->flush();
    return $this->redirectToRoute('app_Cond');
    }

    /*condidature Home pour client*/
    #[Route('/allCond', name: 'app_AllCondidature')]
    public function AfficheCondidatures (CondidatureRepository $condidature)
        {
            $condidature=$condidature->findAll() ;
            return $this->render('projectTemp/allcond.html.twig',['condidature'=>$condidature]);
    }
    #[Route('/deleteC/{id}', name: 'app_deleteCondidature')]
    public function deletecondidature($id, CondidatureRepository $repository,EntityManagerInterface $em)
    {
    $condidature = $repository->find($id);
    $em->remove($condidature);
    $em->flush();
    return $this->redirectToRoute('app_AllCondidature');
    
}
}
    

