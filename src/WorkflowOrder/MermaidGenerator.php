<?php

namespace App\WorkflowOrder;

use App\Entity\OrderWorkflow;
use App\Entity\OrderWorkflowPlace;
use App\Entity\OrderWorkflowTransition;

class MermaidGenerator
{
    /**
     * Génère le code Mermaid pour un workflow.
     *
     * @param OrderWorkflow $workflow L'objet OrderWorkflow contenant les états et les transitions.
     * @return string Le code Mermaid généré.
     */
    public function generate(OrderWorkflow $workflow): string
    {
        $mermaid = "graph LR\n";

        // Récupérer tous les états (places) du workflow
        $places = $workflow->getOrderWorkflowPlaces();

        // Parcourir chaque état pour générer les transitions
        foreach ($places as $place) {
            // Récupérer les transitions sortantes de cet état
            $outgoingTransitions = $place->getOutgoingTransitions();

            foreach ($outgoingTransitions as $transition) {
                $fromPlace = $transition->getFromPlace()->getName();
                $toPlace = $transition->getToPlace()->getName();
                $label = $transition->getName();

                // Ajouter la transition au diagramme Mermaid
                $mermaid .= "    {$fromPlace} -->|{$label}| {$toPlace}\n";
            }
        }

        // Définir les styles des états
        $mermaid .= "\n";
        $mermaid .= "    classDef draft fill:#add8e6,stroke:#000,stroke-width:2px;\n";
        $mermaid .= "    classDef submitted fill:#ffff99,stroke:#000,stroke-width:2px;\n";
        $mermaid .= "    classDef approved fill:#90ee90,stroke:#000,stroke-width:2px;\n";
        $mermaid .= "    classDef rejected fill:#ff7f7f,stroke:#000,stroke-width:2px;\n";

        // Appliquer les styles aux états
        foreach ($places as $place) {
            $placeName = $place->getName();
            $placeType = strtolower($placeName); // Simplification pour l'exemple
            $mermaid .= "    class {$placeName} {$placeType};\n";
        }

        return $mermaid;
    }
}
