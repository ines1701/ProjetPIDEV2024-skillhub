<?php

namespace App\Controller;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {

        
        return $this->render('eventFront/front_event.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,EventRepository $eventRepository, PaginatorInterface $paginator): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);



        
        if ($form->isSubmitted() && $form->isValid()) {

 

// Traitement de l'image
$imageFile = $form->get('imageFile')->getData();
if ($imageFile) {
    $imageName = md5(uniqid()).'.'.$imageFile->guessExtension();
    $imageFile->move(
        $this->getParameter('image_directory'),
        $imageName
    );
    $event->setImage($imageName);
}


            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_new', [], Response::HTTP_SEE_OTHER);
        }

    
        return $this->renderForm('event/new.html.twig', [
            'events' => $eventRepository->findAll(),
            'event' => $event,
            'form' => $form,
        ]);
    }

   
    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_event_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_new', [], Response::HTTP_SEE_OTHER);
    }


}
