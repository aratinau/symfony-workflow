<?php

namespace App\WorkflowOrder\Action;

use App\Entity\OrderWorkflowPlace;
use App\Entity\WorkflowPlace;
use App\Repository\CategoryRepository;

class ChangeCategoryAction implements ActionInterface
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function execute($order, OrderWorkflowPlace $workflowPlace)
    {
//        if (!isset($data['category_name'])) {
//            throw new \InvalidArgumentException("La clé 'category_name' est requise pour l'action 'change_category'.");
//        }

        // TODO envoyer la data
        $c = $workflowPlace->getDatas()->toArray()[0]->getValue();

        //$category = $this->categoryRepository->findOneBy(['name' => $data['category_name']]);
        $category = $this->categoryRepository->findOneBy(['name'=> $c]);

        if (!$category) {
            // throw new \Exception("Catégorie introuvable : {$data['category_name']}");
        }

        $order->addCategory($category);

        // Log (facultatif)
        // $this->logger->info("Catégorie de la commande mise à jour vers {$category->getName()}");
    }
}
