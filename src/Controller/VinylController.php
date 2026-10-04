<?php

namespace App\Controller;

use App\Entity\Vinyl;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class VinylController extends AbstractController
{
    #[Route('/vinyl_list', name: 'vinyl_list')]
    public function listLibraries(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $vinyls = $entityManager->getRepository(Vinyl::class)->findAll();
        dump($vinyls);

        return $this->render('vinyl/index.html.twig', [
            'vinyls' => $vinyls,
        ]);
    }
    /**
     * Show a vinyl
     *
     * @param Integer $id (note that the id must be an integer)
     */
    #[Route('/vinyl/{id}', name: 'vinyl_show', requirements: ['id' => '\d+'])]
    public function show(ManagerRegistry $doctrine, $id) : Response
    {

        // Chargement d'un vinyl par son identifiant
        $VinylRepo = $doctrine->getRepository(Vinyl::class);
        $vinyl = $VinylRepo->find($id);

        // Si l'identifiant est erroné, on s'arrête brutalement : 404
        if (!$vinyl) {
            throw $this->createNotFoundException('The vinyl you are searching for does not exist');
        }

        return $this->render('vinyl/show.html.twig', [
            'vinyl' => $vinyl,
        ]);
    }
}
