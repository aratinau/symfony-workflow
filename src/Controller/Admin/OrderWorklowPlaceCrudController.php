<?php

namespace App\Controller\Admin;

use App\Entity\OrderWorklowPlace;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class OrderWorklowPlaceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrderWorklowPlace::class;
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

        ];
    }
}
