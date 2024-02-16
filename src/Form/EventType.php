<?php

namespace App\Form;
use App\Entity\TypeEvent;
use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('describ', TextareaType::class, [
                'attr' => ['class' => 'describ-textarea', 'rows' => 5], // Classe CSS et nombre de lignes
            ])

            ->add('lieu')
            
            ->add('date')
            ->add('imageFile', FileType::class, [
                'label' => 'Image (JPEG, PNG)',
                'mapped' => false,
                'required' => false,
            ])
            ->add('type', EntityType::class, [
                'class' => TypeEvent::class,
                'choice_label' => 'label',
            ]);
    
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
