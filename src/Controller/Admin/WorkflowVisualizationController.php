<?php

namespace App\Controller\Admin;

use App\Entity\Workflow;
use App\Entity\Workflow\Place;
use App\Entity\Workflow\Transition;
use App\Entity\WorkflowPlace;
use App\Entity\WorkflowTransition;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WorkflowVisualizationController extends AbstractCrudController
{

    private const ROLES = [
        'ROLE_USER',
        'ROLE_MANAGER',
        'ROLE_TECHNICIAN',
        'ROLE_SECRETARY',
        'ROLE_RECEPTION',
        'ROLE_STATISTICS_ANSWERS',
        'ROLE_PAGE_COURRIER_FILES',
        'ROLE_ADMIN',
        'ROLE_SUPER_ADMIN'
    ];


    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return WorkflowPlace::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Visualisation du Workflow')
            //  ->overrideTemplate('crud/index', 'admin/workflow_visualization.html.twig')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name')
            ->setHelp('Le nom unique de cet état dans le workflow');
        yield ChoiceField::new('target')
            ->setChoices([
                'Element' => 'App\Entity\Courier',
                // Ajoutez d'autres entités supportant le workflow ici
            ])
            ->setHelp('L\'entité à laquelle cet état s\'applique');
    }

    private function generateMermaidDefinition(Workflow $workflow): string
    {
        $places = $workflow->getStates();
        $transitions = $workflow->getTransitions();

        $mermaid = "stateDiagram-v2\n";

        // Ajouter les états
        foreach ($places as $place) {
            $mermaid .= "    " . $this->sanitizeName($place->getName()) . "\n";
        }

        // Ajouter les transitions
        foreach ($transitions as $transition) {
            $mermaid .= sprintf(
                "    %s --> %s: %s\n",
                $this->sanitizeName($transition->getFromState()->getName()),
                $this->sanitizeName($transition->getToState()->getName()),
                /*$this->sanitizeName($transition->getName())
                $this->sanitizeName($transition->getFromState()->getName()),*/
                'transition name'
            );
        }

        return $mermaid;
    }

    private function sanitizeName(string $name): string
    {
        return str_replace(' ', '_', $name);
    }

    #[Route('/admin/workflow/{workflow}', name: 'admin_workflow_visualization')]
    public function visualize(Workflow $workflow): Response
    {
        $mermaidDefinition = $this->generateMermaidDefinition($workflow);

        return $this->render('admin/workflow_visualization.html.twig', [
            'mermaid_definition' => $mermaidDefinition,
            'workflow' => $workflow
        ]);
    }
}
