<?php

namespace App\Controller;

use App\Entity\OrderWorkflow;
use App\Form\OrderWorkflowType;
use App\Repository\OrderWorkflowRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/order/workflow')]
final class OrderWorkflowController extends AbstractController
{
    #[Route(name: 'app_order_workflow_index', methods: ['GET'])]
    public function index(OrderWorkflowRepository $orderWorkflowRepository): Response
    {
        return $this->render('order_workflow/index.html.twig', [
            'order_workflows' => $orderWorkflowRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_order_workflow_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $orderWorkflow = new OrderWorkflow();
        $form = $this->createForm(OrderWorkflowType::class, $orderWorkflow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($orderWorkflow);
            $entityManager->flush();

            return $this->redirectToRoute('app_order_workflow_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('order_workflow/new.html.twig', [
            'order_workflow' => $orderWorkflow,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_order_workflow_show', methods: ['GET'])]
    public function show(OrderWorkflow $orderWorkflow): Response
    {
        return $this->render('order_workflow/show.html.twig', [
            'order_workflow' => $orderWorkflow,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_order_workflow_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OrderWorkflow $orderWorkflow, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrderWorkflowType::class, $orderWorkflow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_order_workflow_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('order_workflow/edit.html.twig', [
            'order_workflow' => $orderWorkflow,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_order_workflow_delete', methods: ['POST'])]
    public function delete(Request $request, OrderWorkflow $orderWorkflow, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$orderWorkflow->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($orderWorkflow);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_order_workflow_index', [], Response::HTTP_SEE_OTHER);
    }
}
