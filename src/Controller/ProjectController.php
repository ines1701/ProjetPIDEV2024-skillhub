<?php

namespace App\Controller;

use App\Entity\Condidature;
use App\Entity\Project;
use App\Form\CondidatureFormType;
use App\Form\ProjectFormType;
use App\Repository\CondidatureRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProjectController extends AbstractController
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
        public function Affiche (ProjectRepository $repository, PaginatorInterface $paginator, Request $request)
        {
            $projets=$repository->findAll() ; //select *
            $projet = $paginator->paginate(
                $projets,
                $request->query->getInt('page',1),
                limit: 3,
            );
            $totalProjects = count($projets);
            return $this->render('projectTemp/allprojects.html.twig',['projet'=>$projet, 'totalProjects' => $projets,]);
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
#[Route('/deleteFront/{id}', name: 'app_deleteFront')]
public function delete($id, ProjectRepository $repository,EntityManagerInterface $em)
{
$projet = $repository->find($id);

if (!$projet) {
    throw $this->createNotFoundException('Project not found');
}

// Fetch associated condidatures
$condidatures = $projet->getCondidatures();

// Remove each condidature
foreach ($condidatures as $condidature) {
    $em->remove($condidature);
}

$em->remove($projet);
$em->flush();
return $this->redirectToRoute('app_All');
}
#[Route('/projectdetails/{id}', name: 'app_detailProject')]
public function showProject($id, ProjectRepository $repository, CondidatureRepository $condidatureRepository, Security $security)
{
    $projet = $repository->find($id);
    if (!$projet) {
        return $this->redirectToRoute('app_All');
    }
    $user = $security->getUser();
    $hasCondidature = false;
    $condidature = null;

    if ($user) {
        // Check if the user has already submitted a condidature for this project
        $condidature = $condidatureRepository->findOneBy(['project' => $projet, 'user' => $user]);

        if ($condidature) {
            $hasCondidature = true;
        }
    }

    return $this->render('projectTemp/detprojet.html.twig', [
        'p' => $projet,
        'hasCondidature' => $hasCondidature,
        'condidature' => $condidature,
    ]);
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
                $project= $projectRepository->find($id);

                $condidature=new Condidature();
                $condidature->setProject($project);
                $form =$this->CreateForm(CondidatureFormType::class,$condidature);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid())
                {
                    
                    
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
    return $this->redirectToRoute('app_Cond');
    
}
#[Route('/condProject/{id}', name: 'app_condidatureproject')]
    public function AfficheCondProject (ProjectRepository $repository, CondidatureRepository $condidatureRep, $id)
        {
            $project=$repository->find($id) ; //select *
            $condidature=$condidatureRep->showAllCondidaturesByProject($id) ;
            
            return $this->render('projectTemp/detcondidature.html.twig',['p'=>$project , 'condidature'=>$condidature]);
}


#[Route('/acceptCond/{projectId}/{condidatureId}', name: 'app_accept_condidature', methods: ['GET'])]
public function acceptCondidature($projectId, $condidatureId, EntityManagerInterface $em): Response
{
    $condidature = $this->getDoctrine()->getRepository(Condidature::class)->find($condidatureId);

    if (!$condidature) {
        throw $this->createNotFoundException('Condidature not found.');
    }

    // Add logic to handle acceptance
    $condidature->setStatus('Acceptée');
    $em->flush();

    // Redirect or return a response based on your requirements
    return $this->redirectToRoute('app_detailProject', ['id' => $projectId]);
}

#[Route('/refuseCond/{projectId}/{condidatureId}', name: 'app_refuse_condidature', methods: ['GET'])]
public function refuseCondidature($projectId, $condidatureId, EntityManagerInterface $em): Response
{
    $condidature = $this->getDoctrine()->getRepository(Condidature::class)->find($condidatureId);

    if (!$condidature) {
        throw $this->createNotFoundException('Condidature not found.');
    }

    // Add logic to handle refusal
    $condidature->setStatus('Refusée');
    $em->flush();

    // Redirect or return a response based on your requirements
    return $this->redirectToRoute('app_detailProject', ['id' => $projectId]);
}

#[Route('/live-search', name: 'app_live_search')]
public function liveSearch(Request $request, ProjectRepository $projectRepository)
{
    $query = $request->query->get('query');
    $projects = $projectRepository->searchProjects($query);

    $formattedResults = [];

    foreach ($projects as $project) {
        $formattedResults[] = [
            'url' => $this->generateUrl('app_detailProject', ['id' => $project->getId()]),
            'title' => $project->getTitre(),
            'categorie' => $project->getCategorie() ?? 'N/A',
            'createdAt' => $project->getCreatedAt() ? $project->getCreatedAt()->format('d-m-Y') : 'N/A',
            // Add more fields as needed
        ];
    }

    return new JsonResponse($formattedResults);
}



}
    

