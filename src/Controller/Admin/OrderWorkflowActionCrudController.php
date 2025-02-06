<?php

namespace App\Controller\Admin;

use App\Entity\OrderWorkflowAction;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderWorkflowActionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrderWorkflowAction::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('name'),
            AssociationField::new('data', 'Donnée')
                ->setFormTypeOptions([
                    'choice_label' => fn ($data) => $data?->getKey() . ': ' . $data?->getValue(),
                ]),
            AssociationField::new('place', 'Lieu de Workflow')
                ->setFormTypeOptions([
                    'choice_label' => 'name',
                ]),
        ];
    }
}
