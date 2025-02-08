Voici un exemple complet d'un **système de workflow configurable** en PHP qui utilise les patterns suivants :

- **State Pattern** : Gère les différents états du workflow.
- **Strategy Pattern** : Gère les différentes transitions du workflow.
- **Observer Pattern** : Notifie des événements liés au workflow.
- **Chain of Responsibility** : Applique une série de validations avant de permettre la transition.
- **Factory Pattern** : Crée dynamiquement les états et transitions du workflow.

---

### 🎯 **Contexte**

Nous allons simuler un **workflow d'approbation de document** avec les états suivants :

1. **Brouillon** (`DraftState`)
2. **Soumis** (`SubmittedState`)
3. **Approuvé** (`ApprovedState`)
4. **Rejeté** (`RejectedState`)

Chaque transition est soumise à des validations avant d'être exécutée, et une notification est envoyée en cas de changement d'état.

---

## 🏗 **Implémentation complète**

### 1️⃣ **Définition des États (State Pattern)**

Chaque état implémente une interface commune.

```php
<?php

interface WorkflowState {
    public function proceedToNext(StateContext $context, TransitionStrategy $strategy);
}

class DraftState implements WorkflowState {
    public function proceedToNext(StateContext $context, TransitionStrategy $strategy) {
        echo "Transition de Brouillon à Soumis\n";
        $strategy->executeTransition($context, new SubmittedState());
    }
}

class SubmittedState implements WorkflowState {
    public function proceedToNext(StateContext $context, TransitionStrategy $strategy) {
        echo "Transition de Soumis à Approuvé ou Rejeté\n";
        $strategy->executeTransition($context, new ApprovedState());
    }
}

class ApprovedState implements WorkflowState {
    public function proceedToNext(StateContext $context, TransitionStrategy $strategy) {
        echo "Le document est déjà approuvé.\n";
    }
}

class RejectedState implements WorkflowState {
    public function proceedToNext(StateContext $context, TransitionStrategy $strategy) {
        echo "Le document est déjà rejeté.\n";
    }
}
```

---

### 2️⃣ **Contexte du Workflow**

Gère l'état actuel.

```php
class StateContext {
    private $currentState;

    public function __construct(WorkflowState $state) {
        $this->currentState = $state;
    }

    public function setState(WorkflowState $state) {
        $this->currentState = $state;
        WorkflowObserver::getInstance()->notify();
    }

    public function proceed(TransitionStrategy $strategy) {
        $this->currentState->proceedToNext($this, $strategy);
    }
}
```

---

### 3️⃣ **Gestion des Transitions (Strategy Pattern)**

Les transitions entre les états sont gérées dynamiquement.

```php
interface TransitionStrategy {
    public function executeTransition(StateContext $context, WorkflowState $nextState);
}

class DefaultTransitionStrategy implements TransitionStrategy {
    public function executeTransition(StateContext $context, WorkflowState $nextState) {
        $context->setState($nextState);
    }
}

class ApprovalTransitionStrategy implements TransitionStrategy {
    public function executeTransition(StateContext $context, WorkflowState $nextState) {
        echo "Validation nécessaire avant approbation\n";
        $context->setState($nextState);
    }
}
```

---

### 4️⃣ **Validation des Transitions (Chain of Responsibility Pattern)**

Chaque étape doit passer par une série de vérifications.

```php
abstract class TransitionValidator {
    protected $next;

    public function setNext(TransitionValidator $validator) {
        $this->next = $validator;
    }

    public function validate($request) {
        if ($this->next) {
            return $this->next->validate($request);
        }
        return true;
    }
}

class DocumentExistsValidator extends TransitionValidator {
    public function validate($request) {
        if (!$request['documentExists']) {
            echo "Erreur : Le document n'existe pas.\n";
            return false;
        }
        return parent::validate($request);
    }
}

class UserHasPermissionValidator extends TransitionValidator {
    public function validate($request) {
        if (!$request['userHasPermission']) {
            echo "Erreur : L'utilisateur n'a pas les droits.\n";
            return false;
        }
        return parent::validate($request);
    }
}
```

---

### 5️⃣ **Notifications (Observer Pattern)**

Notifier les utilisateurs lors des changements d'état.

```php
class WorkflowObserver {
    private static $instance;
    private $observers = [];

    private function __construct() {}

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new WorkflowObserver();
        }
        return self::$instance;
    }

    public function attach(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function notify() {
        foreach ($this->observers as $observer) {
            $observer->update();
        }
    }
}

interface Observer {
    public function update();
}

class EmailNotificationObserver implements Observer {
    public function update() {
        echo "🔔 Notification : Changement d'état du workflow.\n";
    }
}
```

---

### 6️⃣ **Factory Pattern**

Crée dynamiquement les états et les stratégies.

```php
class WorkflowFactory {
    public static function createState($state) {
        switch ($state) {
            case 'draft': return new DraftState();
            case 'submitted': return new SubmittedState();
            case 'approved': return new ApprovedState();
            case 'rejected': return new RejectedState();
            default: throw new Exception("État inconnu");
        }
    }

    public static function createStrategy($type) {
        switch ($type) {
            case 'default': return new DefaultTransitionStrategy();
            case 'approval': return new ApprovalTransitionStrategy();
            default: throw new Exception("Stratégie inconnue");
        }
    }
}
```

---

## 🎬 **Exécution du Workflow**

Simulation d'un document qui passe par plusieurs étapes.

```php
// Création des objets
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
```

---

## ✅ **Explication**

Ce système combine :

1. **State Pattern** → Définit les différents états du workflow.
2. **Strategy Pattern** → Permet de choisir la stratégie de transition.
3. **Observer Pattern** → Envoie une notification lors d'un changement d'état.
4. **Chain of Responsibility** → Applique une série de validations avant une transition.
5. **Factory Pattern** → Crée dynamiquement les états et les stratégies.

---

## 🏆 **Avantages**

✔ **Flexible** : Les états et transitions sont entièrement configurables.  
✔ **Extensible** : Ajout facile de nouveaux états ou stratégies.  
✔ **Testable** : Chaque composant peut être testé indépendamment.

---

C'est un excellent point de départ pour un **système de workflow configurable**. 🎯
