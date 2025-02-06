# Symfony Workflow

## Getting Started

`make up`

## Webmail

http://localhost:8025/

## Workflow Home made

### - 1. State Pattern
### - 2. Strategy Pattern
### - 3. Observer Pattern
### - 4. Chain of Responsibility

- Order.php
- OrderWorkflow.php
- OrderWorkflowPlace.php
- OrderWorkflowPlaceData.php
- OrderWorkflowPlaceDataMapping.php


```sql
INSERT INTO workflow (id, name, description) VALUES
    (1, 'Validation d''article', 'Workflow de validation des articles avant publication');

INSERT INTO workflow_place (id, workflow_id, name, description) VALUES
(1, 1, 'draft', 'Brouillon en attente de validation'),
(2, 1, 'review', 'En cours de relecture par un administrateur'),
(3, 1, 'approved', 'Approuvé et publié'),
(4, 1, 'rejected', 'Rejeté et retourné en brouillon');

INSERT INTO workflow_transition (id, workflow_id, from_state_id, to_state_id, strategy) VALUES
(1, 1, 1, 2, 'manual'),   -- Brouillon → Relecture (Action manuelle)
(2, 1, 2, 3, 'auto'),     -- Relecture → Approuvé (Action automatique)
(3, 1, 2, 4, 'manual'),   -- Relecture → Rejeté (Action manuelle)
(4, 1, 4, 1, 'manual');   -- Rejeté → Brouillon (Retour possible)


```
