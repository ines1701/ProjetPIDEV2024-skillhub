<?php

namespace App\Controller;
use App\Service\EventService;
use App\Entity\Event;
use App\Entity\Inscrip;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Gedmo\Translatable\TranslatableListener;
use Knp\Component\Pager\PaginatorInterface;
use Tattali\CalendarBundle\TattaliCalendarBundle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;





#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, EventRepository $eventRepository,Request $request): Response
    {

        $searchQuery = $request->query->get('search');

        $EventQuery = $eventRepository->searchByCriteria($searchQuery);

        $pagination = $paginator->paginate(
            $EventQuery, // Remplacez cette ligne avec votre requête pour récupérer les événements
            $request->query->getInt('page', 1), // Numéro de page par défaut
            3 // Nombre d'éléments par page
        );
    

        return $this->render('eventFront/front_event.html.twig', [
            'events' => $pagination,
        ]);
    }





    
    #[Route('/events', name: 'events', methods: ['GET'])]
    public function events(): Response
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();

        $data = [];
        foreach ($events as $event) {
            $data[] = [
                'title' => $event->getTitre(),
                'start' => $event->getDate()->format('Y-m-d H:i:s'),
            ];
        }

        return $this->render('eventFront/calendar.html.twig', [
            'events' => json_encode($data),
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


    
    public function incrementViews(Event $event, EntityManagerInterface $entityManager)
    {
        $event->setView ($event->getView () + 1);
        $entityManager->flush();
    
        return new Response(null, Response::HTTP_OK);
    }
   



    #[Route('/event/{id}', name: 'app_event_details', methods: ['GET'])]
    public function showEventDetails(Event $event, EntityManagerInterface $entityManager): Response
    {

         // Incrémentez le nombre de vues chaque fois que la page est consultée
    $event->incrementViews();
    $entityManager->flush(); // Enregistrez les modifications
       
        return $this->render('eventFront/details.html.twig', [
            'event' => $event,
        ]);
    }


    #[Route('/Inscrip/{id}', name: 'app_event_Inscrip', methods: ['POST'])]
    public function submitInscription(Request $request, Event $event): Response
    {
        $email = $request->request->get('email');
        $num = $request->request->get('num');

        // Vérifiez si l'email est valide (vous pouvez également ajouter d'autres validations ici)

        // Créer une nouvelle inscription pour l'événement
        $Inscrip = new Inscrip ();
        $Inscrip ->setEmail($email);
        $Inscrip ->setnum($num);
        $Inscrip ->setEvent($event);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($Inscrip );
        $entityManager->flush();

        // Rediriger l'utilisateur vers une page de confirmation ou de retour à la page de détails de l'événement
        return $this->redirectToRoute('app_event_details', ['id' => $event->getId()]);
    }

    #[Route('/Inscrip/{id}/delete', name: 'app_event_inscription_delete', methods: ['POST'])]
    public function deleteInscription(Inscrip $Inscrip, Request $request): Response
    {
        // Vérifier que l'utilisateur a le droit de supprimer l'inscription (à ajouter)

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($Inscrip);
        $entityManager->flush();

        // Rediriger vers la page précédente ou une autre page (à définir)
        return $this->redirectToRoute('app_event_show', ['id' => $Inscrip->getEvent()->getId()]);
    }

    #[Route('/show/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        $Inscrip = $event->getInscription();

        return $this->render('event/show.html.twig', [
            'id' => $event->getId(),
            'event' => $event,
            'Inscrip' => $Inscrip,
        ]);
    }


   
    #[Route('/Inscrip/{id}/send', name: 'app_event_send', methods: ['POST'])]
    public function sendConfirmationEmail(Request $request, MailerInterface $mailer, Event $event): Response
    {
     
     $Inscrip = new Inscrip ();
            $email = $Inscrip->getEmail();
        
            // Vérifier si l'adresse e-mail est valide
            if ($email) {
                // Envoyer l'e-mail de confirmation
                

                if ($form->isSubmitted() && $form->isValid()) {
                $email = (new Email())
                ->from('hello@example.com')
                ->to('you@example.com')
                ->subject('Time for Symfony Mailer!')
                ->text('Sending emails is fun again!')
                ->html('<p>See Twig integration for better HTML integration!</p>');
    
            $mailer->send($email);}
        
         } 
        
    
        return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
    }
    
}