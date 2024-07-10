<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}

class ModeratorController extends AbstractController
{
    #[Route('/moderateur', name: 'moderator_dashboard')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MODERATEUR');

        return $this->render('moderator/index.html.twig', [
            'controller_name' => 'ModeratorController',
        ]);
    }
}

class UserController extends AbstractController
{
    #[Route('/utilisateur', name: 'user_dashboard')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
