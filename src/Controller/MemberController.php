<?php

namespace App\Controller;

use App\Entity\Member;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MemberController extends AbstractController
{
    #[Route('/member_list', name: 'member_list')]
    public function listMembers(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $members = $entityManager->getRepository(Member::class)->findAll();
        dump($members);

        return $this->render('member/index.html.twig', [
            'members' => $members,
        ]);
    }
    /**
     * Show a member
     *
     * @param String $username
     */
    #[Route('/member/{username}', name: 'member_show')]
    public function show(ManagerRegistry $doctrine, string $username) : Response
    {

        // Chargement d'un membre par son nom d'utilisateur (unique)
        $MemberRepo = $doctrine->getRepository(Member::class);
        $member = $MemberRepo->findOneBy(['username' => $username]);

        // Si le nom d'utilisateur est erroné, on s'arrête brutalement : 404
        if (!$member) {
            throw $this->createNotFoundException('The member you are searching for does not exist');
        }

        return $this->render('member/show.html.twig', [
            'member' => $member,
            'bacs' => $member->getBacs(),
        ]);
    }
}
