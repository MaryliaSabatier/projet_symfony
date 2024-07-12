<?php
namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/posts/{id}', name: 'app_post_view')]
    public function view(Post $post): Response
    {
        return $this->render('post/view.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/posts', name: 'app_post_list')]
    public function list(): Response
    {
        $posts = $this->entityManager->getRepository(Post::class)->findAll();

        return $this->render('post/list.html.twig', [
            'posts' => $posts,
        ]);
    }
}
