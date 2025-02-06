<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\WorkflowOrder\OrderWorkflow;
use App\Entity\OrderWorkflow as EntityOrderWorkflow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/order/controller')]
#[IsGranted('ROLE_USER')]
final class OrderController extends AbstractController
{
    public function __construct(
        private Security $security,
    ) {
    }

    #[Route('/{entityWorkflow}/order/process/{id}', name: 'change_state_workflow')]
    public function process(
        EntityOrderWorkflow $entityWorkflow,
        Order $order,
        EntityManagerInterface $em,
        OrderWorkflow $workflow,
        Request $request): JsonResponse|Response
    {
        $nextState = $request->query->get('param', '');
        $currentUser = $this->security->getUser();

        if ($nextState === '') {
            return new JsonResponse([
                'error' => 'nextState is empty',
            ], 400);
        }

        try {
            $context = [
                'user' => $currentUser,
                'order_total' => $order->getAmount(),
            ];

            if ($workflow->canTransition($entityWorkflow, $order->getState(), $nextState, $context)) {
                $workflow->process($entityWorkflow, $order, $nextState);
                $em->flush();
            } else {
                return new JsonResponse([
                    'error' => sprintf('Transition non autorisée de %s ➝ %s', $order->getState(), $nextState),
                ], 400);
            }

            $this->addFlash('success', 'Etat mis à jour avec succès.');

            return $this->redirectToRoute('app_order_index', [
                'entityWorkflow' => $entityWorkflow->getId()
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/order/transitions/{id}/workflow/{workflow}', name: 'order_transitions')]
    public function getTransitions(Order $order, EntityOrderWorkflow $entityWorkflow, OrderWorkflow $workflow): JsonResponse
    {
        return new JsonResponse([
            'id' => $order->getId(),
            'current_state' => $order->getState(),
            'available_transitions' => $workflow->getAvailableTransitions($entityWorkflow, $order->getState()),
        ]);
    }

    #[Route('/{entityWorkflow}', name: 'app_order_index', methods: ['GET'])]
    public function index(EntityOrderWorkflow $entityWorkflow, EntityManagerInterface $entityManager): Response
    {
        $orders = $entityManager
            ->getRepository(Order::class)
            ->findAll();
        $workflows = $entityManager
            ->getRepository(EntityOrderWorkflow::class)
            ->findAll();

        return $this->render('order/index.html.twig', [
            'orders' => $orders,
            'workflows' => $workflows,
            'workflow' => $entityWorkflow
        ]);
    }

    #[Route('/new', name: 'app_order_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('order/new.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_order_show', methods: ['GET'])]
    public function show(Order $order): Response
    {
        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_order_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('order/edit.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_order_delete', methods: ['POST'])]
    public function delete(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
    }
}
