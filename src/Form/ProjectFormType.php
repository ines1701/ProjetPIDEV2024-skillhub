<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('titre')
        ->add('categorie')
        ->add('periode', ChoiceType::class, [
            'choices' => [
                '1 à 3 mois' => '1 à 3 mois',
                '3 à 6 mois' => '3 à 6 mois',
                'Plus que 6 mois' => 'Plus que 6 mois',
            ],
            ])
        ->add('budget')
        ->add('portee', ChoiceType::class, [
            'choices' => [
                'Large' => 'Large',
                'Medium' => 'Medium',
                'Petit' => 'Petit',
            ],
            ])
        ->add('description')
        ->add('ajouter',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
