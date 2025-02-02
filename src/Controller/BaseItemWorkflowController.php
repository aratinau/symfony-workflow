<?php

namespace App\Controller;

use App\Form\ChangePublicStatusType;
use App\Repository\BaseItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Workflow\WorkflowInterface;

class BaseItemWorkflowController extends AbstractController
{
    #[Route('/base-item/{id}/change-status', name: 'base_item_change_status')]
    public function changeStatus(
        int $id,
        BaseItemRepository $baseItemRepository,
        //WorkflowInterface $publicStatusWorkflow, // Corrigez ici
        WorkflowInterface $dynamicWorkflowBaseItem,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $baseItem = $baseItemRepository->find($id);

        if (!$baseItem) {
            throw $this->createNotFoundException('BaseItem not found.');
        }

        $form = $this->createForm(ChangePublicStatusType::class, $baseItem);
        $form->handleRequest($request);

        $enabledTransitions = $dynamicWorkflowBaseItem->getEnabledTransitions($baseItem);


        if ($form->isSubmitted() && $form->isValid()) {
            $transition = $request->request->get('transition');

            // Appliquer la transition choisie
            if ($dynamicWorkflowBaseItem->can($baseItem, $transition)) {
                $dynamicWorkflowBaseItem->apply($baseItem, $transition);

                $entityManager->flush();

                $this->addFlash('success', sprintf('Transition "%s" applied successfully!', $transition));

                return $this->redirectToRoute('base_item_change_status', ['id' => $id]);
            } else {
                $this->addFlash('error', sprintf('Transition "%s" cannot be applied.', $transition));
            }
        }


        return $this->render('base_item_workflow/index.html.twig', [
            'baseItem' => $baseItem,
            'form' => $form->createView(),
            'enabledTransitions' => $enabledTransitions,
        ]);
    }
}
