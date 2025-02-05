<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\WorkflowOrder\OrderWorkflow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/order')]
final class OrderController extends AbstractController
{
    #[Route('/order/process/{id}', name: 'order_process')]
    public function process(Order $order, EntityManagerInterface $em, OrderWorkflow $workflow, Request $request): JsonResponse
    {
        $nextState = $request->query->get('param', '');

        try {
            $workflow->process($order, $nextState);
            $em->flush();

            return new JsonResponse([
                'id' => $order->getId(),
                'new_state' => $order->getState(),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/order/transitions/{id}', name: 'order_transitions')]
    public function getTransitions(Order $order, OrderWorkflow $workflow): JsonResponse
    {
        return new JsonResponse([
            'id' => $order->getId(),
            'current_state' => $order->getState(),
            'available_transitions' => $workflow->getAvailableTransitions($order->getState()),
        ]);
    }

    #[Route(name: 'app_order_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, OrderWorkflow $workflow): Response
    {
        $orders = $entityManager
            ->getRepository(Order::class)
            ->findBy([], ['updatedAt' => 'DESC']);

        return $this->render('order/index.html.twig', [
            'orders' => $orders,
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
