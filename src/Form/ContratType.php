<?php

namespace App\Form;

use App\Entity\Contrat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ContratType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_client')

            ->add('montant')
            ->add('description', ChoiceType::class, [
            'choices' => [
                'confirmé' => 'confirmé',
                
            ],
            'multiple' => false, // Allow only single choice
            'expanded' => false,
            // other options as needed
        ])
            ->add('image', FileType::class, [
                'label' => 'Image du virement',
                'mapped' => false, // Indique à Symfony de ne pas mapper ce champ à une propriété de l'entité
                'required' => false, // Le champ n'est pas obligatoire
            ])
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contrat::class,
        ]);
    }
}
