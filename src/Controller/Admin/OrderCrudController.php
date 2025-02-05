<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('state'),
            TextField::new('name'),
            AssociationField::new('owners', 'Propriétaires')
            ->setFormTypeOptions([
                'by_reference' => false,
                'choice_label' => 'fullname', // Spécifie l'attribut à afficher

            ]),
            AssociationField::new('categories', 'Catégories')
            ->setFormTypeOptions([
                'by_reference' => false,
                'choice_label' => 'name', // Spécifie l'attribut à afficher

            ]),
            IntegerField::new('amount'),
        ];

    }
}
