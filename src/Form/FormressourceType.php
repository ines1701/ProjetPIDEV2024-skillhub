<?php

namespace App\Form;

use App\Entity\Ressource;
use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FormressourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('file', FileType::class, [
                'label' => 'Entrer un fichier',
                'mapped' => false,
                'required' => false])
                
                ->add('formation', EntityType::class, [
                    'class' => Formation::class,
                    'choice_label' => 'titre',
                    'required' => true,
                    'placeholder' => 'Choisissez une formation', // Optionnel : Ajoutez un libellé par défaut
                    // Autres options du champ EntityType
                ]);
               
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ressource::class,
        ]);
    }
}
