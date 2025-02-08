# Symfony Workflow

## Getting Started

`make up`

## Webmail

http://localhost:8025/

## Workflow Home made

### Entities 

- [Order.php](src/Entity/Order.php)
- [OrderWorkflow.php](src/Entity/OrderWorkflow.php)
- [OrderWorkflowAction.php](src/Entity/OrderWorkflowAction.php)
- [OrderWorkflowPlace.php](src/Entity/OrderWorkflowPlace.php)
- [OrderWorkflowPlaceData.php](src/Entity/OrderWorkflowPlaceData.php)
- [OrderWorkflowPlaceDataMapping.php](src/Entity/OrderWorkflowPlaceDataMapping.php)
- [OrderWorkflowTransition.php](src/Entity/OrderWorkflowTransition.php)

### OrderTransition

- [Action](src/WorkflowOrder/Action)
  - [ActionFactory.php](src/WorkflowOrder/Action/ActionFactory.php)
  - [ActionInterface.php](src/WorkflowOrder/Action/ActionInterface.php)
  - [ChangeCategoryAction.php](src/WorkflowOrder/Action/ChangeCategoryAction.php)
  - [ChangeOwnerAction.php](src/WorkflowOrder/Action/ChangeOwnerAction.php)
  - [SendNotificationAction.php](src/WorkflowOrder/Action/SendNotificationAction.php)
- [State](src/WorkflowOrder/State)
  - [DeliveredState.php](src/WorkflowOrder/State/DeliveredState.php)
  - [OrderState.php](src/WorkflowOrder/State/OrderState.php)
  - [PendingState.php](src/WorkflowOrder/State/PendingState.php)
  - [ProcessingState.php](src/WorkflowOrder/State/ProcessingState.php)
  - [ShippedState.php](src/WorkflowOrder/State/ShippedState.php)
  - [State.php](src/WorkflowOrder/State/State.php)
- [MermaidGenerator.php](src/WorkflowOrder/MermaidGenerator.php)
- [OrderTransition.php](src/WorkflowOrder/OrderTransition.php)
- [OrderWorkflowService.php](src/WorkflowOrder/OrderWorkflowService.php)
- [WorkflowInterface.php](src/WorkflowOrder/WorkflowInterface.php)

### Controller

```php
$context = [
    'user' => $currentUser,
    'order_total' => $order->getAmount(),
];

if ($workflow->canTransition($entityWorkflow, $order->getCurrentState(), $targetPlace, $context)) {
    $workflow->applyTransition($entityWorkflow, $order, $targetPlace);
    $em->flush();
} else {
    return new JsonResponse([
        'error' => sprintf('Transition non autorisée de %s ➝ %s', $order->getState(), $targetPlace->getName()),
    ], 400);
}
```

## Workflow deprecated

- [workflow-1.md](docs/workflow-1.md)
- [workflow-2.md](docs/workflow-2.md)
