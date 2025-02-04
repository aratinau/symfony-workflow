<?php

namespace App\Controller;

use App\Form\ChangePublicStatusType;
use App\Repository\BaseItemRepository;
use App\Workflow\DocumentExistsValidator;
use App\Workflow\EmailNotificationObserver;
use App\Workflow\StateContext;
use App\Workflow\UserHasPermissionValidator;
use App\Workflow\WorkflowFactory;
use App\Workflow\WorkflowObserver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Workflow\WorkflowInterface;

class BaseItemWorkflowController extends AbstractController
{
    #[Route('/new-workflow', name: 'base_item_new_workflow')]
    public function newWorkflow()
    {
        $workflow = new StateContext(WorkflowFactory::createState('draft'));
        $observer = new EmailNotificationObserver();
        WorkflowObserver::getInstance()->attach($observer);

        // Validation de la transition
        $documentRequest = ['documentExists' => true, 'userHasPermission' => true];

        $validator1 = new DocumentExistsValidator();
        $validator2 = new UserHasPermissionValidator();
        $validator1->setNext($validator2);

        // Exécution de la transition si validée
        if ($validator1->validate($documentRequest)) {
            $workflow->proceed(WorkflowFactory::createStrategy('default'));  // Passe à Submitted
        }

        if ($validator1->validate($documentRequest)) {
            $workflow->proceed(WorkflowFactory::createStrategy('approval')); // Passe à Approved
        }

        dd('ok');
    }

    #[Route('/base-item/{id}/change-status', name: 'base_item_change_status')]
    public function changeStatus(
        int $id,
        BaseItemRepository $baseItemRepository,
        WorkflowInterface $publicStatusWorkflow, // Corrigez ici
        // WorkflowInterface $dynamicWorkflowBaseItem,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $baseItem = $baseItemRepository->find($id);

        if (!$baseItem) {
            throw $this->createNotFoundException('BaseItem not found.');
        }

        $form = $this->createForm(ChangePublicStatusType::class, $baseItem);
        $form->handleRequest($request);

        $enabledTransitions = $publicStatusWorkflow->getEnabledTransitions($baseItem);


        if ($form->isSubmitted() && $form->isValid()) {
            $transition = $request->request->get('transition');

            // Appliquer la transition choisie
            if ($publicStatusWorkflow->can($baseItem, $transition)) {
                $publicStatusWorkflow->apply($baseItem, $transition);

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
