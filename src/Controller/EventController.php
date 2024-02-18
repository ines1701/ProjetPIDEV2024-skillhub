<?php

namespace App\Controller;

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
    public function new(Request $request, EntityManagerInterface $entityManager,EventRepository $eventRepository): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

// Traitement de l'image
// Traitement de l'image
$imageFile = $form->get('imageFile')->getData();
$currentImage = $event->getImage(); // Récupérer l'image actuelle de l'événement

if ($imageFile) {
    $imageName = md5(uniqid()).'.'.$imageFile->guessExtension();
    $imageFile->move(
        $this->getParameter('image_directory'),
        $imageName
    );
    $event->setImage($imageName);
} elseif ($currentImage) { // Si aucune nouvelle image n'est téléchargée mais qu'il y a une image actuelle
    // Conserver l'image actuelle
    $event->setImage($currentImage);
}


// Gestion du téléchargement et du stockage de la vidéo
$videoFile = $form->get('video')->getData();
$current_video = $event->getVideo();
if ($videoFile) {
    $videoName = md5(uniqid()).'.'.$videoFile->guessExtension();
    $videoFile->move(
        $this->getParameter('image_directory'),
        $videoName
    );
    $event->setVideo($videoName);
} elseif ($current_video){
    // Si aucune nouvelle vidéo n'est téléchargée, conservez la vidéo existante
    $event->setVideo($current_video);

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

            $imageFile = $form->get('imageFile')->getData();
$currentImage = $event->getImage(); // Récupérer l'image actuelle de l'événement

if ($imageFile) {
    $imageName = md5(uniqid()).'.'.$imageFile->guessExtension();
    $imageFile->move(
        $this->getParameter('image_directory'),
        $imageName
    );
    $event->setImage($imageName);
} elseif ($currentImage) { // Si aucune nouvelle image n'est téléchargée mais qu'il y a une image actuelle
    // Conserver l'image actuelle
    $event->setImage($currentImage);
}


$videoFile = $form->get('video')->getData();
$current_video = $event->getVideo();
if ($videoFile) {
    $videoName = md5(uniqid()).'.'.$videoFile->guessExtension();
    $videoFile->move(
        $this->getParameter('image_directory'),
        $videoName
    );
    $event->setVideo($videoName);
} elseif ($current_video){
    // Si aucune nouvelle vidéo n'est téléchargée, conservez la vidéo existante
    $event->setVideo($current_video);

}
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

    #[Route('/event/{id}', name: 'app_event_details', methods: ['GET'])]
    public function showEventDetails(Event $event): Response
    {
        return $this->render('eventFront/details.html.twig', [
            'event' => $event,
        ]);
    }

}
