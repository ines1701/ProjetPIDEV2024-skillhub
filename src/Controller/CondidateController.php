<?php

namespace App\Controller;

use App\Entity\Condidature;
use App\Form\CondidatureFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CondidateController extends AbstractController
{
    #[Route('/condidate', name: 'app_condidate')]
    public function index(): Response
    {
        return $this->render('condidate/index.html.twig', [
            'controller_name' => 'CondidateController',
        ]);
    }

    #[Route('/addCondidature', name: 'app_condidature')]
        public function  AddCondidature (EntityManagerInterface $em,Request $request)
        {
            $condidature=new Condidature();
            $form =$this->CreateForm(CondidatureFormType::class,$condidature);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())
            {
                $em->persist($condidature);
                $em->flush();
                return $this->redirectToRoute('app_All');
            }
            return $this->render('projectTemp/addcondidature.html.twig',['c'=>$form->createView()]);
        }
}
