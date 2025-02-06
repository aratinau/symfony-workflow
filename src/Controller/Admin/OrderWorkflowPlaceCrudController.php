<?php

namespace App\Controller\Admin;

use App\Entity\OrderWorkflowPlace;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class OrderWorkflowPlaceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrderWorkflowPlace::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            AssociationField::new('allowedTransitions', 'Transitions')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'choice_label' => 'name',
                ]),

            AssociationField::new('workflow', 'Workflow')
                ->setFormTypeOption('choice_label', 'name')
                ->setRequired(true)
                ->formatValue(function ($value, $entity) {
                    return $entity->getWorkflow() ? $entity->getWorkflow()->getName() : 'N/A';
                }),

            TextField::new('conditions')
                ->setFormTypeOptions(['required' => false])
                ->setLabel('Conditions'),

            // Champ pour l'attribut 'allowedRoles' (type: json)
            ArrayField::new('allowedRoles')
                ->setFormTypeOptions(['required' => false])
                ->setLabel('Allowed Roles'),

            // Champ pour l'attribut 'actions' (type: json)
            ArrayField::new('actions')
                ->setFormTypeOptions(['required' => false])
                ->setLabel('Actions'),
        ];
    }
}
