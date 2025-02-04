<?php

namespace App\Controller\Admin;

use App\Entity\WorkflowTransition;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class WorkflowTransitionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WorkflowTransition::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('fromState'),  // Association vers WorkflowState (état de départ)
            AssociationField::new('toState'),    // Association vers WorkflowState (état d'arrivée)
            ChoiceField::new('strategy')->setChoices([
                'Manual' => 'manual',
                'Auto' => 'auto',
            ]),
        ];
    }
}
