<?php

namespace App\Controller;

use App\Entity\Bac;
use App\Entity\Member;
use App\Entity\Vinyl;
use App\Form\BacType;
use App\Repository\BacRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/bac')]
final class BacController extends AbstractController
{
    #[Route(name: 'app_bac_index', methods: ['GET'])]
    public function index(BacRepository $bacRepository): Response
    {
        return $this->render('bac/index.html.twig', [
            'bacs' => $bacRepository->findBy(['public' => true]),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/new/{id}', name: 'app_bac_new', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Member $member): Response
    {
        $bac = new Bac();
        $bac->setOwner($member);
        $form = $this->createForm(BacType::class, $bac);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bac);
            $entityManager->flush();

            return $this->redirectToRoute('member_show', ['username' => $member->getUsername()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bac/new.html.twig', [
            'bac' => $bac,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bac_show', methods: ['GET'])]
    public function show(Bac $bac): Response
    {
        if (!$this->canAccess($bac)) {
            throw $this->createAccessDeniedException("You cannot access the requested ressource!");
        }

        return $this->render('bac/show.html.twig', [
            'bac' => $bac,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/edit', name: 'app_bac_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bac $bac, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BacType::class, $bac);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('member_show', ['username' => $bac->getOwner()->getUsername()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bac/edit.html.twig', [
            'bac' => $bac,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/{id}', name: 'app_bac_delete', methods: ['POST'])]
    public function delete(Request $request, Bac $bac, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bac->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($bac);
            $entityManager->flush();
        }

        return $this->redirectToRoute('member_show', ['username' => $bac->getOwner()->getUsername()], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{bac_id}/vinyl/{vinyl_id}', name: 'app_bac_vinyl_show', requirements: ['bac_id' => '\d+', 'vinyl_id' => '\d+'], methods: ['GET'])]
    public function vinylShow(
        #[MapEntity(id: 'bac_id')]
        Bac $bac,
        #[MapEntity(id: 'vinyl_id')]
        Vinyl $vinyl,
    ) : Response
    {
        if (!$this->canAccess($bac)) {
            throw $this->createAccessDeniedException("You cannot access the requested ressource!");
        }

        if (!$bac->getVinyls()->contains($vinyl)) {
            throw $this->createNotFoundException("Couldn't find such a vinyl in this bac !");
        }

        return $this->render('bac/vinylshow.html.twig', [
            'bac' => $bac,
            'vinyl' => $vinyl,
        ]);
    }

    /**
     * Un bac est consultable s'il est public, par un administrateur, ou par son propriétaire.
     * Pour un visiteur, getUser() vaut null : la comparaison est alors simplement fausse.
     */
    private function canAccess(Bac $bac): bool
    {
        return $bac->isPublic()
            || $this->isGranted('ROLE_ADMIN')
            || $bac->getOwner() === $this->getUser();
    }
}
