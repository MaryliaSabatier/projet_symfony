<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Commentaire;
use App\Form\EventType;
use App\Form\PostType;
use App\Form\CommentaireType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/index.html.twig');
    }

    #[Route('/admin/users', name: 'admin_user_list')]
    public function userList(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $users = $this->entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/user_list.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/user/create', name: 'admin_user_create')]
    public function createUser(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/user/{id}/edit', name: 'admin_user_edit')]
    public function editUser(User $user, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('plainPassword')->getData()) {
                $user->setPassword(
                    $this->passwordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }

            $this->entityManager->flush();

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/user/{id}/delete', name: 'admin_user_delete')]
    public function deleteUser(User $user): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_user_list');
    }

    #[Route('/admin/events', name: 'admin_event_list')]
    public function eventList(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $events = $this->entityManager->getRepository(Evenement::class)->findAll();

        return $this->render('admin/event_list.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/admin/event/create', name: 'admin_event_create')]
    public function createEvent(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $event = new Evenement();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setAuteur($this->getUser()); // Set the author of the event
            $this->entityManager->persist($event);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_event_list');
        }

        return $this->render('admin/event_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/event/{id}/edit', name: 'admin_event_edit')]
    public function editEvent(Evenement $event, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_event_list');
        }

        return $this->render('admin/event_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/event/{id}/delete', name: 'admin_event_delete')]
    public function deleteEvent(Evenement $event): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $this->entityManager->remove($event);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_event_list');
    }

    #[Route('/admin/discussions', name: 'admin_discussion_list')]
    public function discussionList(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
    
        $posts = $this->entityManager->getRepository(Post::class)->findAll();
    
        return $this->render('admin/discussion_list.html.twig', [
            'posts' => $posts,
        ]);
    }
    

    #[Route('/admin/post/create', name: 'admin_post_create')]
    public function createPost(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
    
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setAuteur($this->getUser());
            $post->setDateCreation(new \DateTime());
    
            $this->entityManager->persist($post);
            $this->entityManager->flush();
    
            return $this->redirectToRoute('admin_discussion_list');
        }
    
        return $this->render('admin/post_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/admin/post/{id}/edit', name: 'admin_post_edit')]
    public function editPost(Post $post, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
    
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
    
            return $this->redirectToRoute('admin_discussion_list');
        }
    
        return $this->render('admin/post_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/admin/post/{id}/delete', name: 'admin_post_delete')]
    public function deletePost(Post $post): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $this->entityManager->remove($post);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_discussion_list');
    }

    #[Route('/admin/comment/create/{postId}', name: 'admin_comment_create')]
    public function createComment(Request $request, Post $post): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
    
        $comment = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $comment);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setPost($post);
            $comment->setAuteur($this->getUser());
            $comment->setDateCreation(new \DateTime());
    
            $this->entityManager->persist($comment);
            $this->entityManager->flush();
    
            return $this->redirectToRoute('admin_discussion_list');
        }
    
        return $this->render('admin/comment_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/admin/comment/{id}/edit', name: 'admin_comment_edit')]
    public function editComment(Commentaire $comment, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(CommentaireType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_discussion_list');
        }

        return $this->render('admin/comment_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/comment/{id}/delete', name: 'admin_comment_delete')]
    public function deleteComment(Commentaire $comment): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $this->entityManager->remove($comment);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_discussion_list');
    }
}
