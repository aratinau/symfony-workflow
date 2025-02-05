<?php

namespace App\Controller\Admin;

use App\Entity\OrderWorkflowPlaceData;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
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
        return [
            TextField::new('key'),
            TextField::new('value'),
        ];
    }
}
