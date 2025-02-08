Pour rendre la **WorkflowFactory** dynamique et configurable depuis une **base de données**, nous allons :

1. **Stocker les états et transitions en base**.
2. **Récupérer dynamiquement ces informations** pour créer le workflow.
3. **Associer des stratégies de transition aux workflows**.

---

## **1️⃣ Structure de la base de données**

Nous allons utiliser les tables suivantes :

### 📌 `workflow_states` (Stocke les états du workflow)

|id|name|description|
|---|---|---|
|1|draft|Document en brouillon|
|2|submitted|Document soumis|
|3|approved|Document approuvé|
|4|rejected|Document rejeté|

---

### 📌 `workflow_transitions` (Stocke les transitions entre les états)

|id|from_state|to_state|strategy|
|---|---|---|---|
|1|draft|submitted|default|
|2|submitted|approved|approval|
|3|submitted|rejected|default|

---

## **2️⃣ Modélisation des entités**

On crée des **classes PHP** pour représenter ces données.

```php
class WorkflowState {
    public int $id;
    public string $name;
    public string $description;

    public function __construct(int $id, string $name, string $description) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }
}

class WorkflowTransition {
    public int $id;
    public WorkflowState $fromState;
    public WorkflowState $toState;
    public string $strategy;

    public function __construct(int $id, WorkflowState $fromState, WorkflowState $toState, string $strategy) {
        $this->id = $id;
        $this->fromState = $fromState;
        $this->toState = $toState;
        $this->strategy = $strategy;
    }
}
```

---

## **3️⃣ Récupération dynamique des États et Transitions**

Nous allons utiliser **PDO** pour interagir avec la base.

```php
class WorkflowRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getStates(): array {
        $stmt = $this->pdo->query("SELECT * FROM workflow_states");
        $states = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $states[$row['name']] = new WorkflowState($row['id'], $row['name'], $row['description']);
        }
        return $states;
    }

    public function getTransitions(array $states): array {
        $stmt = $this->pdo->query("SELECT * FROM workflow_transitions");
        $transitions = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $fromState = $states[$row['from_state']];
            $toState = $states[$row['to_state']];
            $transitions[] = new WorkflowTransition($row['id'], $fromState, $toState, $row['strategy']);
        }
        return $transitions;
    }
}
```

---

## **4️⃣ Factory dynamique pour créer les États et Transitions**

On adapte la `WorkflowFactory` pour récupérer les informations en base.

```php
class WorkflowFactory {
    private WorkflowRepository $repository;
    private array $states;
    private array $transitions;

    public function __construct(WorkflowRepository $repository) {
        $this->repository = $repository;
        $this->states = $this->repository->getStates();
        $this->transitions = $this->repository->getTransitions($this->states);
    }

    public function createState(string $stateName): WorkflowState {
        if (!isset($this->states[$stateName])) {
            throw new Exception("État introuvable : $stateName");
        }
        return $this->states[$stateName];
    }

    public function getTransitionsForState(string $stateName): array {
        return array_filter($this->transitions, function ($transition) use ($stateName) {
            return $transition->fromState->name === $stateName;
        });
    }

    public function createStrategy(string $strategyType): TransitionStrategy {
        return match ($strategyType) {
            'default' => new DefaultTransitionStrategy(),
            'approval' => new ApprovalTransitionStrategy(),
            default => throw new Exception("Stratégie inconnue : $strategyType"),
        };
    }
}
```

---

## **5️⃣ Exécution du Workflow Dynamique**

On charge dynamiquement les états et transitions en base.

```php
// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=workflow', 'user', 'password');

// Création des repositories et de la factory
$repository = new WorkflowRepository($pdo);
$factory = new WorkflowFactory($repository);

// Création du workflow avec état initial
$workflow = new StateContext($factory->createState('draft'));
$observer = new EmailNotificationObserver();
WorkflowObserver::getInstance()->attach($observer);

// Récupération des transitions possibles
$transitions = $factory->getTransitionsForState('draft');

foreach ($transitions as $transition) {
    echo "🔄 Transition possible : " . $transition->fromState->name . " → " . $transition->toState->name . "\n";
}

// Simulation de validation
$documentRequest = ['documentExists' => true, 'userHasPermission' => true];

$validator1 = new DocumentExistsValidator();
$validator2 = new UserHasPermissionValidator();
$validator1->setNext($validator2);

// Exécution de la transition si validée
if ($validator1->validate($documentRequest)) {
    foreach ($transitions as $transition) {
        $workflow->proceed($factory->createStrategy($transition->strategy));
    }
}
```

---

## **🚀 Résumé**

1. **Stocker les états et transitions en base** ✅
2. **Récupérer dynamiquement les données avec une factory** ✅
3. **Appliquer les stratégies dynamiquement** ✅
4. **Gérer les transitions validées par des règles** ✅

---

## **🔥 Avantages**

✔ **100% Configurable** → Modifier les états/transitions sans toucher au code.  
✔ **Facile à maintenir** → Ajout d’états ou transitions via la base de données.  
✔ **Extensible** → Possibilité d'ajouter de nouvelles stratégies et validations.

---

Avec cette implémentation, vous avez un **workflow configurable via une base de données**, totalement **modulaire** et **adaptable** ! 🎯
