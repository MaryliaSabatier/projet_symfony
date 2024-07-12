<?php
namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Post;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentaireController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/posts/{id}/comments/create', name: 'app_comment_create')]
    public function createComment(Post $post, Request $request): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setPost($post);
            $commentaire->setAuteur($this->getUser());
            $commentaire->setDateCreation(new \DateTime());

            $this->entityManager->persist($commentaire);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_post_view', ['id' => $post->getId()]);
        }

        return $this->render('comment/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/comments/{id}/edit', name: 'app_comment_edit')]
    public function editComment(Commentaire $commentaire, Request $request): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_post_view', ['id' => $commentaire->getPost()->getId()]);
        }

        return $this->render('comment/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/comments/{id}/delete', name: 'app_comment_delete')]
    public function deleteComment(Commentaire $commentaire): Response
    {
        $this->entityManager->remove($commentaire);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_post_view', ['id' => $commentaire->getPost()->getId()]);
    }
}
