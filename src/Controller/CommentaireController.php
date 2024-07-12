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

    // Ajoutez un constructeur pour injecter EntityManagerInterface
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/posts/{postId}/comments/create', name: 'app_comment_create')]
    public function create(Post $post, Request $request): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setAuteur($this->getUser());
            $commentaire->setPost($post);
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
    public function edit(Commentaire $commentaire, Request $request): Response
    {
        $this->denyAccessUnlessGranted('edit', $commentaire);

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
    public function delete(Commentaire $commentaire): Response
    {
        $this->denyAccessUnlessGranted('delete', $commentaire);

        $postId = $commentaire->getPost()->getId();
        $this->entityManager->remove($commentaire);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_post_view', ['id' => $postId]);
    }
}
