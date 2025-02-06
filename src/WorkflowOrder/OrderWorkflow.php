<?php

namespace App\WorkflowOrder;

use App\Entity\Order;
use App\Entity\OrderWorkflowPlace;
use App\Repository\CategoryRepository;
use App\Repository\OrderWorkflowPlaceRepository;
use App\WorkflowOrder\Action\ActionFactory;
use App\WorkflowOrder\State\DeliveredState;
use App\WorkflowOrder\State\PendingState;
use App\WorkflowOrder\State\ProcessingState;
use App\WorkflowOrder\State\ShippedState;
use App\WorkflowOrder\State\State;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;



// TODO
// TODO - pouvoir configurer les boutons en front (mettre une couleur)
// TODO - qui alerter
// TODO - qui peut modifier
// TODO - Quelle action sur l'objet
// TODO - Quelle action sur l'objet en fonction d'autre objet (changer la catégorie ?)

// TODO : si on met a signer : il faut que ca attribue a quelqu'un
// TODO : pouvoir créé plusieurs workflow et choisir lequel attribuer en fonction de ?

// TODO : configurer des interface par rapport aux actions ?
// exemple avec les notifs : qui alerter

// TODO : des direction (nouvel etat) en fonction du resultat d'une expression

class OrderWorkflow
{

    public function __construct(
        private OrderTransition $orderTransition,
        private OrderWorkflowPlaceRepository $OrderWorkflowPlaceRepository,
        private LoggerInterface $logger,
        private EntityManagerInterface $entityManager,
        private CategoryRepository $categoryRepository,
        private ActionFactory $actionFactory,
    ) {
//        foreach ($this->orderTransition->getAllTransitions() as $name => $transition) {
//            $this->states[$name] = new State($name, $transition);
//        }
    }

    public function process(\App\Entity\OrderWorkflow $entityWorkflow, Order $order, string $nextState): void
    {
        $currentState = $order->getState();

        foreach ($this->orderTransition->getAllTransitions($entityWorkflow->getId()) as $name => $transition) {
            $states[$name] = new State($name, $transition);
        }

        // Vérifier si l'état actuel existe
        if (!isset($states[$currentState])) {
            throw new \Exception("État actuel inconnu : $currentState");
        }

        // Vérifier si la transition est valide
        if (!in_array($nextState, $states[$currentState]->getTransitions())) {
            throw new \Exception("Transition non autorisée : $currentState -> $nextState");
        }

        // Récupérer l'état cible
        $nextStatePlace = $this->OrderWorkflowPlaceRepository->findOneBy(['name' => $nextState]);
        if (!$nextStatePlace) {
            throw new \Exception("État cible inconnu : $nextState");
        }

        // Exécuter les actions associées à l'état cible
        $actions = $nextStatePlace->getActions();

        foreach ($actions as $action) {
            try {
                $actionInstance = $this->actionFactory->create($action);
                $actionInstance->execute($order, $nextStatePlace);
            } catch (\Exception $e) {
                $this->logger->warning($e->getMessage());
            }
        }

        /*
        foreach ($actions as $action) {
            switch ($action) {
                case 'change_category':
                    // $order->setCategory($action['category']);
                    $order->addCategory($this->categoryRepository->findOneBy([], [])); // tmp pour tester
                    //$this->logger->info("Catégorie de la commande mise à jour vers {$action['category']}");
                    break;
                case 'send_notification':
                    $this->sendNotification($action['recipient'], $action['message']);
                    $this->logger->info("Notification envoyée à {$action['recipient']} avec le message '{$action['message']}'");
                    break;
                // Ajoutez d'autres types d'actions ici
                default:
                    $this->logger->warning("Type d'action inconnu : {$action['type']}");
                    break;
            }
        }*/

        // Mettre à jour l'état de la commande
        $states[$nextStatePlace->getName()]->process($order);
        $order->setState($nextState);

        $this->logger->info("Commande mise à jour avec succès : nouvel état '$nextState'");
    }

    public function getAvailableTransitions($workflow, string $currentState): array
    {
        // TODO aller chercher depuis la base de donnée
        // Utilise OrderTransition pour obtenir les transitions dynamiques
        $allTransitions = $this->orderTransition->getAllTransitions($workflow);

        return $allTransitions[$currentState] ?? [];
    }

    public function canTransition(
        \App\Entity\OrderWorkflow $workflow,
        string $currentState,
        string $newState,
        array $context = []
    ): bool
    {
        // Récupérer toutes les transitions possibles
        $transitions = $this->orderTransition->getAllTransitions($workflow->getId());

        // Vérifier si la transition est définie
        if (!isset($transitions[$currentState]) || !in_array($newState, $transitions[$currentState], true)) {
            return false;
        }

        // Récupérer l'état cible
        $targetPlace = $this->entityManager
            ->getRepository(OrderWorkflowPlace::class)
            ->findOneBy(['name' => $newState, 'workflow' => $workflow]);

        if (!$targetPlace) {
            throw new \Exception("État inconnu : $newState");
        }

        // Vérifier les rôles autorisés
        $allowedRoles = $targetPlace->getAllowedRoles();
        if ($allowedRoles && isset($context['user']) && !in_array($context['user']->getRole(), $allowedRoles, true)) {
            return false;
        }

        // Initialiser l'évaluateur d'expressions
        $expressionLanguage = new ExpressionLanguage();

        // Évaluer les conditions associées à l'état cible
        $conditions = $targetPlace->getConditions();
        if (!empty($conditions)) {
            try {
                foreach ($conditions as $condition) {
                    if (!$expressionLanguage->evaluate($condition, $context)) {
                        return false; // Si une condition n'est pas satisfaite, refuser la transition
                    }
                }
            } catch (\Exception $e) {
                throw new \Exception("Erreur lors de l'évaluation des conditions : " . $e->getMessage());
            }
        }

        return true; // Si toutes les vérifications sont passées, autoriser la transition
    }
}
