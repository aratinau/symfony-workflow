<?php

namespace App\WorkflowOrder\Action;

use App\Entity\OrderWorkflow as EntityOrderWorkflow;
use App\Entity\OrderWorkflowPlace;
use App\Entity\User;
use App\Entity\WorkflowPlace;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class ChangeOwnerAction implements ActionInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function execute($entity, OrderWorkflowPlace $workflowPlace)
    {
//        if (!isset($data['category_name'])) {
//            throw new \InvalidArgumentException("La clé 'category_name' est requise pour l'action 'change_category'.");
//        }


//        $this->entityManager
//            ->getRepository(User::class)
//            ->findAll();


        // Attention : utilise get data mappings
        foreach ($workflowPlace->getDataMappings() as $dataMapping) {
//            $category = $this->entityManager
//                ->getRepository(User::class)->findOneBy([
//                $dataMapping->getData()->getKey() => $dataMapping->getData()->getValue()
//            ]);

//            if (!$category) {
                // throw new \Exception("Catégorie introuvable : {$data['category_name']}");
//            }

            //$entity->addOwner($category);
        }


        foreach ($workflowPlace->getOrderWorkflowActions() as $orderWorkflowAction) {
            $user = $this->entityManager
                ->getRepository(User::class)->findOneBy([
                    $orderWorkflowAction->getData()->getKey() => $orderWorkflowAction->getData()->getValue()
                ]);

            if (!$user) {
                // throw new \Exception("Catégorie introuvable : {$data['category_name']}");
            }

            $entity->addOwner($user);
        }

        //$category = $this->categoryRepository->findOneBy(['name' => $data['category_name']]);


        // Log (facultatif)
        // $this->logger->info("Catégorie de la commande mise à jour vers {$category->getName()}");
    }
}
