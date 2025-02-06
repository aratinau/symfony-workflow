<?php

namespace App\Controller;

use App\Entity\BaseItem;
use App\WorkflowDeprecated\Observer\EmailNotificationObserver;
use App\WorkflowDeprecated\Observer\WorkflowObserver;
use App\WorkflowDeprecated\Validator\DocumentExistsValidator;
use App\WorkflowDeprecated\Validator\UserHasPermissionValidator;
use App\WorkflowDeprecated\WorkflowFactory;
use App\WorkflowDeprecated\WorkflowService;
use App\WorkflowOrder\MermaidGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class BaseItemWorkflowController extends AbstractController
{
    public function __construct(
        private MermaidGenerator $mermaidGenerator,
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/workflow-en-dur', name: 'base_item_new_workflow')]
    public function workflowEnDur(WorkflowService $workflowService)
    {
        $workflow = $workflowService->getWorkflow();
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

    #[Route('/workflow-en-dur2/{baseItem}', name: 'base_item_new_workflow2')]
    public function workflowEnDur2(BaseItem $baseItem, WorkflowService $workflowService, Request $request)
    {
        $type = $request->query->get('param', 'default');

        $workflow = $workflowService->getWorkflow();
        $workflow->proceed(WorkflowFactory::createStrategy($type));  // Passe à Submitted

        $currentStateName = $workflow->getCurrentState();

        $states = [
            'draft' => 'Draft State',
            'submitted' => 'Submitted State',
            'approved' => 'Approved State',
            'rejected' => 'Rejected State'
        ];

        $transitions = [
            ['draft', 'submitted', 'submit'],
            ['submitted', 'approved', 'approve'],
            ['submitted', 'rejected', 'reject']
        ];

        $baseItem->setPublicStatus($type);

        /* TODO
        if ($orderProcessingStateMachine->can($order, 'submit')) {
            $orderProcessingStateMachine->apply($order, 'submit');
            $this->getDoctrine()->getManager()->flush();
        }*/

        $this->entityManager->persist($baseItem);
        $this->entityManager->flush();

        $mermaidCode = $this->mermaidGenerator->generate($states, $transitions);

        return $this->render('workflow/transitions.html.twig', [
//            'currentState' => $currentStateName::class,
            'baseItem' => $baseItem,
            'transitions' => [
                'default',
                'approval',
            ],
            'mermaidCode' => $mermaidCode
        ]);
    }

    /*
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
    }*/
}
