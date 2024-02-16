<?php

namespace App\Controller;

use App\Entity\Ressource;
use Vich\UploaderBundle\Handler\UploadHandler;

use App\Form\FormressourceType;
use App\Controller\UploaderInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use App\Repository\RessourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;



class RessourceController extends AbstractController
{
    #[Route('/ressource', name: 'app_ressource')]
    public function index(): Response
    {
        return $this->render('ressource/index.html.twig', [
            'controller_name' => 'RessourceController',
        ]);
    }
    #[Route('/Afficher', name: 'app_Afficher')]

    public function Affiche (RessourceRepository $repository)
        {
            $ressource=$repository->findAll() ; //select *
            return $this->render('ressource/Affiche.html.twig',['ressource'=>$ressource]);
        }
    #[Route('/Addr', name: 'app_Addr')]

    public function Add(EntityManagerInterface $em, Request $request, UploadHandler $uploadHandler, SluggerInterface $slugger): Response
    {
        $ressource = new Ressource();
        $form = $this->createForm(FormressourceType::class, $ressource);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('ressource_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                
                $ressource->setFilename($newFilename);
            }
            $em->persist($ressource);
            $em->flush();
            return $this->redirectToRoute('app_Afficher');
        }

        return $this->render('ressource/Add.html.twig', [
            'form' => $form->createView()]);
    }



    #[Route('/editr/{id}', name: 'app_editr')]
    public function edit(RessourceRepository $repository, $id, Request $request,EntityManagerInterface $em)
    {
        $ressource = $repository->find($id);
        $form = $this->createForm(FormressourceType::class, $ressource);
        $form->add('Edit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
            $em->flush(); // Correction : Utilisez la méthode flush() sur l'EntityManager pour enregistrer les modifications en base de données.
            return $this->redirectToRoute("app_Afficher");
        }
        return $this->render('ressource/edit.html.twig', [
            'f' => $form->createView(),
        ]);
}
#[Route('/deleter/{id}', name: 'app_deleter')]
public function delete($id, RessourceRepository $repository,EntityManagerInterface $em)
{
    $ressource = $repository->find($id);
    $em->remove($ressource);
    $em->flush();
    return $this->redirectToRoute('app_Afficher');
}

#[Route('/AfficherFront', name: 'app_AfficherFront')]


public function AfficherFront (RessourceRepository $repository)
    {
        $ressource=$repository->findAll() ; //select *
        return $this->render('ressource/AfficheFront.html.twig',['ressource'=>$ressource]);
    }





}
