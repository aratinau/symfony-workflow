<?php

namespace App\Controller\Admin;

use App\Entity\OrderWorkflowPlaceData;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderWorkflowPlaceDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrderWorkflowPlaceData::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $entities = [
            'Category' => 'categories',
            'User' => 'User',
        ];


        return [
            ChoiceField::new('entity')
                ->setLabel('entity')
                ->setChoices($entities)
                ->allowMultipleChoices(false)
                ->renderExpanded(false),

            // TextField::new('entity'),
            TextField::new('key'),
            TextField::new('value'),
        ];
    }
}
