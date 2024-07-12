<?php
namespace App\Controller;

use App\Entity\Post;
use App\Entity\Commentaire;
use App\Entity\Evenement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiscussionController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/discussion', name: 'app_discussion')]
    public function index(): Response
    {
        $posts = $this->entityManager->getRepository(Post::class)->findAll();
        $evenements = $this->entityManager->getRepository(Evenement::class)->findAll();

        return $this->render('discussion/index.html.twig', [
            'posts' => $posts,
            'evenements' => $evenements,
        ]);
    }
}
