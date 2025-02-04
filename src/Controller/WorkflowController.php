<?php

namespace App\Controller;

use App\Entity\BaseItem;
use App\Form\WorkflowStatusType;
use App\Repository\WorkflowPlaceRepository;
use App\Repository\WorkflowTransitionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WorkflowController extends AbstractController
{
    public function __construct(
        private WorkflowPlaceRepository      $workflowStateRepository,
        private WorkflowTransitionRepository $workflowTransitionRepository,
        private EntityManagerInterface       $entityManager
    ) {
    }

    #[Route('/workflow', name: 'app_workflow')]
    public function index(): Response
    {
        // Récupérer l'état "draft"
        $draftState = $this->workflowStateRepository->findBy(['name' => 'draft']);
        if (!$draftState) {
            throw $this->createNotFoundException('État "draft" introuvable.');
        }

        // Récupérer les transitions possibles pour l'état "draft"
        $transitions = $this->workflowTransitionRepository->findTransitionsForState($draftState);

        foreach ($transitions as $transition) {
            echo "Transition possible: " . $transition->getFromState()->getName() . " → " . $transition->getToState()->getName() . "\n";
        }

        return new Response('Workflow dynamique chargé.');
    }

    #[Route('/update-workflow/{baseItem}', name: 'update_status_workflow')]
    public function updateWorkflow(BaseItem $baseItem, Request $request): Response
    {
        $currentStatus = $baseItem->getPublicStatus();

        $transitions = $this->workflowTransitionRepository->findTransitionsForState($currentStatus);

        $choices = [];
        foreach ($transitions as $transition) {
            $choices[$transition->getToState()->getName()] = $transition->getToState()->getName();
        }

        $form = $this->createForm(WorkflowStatusType::class, null, [
            'transitions' => $choices,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newStatus = $form->get('newStatus')->getData();
            $baseItem->setPublicStatus($newStatus);

            $this->entityManager->persist($baseItem);
            $this->entityManager->flush();

            $this->addFlash('success', 'Statut mis à jour avec succès.');

            return $this->redirectToRoute('update_status_workflow', ['baseItem' => $baseItem->getId()]);
        }

        return $this->render('workflow/update.html.twig', [
            'transitions' => $transitions,
            'form' => $form->createView(),
        ]);
    }
}
