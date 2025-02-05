<?php

namespace App\Service;

class MermaidGenerator
{
    /**
     * Génère le code Mermaid pour un workflow.
     *
     * @param array $states Les états du workflow.
     * @param array $transitions Les transitions entre les états.
     * @return string Le code Mermaid généré.
     */
    public function generate(array $states, array $transitions): string
    {
        $mermaid = "graph LR\n";

        // Générer les transitions
        foreach ($transitions as [$from, $to, $label]) {
            $mermaid .= "    {$from}[" . $states[$from] . "] -->|$label| {$to}[" . $states[$to] . "]\n";
        }

        // Définir les styles des états
        $mermaid .= "\n";
        $mermaid .= "    classDef draft fill:#add8e6,stroke:#000,stroke-width:2px;\n";
        $mermaid .= "    classDef submitted fill:#ffff99,stroke:#000,stroke-width:2px;\n";
        $mermaid .= "    classDef approved fill:#90ee90,stroke:#000,stroke-width:2px;\n";
        $mermaid .= "    classDef rejected fill:#ff7f7f,stroke:#000,stroke-width:2px;\n";

        foreach ($states as $key => $label) {
            $mermaid .= "    class $key $key;\n";
        }

        return $mermaid;
    }
}
