<?php 
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
