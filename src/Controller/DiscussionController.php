<?php

namespace App\Controller;

use App\Entity\Discussion;
use App\Form\DiscussionType;
use App\Repository\DiscussionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiscussionController extends AbstractController
{
    #[Route('/admin/discussions', name: 'admin_discussion_list')]
    public function list(DiscussionRepository $discussionRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $discussions = $discussionRepository->findAll();

        return $this->render('admin/discussion_list.html.twig', [
            'discussions' => $discussions,
        ]);
    }

    #[Route('/admin/discussions/create', name: 'admin_create_discussion')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $discussion = new Discussion();
        $form = $this->createForm(DiscussionType::class, $discussion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $discussion->setAuteur($this->getUser());
            $entityManager->persist($discussion);
            $entityManager->flush();

            $this->addFlash('success', 'Discussion créée avec succès.');
            return $this->redirectToRoute('admin_discussion_list');
        }

        return $this->render('admin/create_discussion.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/discussions/edit/{id}', name: 'admin_edit_discussion')]
    public function edit(Discussion $discussion, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(DiscussionType::class, $discussion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Discussion modifiée avec succès.');

            return $this->redirectToRoute('admin_discussion_list');
        }

        return $this->render('admin/edit_discussion.html.twig', [
            'form' => $form->createView(),
            'discussion' => $discussion,
        ]);
    }

    #[Route('/admin/discussions/delete/{id}', name: 'admin_delete_discussion', methods: ['POST'])]
    public function delete(Request $request, Discussion $discussion, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->isCsrfTokenValid('delete' . $discussion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($discussion);
            $entityManager->flush();

            $this->addFlash('success', 'Discussion supprimée avec succès.');
        }

        return $this->redirectToRoute('admin_discussion_list');
    }

    // Accessible pour tous les users
    #[Route('/discussions', name: 'discussion_list')]
    public function listForAll(DiscussionRepository $discussionRepository): Response
    {
        // Récupérer toutes les discussions
        $discussions = $discussionRepository->findAll();

        // Rendre la vue Twig pour afficher toutes les discussions
        return $this->render('discussion/index.html.twig', [
            'discussions' => $discussions,
        ]);
    }

    #[Route('/discussions/{id}', name: 'discussion_show')]
    public function show(Discussion $discussion): Response
    {
        // Rendre la vue Twig pour afficher les détails d'une discussion
        return $this->render('discussion/show.html.twig', [
            'discussion' => $discussion,
        ]);
    }

    #[Route('/discussions', name: 'discussion_page')]
    public function listDiscussions(DiscussionRepository $discussionRepository): Response
    {
        $discussions = $discussionRepository->findAll();

        return $this->render('discussion/discussion_page.html.twig', [
            'discussions' => $discussions,
        ]);
    }
}
