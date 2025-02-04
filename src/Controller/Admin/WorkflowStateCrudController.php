<?php

namespace App\Controller\Admin;

use App\Entity\WorkflowPlace;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class WorkflowStateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WorkflowPlace::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextareaField::new('description'),
            AssociationField::new('workflow'),  // Association vers Workflow
        ];
    }
}
