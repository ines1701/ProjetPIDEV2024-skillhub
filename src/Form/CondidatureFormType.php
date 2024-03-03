<?php

namespace App\Form;

use App\Entity\Condidature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;
use VictorPrdh\RecaptchaBundle\Form\ReCaptchaType;

class CondidatureFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('project_id', HiddenType::class) // Assuming it's a hidden field
            ->add('name')
            ->add('prenom')
            ->add('email')
            ->add('numTel')
            ->add('lettremotivation', FileType::class, [
                'label' => 'Lettre de motivation (PDF)',
                'required' => false, // since we have a separate file field
            ])
            ->add('cv', FileType::class, [
                'label' => 'Curriculum vitae (PDF)',
                'required' => false, // since we have a separate file field
            ])
            ->add("recaptcha", ReCaptchaType::class)
            ->add('Postuler',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Condidature::class,
        ]);
    }
}
