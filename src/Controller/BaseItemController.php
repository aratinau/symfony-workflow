<?php

namespace App\Controller;

use App\Entity\BaseItem;
use App\Form\BaseItemType;
use App\Repository\BaseItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/base_items')]
#[IsGranted('ROLE_USER')]
class BaseItemController extends AbstractController
{
    #[Route('/', name: 'app_base_item_index', methods: ['GET'])]
    public function index(BaseItemRepository $baseItemRepository): Response
    {
        return $this->render('base_item/index.html.twig', [
            'base_items' => $baseItemRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_base_item_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $baseItem = new BaseItem();
        $form = $this->createForm(BaseItemType::class, $baseItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($baseItem);
            $entityManager->flush();

            return $this->redirectToRoute('app_base_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('base_item/new.html.twig', [
            'base_item' => $baseItem,
            'form' => $form,
        ]);
    }

//    #[Route('/{id}', name: 'app_base_item_show', methods: ['GET'])]
//    public function show(BaseItem $baseItem): Response
//    {
//        return $this->render('base_item/show.html.twig', [
//            'base_item' => $baseItem,
//        ]);
//    }

    #[Route('/{id}/edit', name: 'app_base_item_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BaseItem $baseItem, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BaseItemType::class, $baseItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_base_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('base_item/edit.html.twig', [
            'base_item' => $baseItem,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_base_item_delete', methods: ['POST'])]
    public function delete(Request $request, BaseItem $baseItem, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$baseItem->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($baseItem);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_base_item_index', [], Response::HTTP_SEE_OTHER);
    }
}
