<?php

namespace App\Controller;

use App\Entity\Member;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MemberController extends AbstractController
{
    /**
     * Show a [Member]'s profile by their email (used as username)
     */
    #[Route('/member/{username}', name: 'member_show')]
    public function show(ManagerRegistry $doctrine, string $username): Response
    {
        $html_page = " <!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <title>Profil du membre</title>
    </head>
    <body>
    <h2>Profil de " . htmlspecialchars($username) . "</h2>";

        $memberRepo = $doctrine->getManager()->getRepository(Member::class);
        $member = $memberRepo->findOneBy(['username' => $username]);

        if (!$member) {
            throw $this->createNotFoundException('The Member does not exist');
        }

        $html_page .= $member;

        $library = $member->getLibrary();
        if ($library) {
            $html_page .= '<p/><a href="' . $this->generateUrl('library_show', ['id' => $library->getId()]) . '">Voir sa librairie</a>';
        }

        $html_page .= '<p/><a href="' . $this->generateUrl('app_library') . '">Back</a>';
        $html_page .= '</body></html>';

        return new Response(
            $html_page,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
    }
}
