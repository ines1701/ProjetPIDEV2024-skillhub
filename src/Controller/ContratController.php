<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Form\ContratType;
use App\Repository\ContratRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/contrat')]
class ContratController extends AbstractController
{
    private $transactionRepository;

    public function __construct(ContratRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }
    #[Route('/', name: 'app_contrat_index', methods: ['GET'])]
public function index(Request $request, ContratRepository $contratRepository): Response
{
    $contratQuery = $contratRepository->findAll(); 
    $currentPage = $request->query->getInt('page', 1); 
    $perPage = 5; 
    $totalcontrat = count($contratQuery); 
    $totalPages = ceil($totalcontrat / $perPage); 
    $offset = ($currentPage - 1) * $perPage; 
    $contrats = array_slice($contratQuery, $offset, $perPage); 

    return $this->render('contrat/index.html.twig', [
        'contrats' => $contrats, 
        'totalcontrat' => $totalcontrat,
        'perPage' => $perPage,
        'currentPage' => $currentPage,
        'totalPages' => $totalPages,
    ]);
}


    #[Route('/new', name: 'app_contrat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contrat = new Contrat();
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);
        $this->addFlash('success', ' New contrat Added.');
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('contrat')['image'];
                // $file=$jeux->getImagejeux();
                $uploads_directory = $this->getParameter('uploads_directory');
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $uploads_directory,
                    $filename
                );
                $contrat->setImage($filename);
            $entityManager->persist($contrat);
            $entityManager->flush();

            return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
        }
        

        return $this->renderForm('contrat/new.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contrat_show', methods: ['GET'])]
    public function show(Contrat $contrat): Response
    {
        return $this->render('contrat/show.html.twig', [
            'contrat' => $contrat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_contrat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $contrat->getImage();
            $contrat->setImage($image);
            $entityManager->flush();


            return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contrat/edit.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contrat_delete', methods: ['POST'])]
    public function delete(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contrat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($contrat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
    }

        public function printPdf(): Response
        {
            // Configure Dompdf according to your needs
            $pdfOptions = new Options();
            $pdfOptions->set('defaultFont', 'Arial');
            $pdfOptions->setIsRemoteEnabled(true);
    
            // Instantiate Dompdf with our options
            $dompdf = new Dompdf($pdfOptions);
            $contrats = $this->transactionRepository->findAll();
            
            // Generate the HTML content
            $html = $this->renderView('contrat/print.html.twig', ['contrats' => $contrats]);
    
            // Load HTML to Dompdf
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait'); // Setup the paper size and orientation
            $dompdf->render(); // Render the HTML as PDF
    
            $filename = sprintf('contrat-%s.pdf', date('Y-m-d_H-i-s'));
    
            // Output the generated PDF to Browser (force download)
            return new Response($dompdf->stream($filename, ["Attachment" => true]));
        }
        #[Route('/searchByNomclient', name: 'search_by_nomclient', methods: ['GET'])]
    public function searchByNomclient(Request $request, ContratRepository $contratRepository): JsonResponse
    {
        $nomclient = $request->query->get('nom_client');
    
        // Recherche des logements par adresse
        $contrats = $contratRepository->findByNomclient($nomclient);
    
        // Convertir les entités Logement en tableau pour la réponse JSON
        $results = [];
        foreach ($contrats as $contrat) {
            $results[] = [
                'id' => $contrat->getId(),
                'nom_client' => $contrat->getDescription(),
                'date_contrat' => $contrat->getDateContrat(),
                'montant' => $contrat->getMontant(),
                'description' => $contrat->getDescription(),
                'image' => $contrat->getImage()
            ];
        }
    
        return $this->json($results);
    }
    #[Route('/loadAllContrats', name: 'load_all_contrats', methods: ['GET'])]
public function loadAllLogements(ContratRepository $contratRepository): JsonResponse
{
    // Récupérer tous les logements depuis le repository
    $contrats = $contratRepository->findAll();

    // Convertir les entités Logement en tableau pour la réponse JSON
    $results = [];
    foreach ($contrats as $contrat) {
        $results[] = [
                'id' => $contrat->getId(),
                'nom_client' => $contrat->getDescription(),
                'date_contrat' => $contrat->getDateContrat(),
                'montant' => $contrat->getMontant(),
                'description' => $contrat->getDescription(),
                'image' => $contrat->getImage()
        ];
    }

    return $this->json($results);
}
}
