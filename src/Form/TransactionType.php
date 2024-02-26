<?php

namespace App\Form;

use App\Entity\Transaction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
        ->add('methode_paiement', ChoiceType::class, [
            'choices' => [
                'virement' => 'virement',
                'chéque' => 'chéque',
            ],
            'multiple' => false, // Allow only single choice
            'expanded' => false,
            // other options as needed
        ])
            ->add('montant')
            ->add('Contrat')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
