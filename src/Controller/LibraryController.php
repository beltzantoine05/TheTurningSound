<?php

namespace App\Controller;

use App\Entity\Library;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class LibraryController extends AbstractController
{
    #[Route('/library_list', name: 'library_list')]
    public function listLibraries(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $libraries = $entityManager->getRepository(Library::class)->findAll();
        dump($libraries);

        return $this->render('library/index.html.twig', [
            'libraries' => $libraries,
        ]);
    }

    /**
     * Show a library
     *
     * @param Integer $id (note that the id must be an integer)
     */
    #[Route('/library/{id}', name: 'library_show', requirements: ['id' => '\d+'])]
    public function show(ManagerRegistry $doctrine, $id) : Response
    {

        // Chargement d'un [inventaire] par son identifiant
        $LibraryRepo = $doctrine->getRepository(Library::class);
        $library = $LibraryRepo->find($id);

        // Si l'identifiant est erroné, on s'arrête brutalement : 404
        if (!$library) {
            throw $this->createNotFoundException('The Library does not exist');
        }

        return $this->render('library/show.html.twig', [
            'library' => $library,
            'vinyls' => $library->getVinyls(),
        ]);
}


}
