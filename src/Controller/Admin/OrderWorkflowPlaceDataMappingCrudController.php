<?php

namespace App\Controller\Admin;

use App\Entity\OrderWorkflowPlaceDataMapping;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderWorkflowPlaceDataMappingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrderWorkflowPlaceDataMapping::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            AssociationField::new('place', 'Lieu de Workflow')
            ->setFormTypeOptions([
                'choice_label' => 'name',
            ]),
            AssociationField::new('data', 'Donnée')
            ->setFormTypeOptions([
                'choice_label' => fn ($data) => $data?->getKey() . ': ' . $data?->getValue(),
            ]),
            TextField::new('name', 'Nom de la Donnée'),
        ];
    }
}
