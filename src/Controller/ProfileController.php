<?php

namespace App\Controller;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

use Symfony\Component\HttpFoundation\File\UploadedFile;
class ProfileController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    #[Route('/profile', name: 'profile')]
    public function showProfile(): Response
    {
        $user = $this->getUser(); // Récupère l'utilisateur connecté
        if (!$user) {
            $message = 'No user is connected.';
            return new Response($message);
        }

        return $this->render('profile/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile/edit', name: 'profile_edit')]
    public function editProfile(Request $request): Response
    {
        
        $userId = $this->getUser(); // Get the ID of the currently logged-in user
        // Retrieve the user from the repository based on their ID
        $user = $this->userRepository->find($userId);


        // Traitement du formulaire de modification du profil
        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();

            // Récupérer les valeurs des champs du formulaire
            $firstName = $request->request->get('firstName');
            $lastName = $request->request->get('lastName');
            $skills = $request->request->get('skills');
            $experience = $request->request->get('experience');

            // Mettre à jour les propriétés de l'utilisateur
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setSkills($skills);
            $user->setExperience($experience);

            // Gérer le téléchargement de l'image de profil
            /** @var UploadedFile $profilePictureFile */
            $profilePictureFile = $request->files->get('profilePicture');
            if ($profilePictureFile) {
                // Générer un nom de fichier unique
                $newFilename = uniqid().'.'.$profilePictureFile->guessExtension();

                // Déplacer le fichier téléchargé vers le répertoire de stockage des images
                $profilePictureFile->move(
                    $this->getParameter('profile_picture_directory'),
                    $newFilename
                );

                // Mettre à jour le chemin d'accès de l'image de profil
                $user->setProfilePicture($newFilename);
            }

            // Enregistrer les modifications dans la base de données
            $entityManager->flush();

            // Rediriger l'utilisateur vers la page de profil
            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/edit.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/profile/qr', name: 'profile_qr')]
    public function profileQr(): Response
    {
        $userId = $this->getUser(); // Get the ID of the currently logged-in user
        // Retrieve the user from the repository based on their ID
        $user = $this->userRepository->find($userId);
        if (!$user) {
            return new Response('No user is connected.', 404);
        }
    
        // Construire les données à encoder dans le QR code
        // Exemple : nom de l'utilisateur, email, etc.
        
        $data = sprintf(
          
            $user->getFirstName().' '.$user->getLastName(),
            $user->getEmail(),
            $user->getExperience(),
        );
        
        // Générer le QR code
        $qrCode = new QrCode($data);
        $qrCode->setSize(300); // Taille du QR code en pixels
    
        // Vous pouvez personnaliser votre QR code ici (taille, couleur, etc.)
    
        $writer = new PngWriter();
        // Générer l'image du QR code
        $qrCodeImage = $writer->write($qrCode)->getString();
    
        // Créer une réponse avec l'image du QR code
        $response = new Response($qrCodeImage);
        $response->headers->set('Content-Type', 'image/png');
    
        return $response;
    }
}