<?php
namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EvenementController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/evenement/create', name: 'app_evenement_create')]
    public function create(Request $request): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evenement->setAuteur($this->getUser());
            $evenement->setDateCreation(new \DateTime());

            $this->entityManager->persist($evenement);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_discussion');
        }

        return $this->render('evenement/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/evenement/{id}/edit', name: 'app_evenement_edit')]
    public function edit(Evenement $evenement, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', $evenement);

        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_discussion');
        }

        return $this->render('evenement/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/evenement/{id}/delete', name: 'app_evenement_delete')]
    public function delete(Evenement $evenement): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', $evenement);

        $this->entityManager->remove($evenement);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_discussion');
    }
}
