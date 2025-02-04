<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkflowStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('newStatus', ChoiceType::class, [
                'choices' => $options['transitions'], // Injecté depuis le contrôleur
                'label' => 'Nouveau statut',
                'placeholder' => 'Sélectionner un statut',
            ])
            ->add('save', SubmitType::class, ['label' => 'Changer le statut']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'transitions' => [], // Passé en option depuis le contrôleur
        ]);
    }
}
