<?php

namespace App\Controller;

use App\Entity\Library;
use App\Entity\Vinyl;
use App\Form\VinylType;
use App\Repository\VinylRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/vinyl')]
final class VinylController extends AbstractController
{
    #[Route(name: 'app_vinyl_index', methods: ['GET'])]
    public function index(VinylRepository $vinylRepository): Response
    {
        return $this->render('vinyl/index.html.twig', [
            'vinyls' => $vinylRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_vinyl_new', requirements:  ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Library $library): Response
    {
        $vinyl = new Vinyl();
        $vinyl->setLibrary($library);
        $form = $this->createForm(VinylType::class, $vinyl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($vinyl);
            $entityManager->flush();

            return $this->redirectToRoute('library_show', ['id' => $library->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vinyl/new.html.twig', [
            'vinyl' => $vinyl,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vinyl_show', methods: ['GET'])]
    public function show(Vinyl $vinyl): Response
    {
        return $this->render('vinyl/show.html.twig', [
            'vinyl' => $vinyl,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vinyl_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vinyl $vinyl, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VinylType::class, $vinyl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('library_show', ['id' => $vinyl->getLibrary()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vinyl/edit.html.twig', [
            'vinyl' => $vinyl,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vinyl_delete', methods: ['POST'])]
    public function delete(Request $request, Vinyl $vinyl, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vinyl->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($vinyl);
            $entityManager->flush();
        }

        return $this->redirectToRoute('library_show', ['id' => $vinyl->getLibrary()->getId()], Response::HTTP_SEE_OTHER);
    }
}
