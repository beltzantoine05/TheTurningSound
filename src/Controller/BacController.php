<?php

namespace App\Controller;

use App\Entity\Bac;
use App\Entity\Vinyl;
use App\Form\BacType;
use App\Repository\BacRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/bac')]
final class BacController extends AbstractController
{
    #[Route(name: 'app_bac_index', methods: ['GET'])]
    public function index(BacRepository $bacRepository): Response
    {
        return $this->render('bac/index.html.twig', [
            'bacs' => $bacRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_bac_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $bac = new Bac();
        $form = $this->createForm(BacType::class, $bac);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bac);
            $entityManager->flush();

            return $this->redirectToRoute('app_bac_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bac/new.html.twig', [
            'bac' => $bac,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bac_show', methods: ['GET'])]
    public function show(Bac $bac): Response
    {
        return $this->render('bac/show.html.twig', [
            'bac' => $bac,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bac_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bac $bac, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BacType::class, $bac);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_bac_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bac/edit.html.twig', [
            'bac' => $bac,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bac_delete', methods: ['POST'])]
    public function delete(Request $request, Bac $bac, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bac->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($bac);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_bac_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Show a vinyl
     *
     * @param Integer $id (note that the id must be an integer)
     */
    #[Route('/vinyl/{id}', name: 'app_bac_vinyl_show', requirements: ['id' => '\d+'])]
    public function vinylShow(ManagerRegistry $doctrine, $id) : Response
    {

        // Chargement d'un vinyl par son identifiant
        $VinylRepo = $doctrine->getRepository(Vinyl::class);
        $vinyl = $VinylRepo->find($id);

        // Si l'identifiant est erroné, on s'arrête brutalement : 404
        if (!$vinyl) {
            throw $this->createNotFoundException('The vinyl you are searching for does not exist');
        }

        return $this->render('bac/vinylshow.html.twig', [
            'vinyl' => $vinyl,
        ]);
    }
}
