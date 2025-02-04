# Symfony Workflow

![CI](https://github.com/dunglas/symfony-docker/workflows/CI/badge.svg)

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images
3. Run `docker compose up --pull always -d --wait` to set up and start a fresh Symfony project
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Run `docker compose down --remove-orphans` to stop the Docker containers.

## Event

Utilisation des Événements dans un Workflow Symfony

Symfony offre une manière puissante d'interagir avec les workflows via des événements. Ces événements permettent d'exécuter du code personnalisé à différents moments du cycle de vie d'un workflow, tels que lors du déclenchement d'une transition ou de l'entrée dans un nouvel état.
Principaux Événements

    workflow.enter : Déclenché lorsqu'un sujet entre dans un nouvel état. Vous pouvez l'utiliser pour réagir à l'entrée dans un état spécifique.

    workflow.leave : Déclenché juste avant qu'un sujet quitte un état actuel. Il peut être utilisé pour effectuer des actions avant de quitter un état.

    workflow.transition : Déclenché lors de l'application d'une transition. Cela vous permet d'intervenir au moment où une transition se produit, par exemple pour valider des conditions spécifiques ou effectuer des actions immédiates.

    workflow.guard : Cet événement est déclenché avant qu'une transition ne soit appliquée et permet d'annuler une transition si certaines conditions ne sont pas remplies.

    workflow.completed : Déclenché après qu'une transition a été appliquée avec succès, ce qui peut être utilisé pour effectuer des actions après un changement d'état.

    workflow.announce : Utilisé pour annoncer les transitions possibles après une transition réussie, vous permettant d'anticiper les prochaines étapes.

Utilisation des Écouteurs (Event Listeners)

Pour tirer parti de ces événements, vous pouvez créer des écouteurs d'événements (EventListener) ou des abonnés d'événements (EventSubscriber). Ces composants vous permettent d'exécuter du code à des moments précis du cycle de vie du workflow, tels que l'envoi de notifications, la mise à jour d'autres entités, ou la journalisation des changements d'état.

## Design patterns

### - 1. State Pattern 
### - 2. Strategy Pattern  
### - 3. Observer Pattern 
### - 4. Chain of Responsibility 

## Workflow Home made

```sql
INSERT INTO workflow (id, name, description) VALUES
    (1, 'Validation d''article', 'Workflow de validation des articles avant publication');

INSERT INTO workflow_state (id, workflow_id, name, description) VALUES
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
